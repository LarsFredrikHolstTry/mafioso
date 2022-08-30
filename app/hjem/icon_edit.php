<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../auth.php';
include_once '../../functions/functions.php';

if(isset($_POST['icon'])){
    $icon = $_POST['icon'];
    $zero = 0;
    $one = 1;
    
    $stmt = $pdo->prepare("UPDATE charm SET CH_use = :unuse WHERE CH_use = :use AND CH_acc_id = :id");
    $stmt->bindParam(":unuse", $zero);
    $stmt->bindParam(":use", $one);
    $stmt->bindParam(":id", $_SESSION['ID']);
    $stmt->execute();
    
    $stmt = $pdo->prepare("UPDATE charm SET CH_use = :use WHERE CH_acc_id = :id AND CH_charm = :charm");
    $stmt->bindParam(":use", $one);
    $stmt->bindParam(":id", $_SESSION['ID']);
    $stmt->bindParam(":charm", $icon);
    $stmt->execute();
}

?>