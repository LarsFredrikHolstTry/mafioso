<?php

ob_start();
include_once '../db_cred/db_cred.php';

if(session_destroy()) {
    $cookie_name = "remember_forever";
    if(isset($_COOKIE[$cookie_name])) {
        setcookie($cookie_name, time() - 3600);
    }
    
    header("Location: login.php?act=loggut");
}

?>