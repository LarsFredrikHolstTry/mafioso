<?php

ob_start();
include '../../env.php';
include_once '../../auth.php';
include_once '../../functions/functions.php';

if (0 < $_FILES['file']['error'] ) {
    echo 'feil';
} else {

    $name = $_FILES['file']['name'];
    $target_dir = "././img/avatar/avatar";
    $target_dir_really = "../../img/avatar/avatar";
    $target_file = $target_dir . time() . "-" .  basename($_FILES["file"]["name"]);

    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $extensions_arr = array("jpg","jpeg","png","gif");

    if(in_array($imageFileType, $extensions_arr)){

        $stmt = $pdo->prepare("UPDATE accounts_stat SET AS_avatar = :as_avatar WHERE AS_id = :as_id");
        $stmt->bindParam(":as_avatar", $target_file);
        $stmt->bindParam(":as_id", $_SESSION['ID']);
        $stmt->execute();
        
        $sql = "INSERT INTO uploaded_avatar (UOA_acc_id, UOA_avatar) VALUES (?,?)";
        $pdo -> prepare($sql)->execute([$_SESSION['ID'], $target_file]);

        move_uploaded_file(
            $_FILES['file']['tmp_name'],
            $target_dir_really. time() . "-" .$name
        );
    } else {
        echo "Ugyldig filtype";
    }
}
