<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../functions/functions.php';
include_once '../../functions/oppdrag.php';

$weed_id = 6;

$sql_DETSOK = "SELECT * FROM marked WHERE MARKED_cat = ".$weed_id."";
$stmt_DETSOK = $pdo->query($sql_DETSOK);
while ($row = $stmt_DETSOK->fetch(PDO::FETCH_ASSOC)) {
    $marked_id = $row['MARKED_id'];
    $marked_seller = $row['MARKED_seller'];

    $sql = "DELETE FROM marked WHERE MARKED_id = ".$marked_id."";
    $pdo -> exec($sql);
    
    $text = "Dine cannabis som lå på marked har blitt fjernet fordi det var midnatt.";
    send_notification($marked_seller, $text, $pdo);
}


$query = $pdo->prepare('SELECT * FROM accounts_stat WHERE AS_health > :health');
$query->execute(array(':health' => '0'));
foreach ($query as $row) {
    $money = money_by_midnight($row['AS_bankmoney'], $pdo);

    if($money > 0){
        give_bank_money($row['AS_id'], $money, $pdo);

        $text = "Du har fått ".number($money)." kr i renter";
        send_notification($row['AS_id'], $text, $pdo);
    }
    
    if(AS_session_row($row['AS_id'], 'AS_protection', $pdo) >= 1){
        $sql = "UPDATE accounts_stat SET AS_protection = AS_protection - 1 WHERE AS_id = ".$row['AS_id']."";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } 
    
    if($row['AS_boost'] == 0){
        $boost = 1;
    } else {
        $boost = 2;
    }

    if(get_daily_weed($row['AS_id'], $pdo) >= smugling_demand($row['AS_id'], $pdo)) {
        $money_utbetalt = (smugling_money_midnight($row['AS_id'], $pdo) * get_daily_weed($row['AS_id'], $pdo)) * $boost;
        
        if($money_utbetalt > 0){
            if($row['AS_mission'] == 20){
                mission_update($row['AS_mission_count'] + $money_utbetalt, $row['AS_mission'], mission_criteria($row['AS_mission']), $row['AS_id'], $pdo);
            }

            give_money($row['AS_id'], $money_utbetalt, $pdo);

            $sql = "DELETE FROM smugling_daily WHERE SMDAI_acc_Id = ".$row['AS_id']."";
            $pdo -> exec($sql);

            $text = "Du har fått ".number($money_utbetalt)." kr fra kartellet";
            send_notification($row['AS_id'], $text, $pdo);
        }
    } else {
        if(smugling_demand($row['AS_id'], $pdo) > 0){
            $sthandler = $pdo->prepare("SELECT * FROM smugling_daily WHERE SMDAI_acc_id = :id");
            $sthandler->bindParam(':id', $row['AS_id']);
            $sthandler->execute();

            if($sthandler->rowCount() > 0){
                $smugling_inactive_days = $sthandler->fetch(PDO::FETCH_ASSOC)['SMDAI_days'] ?? 0;
                
                if($smugling_inactive_days == 6){
                    $text = "Dersom du ikke klarer smuglerkravet i løpet av dette døgnet vil politimannen som får sin del av kaka iverksette en razzia mot ditt kartell neste døgn.";
                    send_notification($row['AS_id'], $text, $pdo);
                }
                if($smugling_inactive_days == 7){
                    $sql = "DELETE FROM smugling WHERE SMUG_acc_id = ".$row['AS_id']."";
                    $pdo -> exec($sql);
                    
                    $sql = "UPDATE smugling_daily SET SMDAI_days = 0 WHERE SMDAI_acc_id = ".$row['AS_id']."";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    
                    $text = "Det har vært razzia hos ditt kartell! Du har ikke vært aktiv nok og politiet har konfiskert ditt ustyr.";
                    send_notification($row['AS_id'], $text, $pdo);
                } else {
                    $sql = "UPDATE smugling_daily SET SMDAI_days = (SMDAI_days + 1) WHERE SMDAI_acc_id = ".$row['AS_id']."";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                }
            } else {
                $start = 1;
                $sql = "INSERT INTO smugling_daily (SMDAI_acc_id, SMDAI_days) VALUES (?,?)";
                $stmt= $pdo->prepare($sql);
                $stmt->execute([$row['AS_id'], $start]);
            }
        }
    }
}

$query = $pdo->prepare('SELECT * FROM territorium');
$query->execute();
foreach ($query as $row) {
    if($row['TE_money'] > 0){
        $family_leader = get_family_leader($row['TE_family_id'], $pdo);

        give_my_family_money($row['TE_family_id'], $row['TE_money'], $pdo);

        $text = "Du har fått ".number($row['TE_money'])." kr fra territoriumet i ".city_name($row['TE_city'])."";
        send_notification($family_leader, $text, $pdo);
    }
}

$sql = "UPDATE territorium SET TE_money = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$sql = "DELETE FROM dagens_utfordring_user";
$pdo -> exec($sql);
create_new_utfordring($pdo);

$sql = "UPDATE accounts_stat SET AS_daily_exp = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$sql = "UPDATE accounts_stat SET AS_weed = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute();
