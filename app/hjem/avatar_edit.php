<?php

ob_start();
include '../../env.php';
include_once '../../auth.php';
include_once '../../functions/functions.php';


if(isset($_POST['option'])){
    $option = $_POST['option'];
    
    $stmt = $pdo->prepare("UPDATE accounts_stat SET AS_avatar = :as_avatar WHERE AS_id = :as_id");
    $stmt->bindParam(":as_avatar", $option);
    $stmt->bindParam(":as_id", $_SESSION['ID']);
    $stmt->execute();
}
