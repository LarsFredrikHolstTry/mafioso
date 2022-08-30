<?php 

if ( ! session_id() ) {
    session_start();
}

date_default_timezone_set('Europe/Oslo');

$db_host = "localhost"; //localhost server 
$db_user = "root"; //database username
$db_password = ""; //database password   
$db_name = "mafiosov2"; //database name

try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_password);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e){
    echo "Connection failed: ".$e->getMessage();
}

$days = [" ", "Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag", "Søndag"];
$month = [" ", "Januar", "Februar", "Mars", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Desember"];

?>
