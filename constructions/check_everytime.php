<?php

// HER SKAL DET VÆRE MINST MULIG KODE
$sql = "DELETE FROM fengsel WHERE FENG_countdown < ".time()."";
$pdo -> exec($sql);

$sql = "DELETE FROM double_xp WHERE DX_date < ".time()."";
$pdo -> exec($sql);

$percentage = 50;

$cooldown[0] = time() + 2400;
$cooldown[1] = time() + 3600;

// Heist
$notification_failed = "Dere klarte ikke heistet innenfor fristen og gruppen oppløses";
$statement = $pdo->prepare("SELECT HEIST_countdown, HEIST_id FROM heist WHERE HEIST_countdown < :time");
$statement->execute(array(':time' => time()));
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    if($row['HEIST_countdown'] != 0){
        $heist_id = $row['HEIST_id'];

        $heist_members = heist_members($row['HEIST_id'], $pdo);
        foreach($heist_members as $member){
            send_notification($member->ACC_id, $notification_failed, $pdo);

            heist_give_cooldown($member->ACC_id, $cooldown[heist_row($heist_id, 'HEIST_type', $pdo)], $pdo);
        }
        
        $sql = "DELETE heist, heist_members FROM heist INNER JOIN heist_members ON HEIST_id=HEIME_heist_id WHERE HEIST_id=?";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute([$heist_id]);
    }
}

?>