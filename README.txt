shrinking.py    -> združi oba csv-ja iz mape Porocilo_o_uspesnosti_TP_2022 
                -> kreira datoteko rezulati_TP_22.csv
temp_shrink.py  -> kot vhod vzame datoteko rezulati_TP_22.csv
                -> kreira datoteki cars.csv in resultTP.csv

                cars.csv        -> vsi avti, ki so bili na tehničnem v sloveniji leta 2022
                                -> vsebuje stolpce:
                                'VIN',
                                'ZNAMKA',
                                'KOMERCIALNI_TIP',
                                'KATEGORIJA_OZNAKA',   
                                'VRSTA_GORIVA_OZNAKA',
                                'PREVOZENI_KILOMETRI',
                                'DATUM_PRVE_REGISTRACIJE',
                                'DATUM_PRVE_REGISTRACIJE_SLO'
                                            
                
                resultTP.csv    -> rezultati tehničnih pregledov
                                -> vsebuje stolpce:
                                'VIN',
                                'TEHNICNI_ZAPISNIK_RAZLOG',
                                'TEHNICNI_PREGLED_STATUS',
                                'VELJA_OD',
                                'VELJA_DO'
                                