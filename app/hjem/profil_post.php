<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../auth.php';
include_once '../../functions/functions.php';

$trusted_img_hosts = ['imma.gr', 'imgur.com'];

if(isset($_POST['profile_text'])){
    $profile_text = htmlspecialchars($_POST['profile_text']);
    $background_color = $_POST['background_color'] ?? NULL;
    $background_image = $_POST['background_image'] ?? NULL;
    $background_type = $_POST['background_type'] ?? NULL;
    
    if($background_image == ''){
        $background_image = NULL;
    }
    else if(!preg_match("/^https:\/\/(" . join('|', $trusted_img_hosts) . ").*/", $background_image)){
        http_response_code(400);
        die("Kun bilder som er lastet opp til <a target='_blank' href='https://imma.gr/'>imma.gr</a> eller <a target='_blank' href='https://imgur.com'>imgur.com</a> er tillatt");
    }
    $stmt = $pdo->prepare("UPDATE accounts_stat SET AS_bio = :as_bio, AS_bio_bg_color = :bg_color, AS_bio_bg_image = :bg_image, AS_bio_bg_active = :bg_type WHERE AS_id = :as_id");
    $stmt->execute(array(
        ':as_bio'   => $profile_text,
        ':bg_color' => $background_color,
        ':bg_image' => $background_image,
        ':bg_type'  => $background_type,
        ':as_id'    => $_SESSION['ID']
    ));
    
    echo 'Profilen ble oppdatert';
}
?>