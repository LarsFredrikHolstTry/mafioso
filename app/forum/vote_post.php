<?php

ob_start();
include '../../env.php';
include_once '../../functions/functions.php';
include_once '../../auth.php';
include_once '../../functions/fynn_check.php';

$type = $_POST['type'];
$forum_id = $_POST['forum_id'];

$stmt = $pdo->prepare("SELECT count(*) FROM forum_likes WHERE FLIKES_forum_id = ? AND FLIKES_acc_id = ?");
$stmt->execute([$forum_id, $_SESSION['ID']]);
$count = $stmt->fetchColumn();

if($count == 0){
    $sql = "INSERT INTO forum_likes (FLIKES_forum_id, FLIKES_type, FLIKES_acc_id, FLIKES_date) VALUES (?,?,?,?)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$forum_id, $type, $_SESSION['ID'], time()]);
    
    $response = get_forum_likes($forum_id, $pdo);
} else {
    $sql = "DELETE FROM forum_likes WHERE FLIKES_forum_id=? AND FLIKES_acc_id=?";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$forum_id, $_SESSION['ID']]);
}

echo $response;
