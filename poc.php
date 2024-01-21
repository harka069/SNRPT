<?php
$DEBUG = true;

include("orodja.php"); 			// Vključitev 'orodij'

$zbirka = dbConnect();			//Pridobitev povezave s podatkovno zbirko

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');	// Dovolimo dostop izven trenutne domene (CORS)
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

switch($_SERVER["REQUEST_METHOD"])			//glede na HTTP metodo izvedemo ustrezno dejanje nad virom
{
	case 'GET':
        
		if(!empty($_GET["vin"]))
		{   
			TP_stanje($_GET["vin"]); 
		} elseif (!empty($_GET["datum"])) {
            $datum = $_GET["datum"];
            avto_znamka_datum($datum);
        } /*elseif (!empty($_GET["start_date"]  ) && 
                !empty($_GET["end_date"]  ) 
                ){
            $start_date = $_GET["start_date"];
            $end_date   = $_GET["end_date"];
            avto_znamka_datum_range($start_date, $end_date);
        }*/
        elseif (!empty($_GET["start_date"]  ) && 
                !empty($_GET["end_date"]    ) &&
                !empty($_GET["znamka"]      ) &&
                !empty($_GET["model"]    )
                ){
            $start_date = $_GET["start_date"];
            $end_date   = $_GET["end_date"];
            $znamka     = $_GET["znamka"];
            $model      = $_GET["model"];
            avto_uspesnost_na_TP($start_date, $end_date,$znamka,$model);
        }else
		{
			echo "bad";
            http_response_code(400);	// Bad Request
		}
		break;
	/*	
	case 'POST':
			dodaj_igro();
		break;*/
		
	default:
		http_response_code(405);	//Method Not Allowed
		break;
}

mysqli_close($zbirka);					// Sprostimo povezavo z zbirko

function TP_stanje($vin) // input VIN, vrne vse zapise kjer je bil avto zavrnjen na tenhičnih pregledih
{
	global $zbirka;
	$vin=mysqli_escape_string($zbirka, $vin);
	$odgovor=array();
	
	if(avto_obstaja($vin))
	{
		$poizvedba="SELECT * FROM rezultatiTP WHERE VIN='$vin' AND  rezultatiTP.TEHNICNI_PREGLED_STATUS != 'brezhiben'";
       
		/*$poizvedba="SELECT 
                        rezultatiTP.VIN,
                        rezultatiTP.TEHNICNI_PREGLED_STATUS,
                        cars.ZNAMKA,
                        cars.VIN
                    
                    FROM 
                        rezultatiTP
                            INNER JOIN
                        cars on cars.VIN = 	rezultatiTP.VIN
                        
                    WHERE
                        rezultatiTP.TEHNICNI_PREGLED_STATUS = 'ni brezhiben - kriticna napaka'";  */  
		$result=mysqli_query($zbirka, $poizvedba);

		while($vrstica=mysqli_fetch_assoc($result))
		{
			$odgovor[]=$vrstica;
		}
		
		http_response_code(200);		//OK
		echo json_encode($odgovor);
	}
	else
	{
		http_response_code(404);	// Not Found
	}
}
function avto_znamka_datum_range($start_date, $end_date)
{
    global $zbirka;
    $start_date = mysqli_real_escape_string($zbirka, $start_date);
    $end_date = mysqli_real_escape_string($zbirka, $end_date);
    $odgovor = array();

    $poizvedba = "  SELECT car.VIN 
                    FROM car
                    WHERE STR_TO_DATE(car.DATUM_PRVE_REGISTRACIJE, '%d.%m.%Y') 
                    BETWEEN '$start_date' AND '$end_date'";

    $result = mysqli_query($zbirka, $poizvedba);

    while ($vrstica = mysqli_fetch_assoc($result)) {
        $odgovor[] = $vrstica;
    }

    http_response_code(200);    // OK
    echo json_encode($odgovor);
}
function avto_znamka_datum($datum)
{
    global $zbirka;
    //$vin=mysqli_escape_string($zbirka, $vin);
    $datum=mysqli_real_escape_string($zbirka, $datum);
    $odgovor=array();
    
    

        $poizvedba="SELECT * FROM cars WHERE STR_TO_DATE(cars.DATUM_PRVE_REGISTRACIJE, '%d.%m.%Y') > '$datum' ";
        

        $result=mysqli_query($zbirka, $poizvedba);

        while($vrstica=mysqli_fetch_assoc($result))
        {
            $odgovor[]=$vrstica;
        }
        
        http_response_code(200);		//OK
        echo json_encode($odgovor);
}
function avto_uspesnost_na_TP($start_date, $end_date,$znamka,$model)
{
    global $zbirka;
    $start_date = mysqli_real_escape_string($zbirka, $start_date);
    $end_date = mysqli_real_escape_string($zbirka, $end_date);
    $znamka = mysqli_real_escape_string($zbirka, $znamka);
    $model = mysqli_real_escape_string($zbirka, $model);
    $odgovor = array();
    /*$poizvedba = "  SELECT * 
                    FROM car 
                    WHERE STR_TO_DATE(car.DATUM_PRVE_REGISTRACIJE, '%d.%m.%Y')
                    BETWEEN '$start_date' AND '$end_date'
                    AND car.ZNAMKA = '$znamka' 
                    AND car.KOMERCIANI_TIP = '$model'";*/

   // $poizvedba = "SELECT * FROM car WHERE ZNAMKA='$znamka'"; 
    $poizvedba = "  SELECT *
                    FROM car
                    WHERE  STR_TO_DATE(car.DATUM_PRVE_REGISTRACIJE, '%d.%m.%Y') 
                    BETWEEN '$start_date' AND '$end_date'
                    AND ZNAMKA = '$znamka'
                    AND KOMERCIALNI_TIP = '$model'"; 
    
    $result = mysqli_query($zbirka, $poizvedba);

    while ($vrstica = mysqli_fetch_assoc($result)) {
        $odgovor[] = $vrstica;
    }

    http_response_code(200);    // OK
    echo json_encode($odgovor);
}


















function dodaj_igro()
    {
        global $zbirka, $DEBUG;
        
        $podatki = json_decode(file_get_contents('php://input'), true);
        
        if(isset($podatki["vin"], $podatki["tezavnost"], $podatki["rezultat"]))
        {
            if(igralec_obstaja($podatki["vin"]))	//preprecimo napako zaradi krsitve FK 
            {
                $vin = mysqli_escape_string($zbirka, $podatki["vin"]);
                $tezavnost = mysqli_escape_string($zbirka, $podatki["tezavnost"]);
                $rezultat = mysqli_escape_string($zbirka, $podatki["rezultat"]);
                    
                $poizvedba="INSERT INTO odigrana_igra (vin, tezavnost, rezultat) VALUES ('$vin', $tezavnost, $rezultat)";
                
                if(mysqli_query($zbirka, $poizvedba))
                {
                    http_response_code(201);	// Created
                    // ne pošljemo URL-ja do vpisane igre, ker ne omogočamo vpogleda v posamezno igro
                }
                else
                {
                    http_response_code(500);	// Internal Server Error

                    if($DEBUG)	//Pozor: vračanje podatkov o napaki na strežniku je varnostno tveganje!
                    {
                        pripravi_odgovor_napaka(mysqli_error($zbirka));
                    }
                }
            }
            else
            {
                http_response_code(409);	// Conflict (in ne 404 - vir na katerega se tu sklicujemo je igra in ne igralec!)
                pripravi_odgovor_napaka("Igralec ne obstaja!");
            }
        }
        else
        {
            http_response_code(400);	// Bad Request
        }
    }
?>