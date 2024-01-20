import pandas as pd
import csv
file_path = 'rezultati_TP_22.csv'
column_car_id=[
    'VIN',
    'ZNAMKA',
    'KOMERCIALNI_TIP',
    'KATEGORIJA_OZNAKA',   
    'VRSTA_GORIVA_OZNAKA',
    'PREVOZENI_KILOMETRI',
    'DATUM_PRVE_REGISTRACIJE',
    'DATUM_PRVE_REGISTRACIJE_SLO',
    
    ]
column_report=[
    'VIN',
    'TEHNICNI_ZAPISNIK_RAZLOG',
    'TEHNICNI_PREGLED_STATUS',
    'VELJA_OD',
    'VELJA_DO'
    ]

df1 = pd.read_csv(file_path,encoding='latin1', engine='python', delimiter=',',usecols=column_car_id)
df1 = df1.iloc[1:]
df1 = df1[column_car_id]
df1 = df1.drop_duplicates(subset='VIN', keep='first')
#df1.to_csv('cars.csv', index=False)
df1.to_csv('cars.csv', index=False, quoting=csv.QUOTE_ALL, quotechar='"')

df2 = pd.read_csv(file_path,encoding='latin1', engine='python', delimiter=',',usecols=column_report)
df2 = df2.iloc[1:]
df2 = df2[column_report]
df2.to_csv('resultTP.csv', index=False, quoting=csv.QUOTE_ALL, quotechar='"')
df2.to_csv('resultTP.csv', index=False)
