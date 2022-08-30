# Oppsett av Mafioso 
Dette er hvordan man setter opp Mafioso på din maskin

## Steg 1
Last ned Xampp og kjør Apache og Mysql [(Last ned Xampp her)](https://www.apachefriends.org/index.html)

## Steg 2
Lag database på [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/) og kall den ```mafiosov2```

## Steg 3
Klon mafioso source koden til din maskin:
```git clone git@bitbucket.org:mafioso1/mafioso_main.git```
Dersom den ber om et passord er passordet: ```T7Yqp2cLMALz95s3H6Dt```

## Steg 4
Åpne ```db_cred_example.php```, rename den til ```db_cred.php``` og plasser den i en mappe med mappenavnet ```db_cred```
Mappen ```db_cred``` **må** ligge 1 mappe bak hele main-mappen.

## Steg 5
Kjør ```db_example.sql``` i phpmyadmin

## Steg 6
Opprett bruker på Mafioso via ```http://Localhost/MAPPENAVN``` åpne accounts i phpmyadmin og endre ACC_status til 1

Dersom man må skrive passord for hver eneste commit kan man skrive ```git config --global credential.helper store``` i terminalen