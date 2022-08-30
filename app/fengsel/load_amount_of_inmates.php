<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../functions/functions.php';
include_once '../../functions/dates.php';

if(people_in_jail($pdo) > 0){
    echo people_in_jail($pdo);
}
