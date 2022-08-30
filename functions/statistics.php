<?php

function update_gambling($id, $amount, $pdo){
    if($amount == null){
        $amount = 0;
    } else {
        $amount = $amount;
    }
    $sql = "UPDATE user_statistics SET US_gambling = (US_gambling + $amount) WHERE US_acc_id='".$id."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function update_drap($id, $amount, $pdo){
    $sql = "UPDATE user_statistics SET US_kills = (US_kills + $amount) WHERE US_acc_id='".$id."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function update_crime($id, $status, $pdo){
    if($status == 1){
        $sql = "UPDATE user_statistics SET US_krim_v = (US_krim_v + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "UPDATE user_statistics SET US_krim_m = (US_krim_m + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

function update_gta($id, $status, $pdo){
    if($status == 1){
        $sql = "UPDATE user_statistics SET US_gta_v = (US_gta_v + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "UPDATE user_statistics SET US_gta_m = (US_gta_m + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

function update_race($id, $status, $pdo){
    if($status == 1){
        $sql = "UPDATE user_statistics SET US_rc_v = (US_rc_v + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "UPDATE user_statistics SET US_rc_m = (US_rc_m + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

function update_brekk($id, $status, $pdo){
    if($status == 1){
        $sql = "UPDATE user_statistics SET US_brekk_v = (US_brekk_v + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "UPDATE user_statistics SET US_brekk_m = (US_brekk_m + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

function update_stjel($id, $status, $pdo){
    if($status == 1){
        $sql = "UPDATE user_statistics SET US_stjel_v = (US_stjel_v + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "UPDATE user_statistics SET US_stjel_m = (US_stjel_m + 1) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

function update_transfer_money($id, $status, $value, $pdo){
    if($status == 1){
        $sql = "UPDATE user_statistics SET US_money_sent = (US_money_sent + $value) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "UPDATE user_statistics SET US_money_received = (US_money_received + $value) WHERE US_acc_id='".$id."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

function update_utbrytninger($id, $pdo){
    $sql = "UPDATE user_statistics SET US_jail = (US_jail + 1) WHERE US_acc_id='".$id."'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

?>