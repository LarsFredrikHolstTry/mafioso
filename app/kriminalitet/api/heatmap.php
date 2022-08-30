<?php

ob_start();
include '../../../../db_cred/db_cred.php';
include_once '../../../functions/functions.php';


if(isset($_POST['x'])) { //if i have this post
    $sql = "INSERT INTO heatmap (HM_acc_id, HM_x, HM_y, HM_page, HM_date) VALUES (?,?,?,?,?)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$_SESSION['ID'], $_POST['x'], $_POST['y'], "kriminalitet", time()]);
}

?>