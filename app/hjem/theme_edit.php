<?php

ob_start();
include '../../env.php';
include_once '../../auth.php';
include_once '../../functions/functions.php';

if(isset($_POST['theme'])){
    $theme = $_POST['theme'];
    
    $stmt = $pdo->prepare("UPDATE accounts_stat SET AS_theme = :as_theme WHERE AS_id = :as_id");
    $stmt->bindParam(":as_theme", $theme);
    $stmt->bindParam(":as_id", $_SESSION['ID']);
    $stmt->execute();
}
