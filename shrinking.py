import pandas as pd


file_path = '/home/kristof/FAKS/2L/Seminar_iz_nacrtovanja_programske/Podatki/Porocilo_o_uspesnosti_TP_2022/Porocilo-o-uspesnosti-tehnicnih-pregledov_2022_1.csv'
"""
want to keep folowing columns
"""
names=['ZNAMKA', 'KATEGORIJA_OZNAKA','VIN', 'VRSTA_GORIVA_OZNAKA', 'DATUM_PRVE_REGISTRACIJE','DATUM_PRVE_REGISTRACIJE_SLO','PREVOZENI_KILOMETRI','TEHNICNI_ZAPISNIK_RAZLOG','TEHNICNI_PREGLED_STATUS','VELJA_OD','VELJA_DO']
df1 = pd.read_csv(file_path,encoding='latin1', engine='python', delimiter=';',usecols=names)

"""
we are only interested in car, so category M1
"""
df1=df1[df1['KATEGORIJA_OZNAKA'] == 'M1']
df1.to_csv('rezultati_TP_22_01.csv', index=False)

file_path = '/home/kristof/FAKS/2L/Seminar_iz_nacrtovanja_programske/Podatki/Porocilo_o_uspesnosti_TP_2022/Porocilo-o-uspesnosti-tehnicnih-pregledov_2022_2.csv'
"""
want to keep folowing columns
"""
names=['ZNAMKA', 'KATEGORIJA_OZNAKA','VIN', 'VRSTA_GORIVA_OZNAKA', 'DATUM_PRVE_REGISTRACIJE','DATUM_PRVE_REGISTRACIJE_SLO','PREVOZENI_KILOMETRI','TEHNICNI_ZAPISNIK_RAZLOG','TEHNICNI_PREGLED_STATUS','VELJA_OD','VELJA_DO']
df2 = pd.read_csv(file_path,encoding='latin1', engine='python', delimiter=';',usecols=names)
"""
we are only interested in car, so category M1
"""
df2=df2[df2['KATEGORIJA_OZNAKA'] == 'M1']
df2.to_csv('rezultati_TP_22_02.csv', index=False)

df3 = pd.concat( [df1,df2], ignore_index=False) 
df3.to_csv('rezultati_TP_22.csv', index=False)
"""
remove duplicates entry in column VIN
"""
file_path = '/home/kristof/FAKS/2L/Seminar_iz_nacrtovanja_programske/Podatki/rezultati_TP_22.csv'
df4 = pd.read_csv(file_path,encoding='latin1', engine='python', delimiter=',',usecols=names)