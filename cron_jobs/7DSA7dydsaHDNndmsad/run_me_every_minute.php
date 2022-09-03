<?php

ob_start();
include '../../env.php';
include_once '../../functions/functions.php';
include_once '../../functions/oppdrag.php';
include_once '../../functions/dates.php';

$sql = "DELETE FROM half_fp WHERE HALFFP_date <= " . time() . "";
$pdo->exec($sql);
$sql = "DELETE FROM bunker_active WHERE BUNACT_cooldown <= " . time() . "";
$pdo->exec($sql);

$query = $pdo->prepare('SELECT * FROM diamond_event WHERE DE_risk_level > 1');
$query->execute();

foreach ($query as $row) {

    if ($row['DE_risk_level'] <= 1) {
        $sql = "UPDATE diamond_event SET DE_risk_level = 0 WHERE DE_acc_id = ?";
    } else {
        $sql = "UPDATE diamond_event SET DE_risk_level = DE_risk_level - 2 WHERE DE_acc_id = ?";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$row['DE_acc_id']]);
}


$sql_DETSOK = "SELECT * FROM detektiv_sok WHERE DETSOK_date < " . time() . " AND DETSOK_active = 0";
$stmt_DETSOK = $pdo->query($sql_DETSOK);
while ($row_DETSOK = $stmt_DETSOK->fetch(PDO::FETCH_ASSOC)) {
    $id = $row_DETSOK['DETSOK_id'];
    $acc_id = $row_DETSOK['DETSOK_acc_id'];
    $user = $row_DETSOK['DETSOK_user'];
    $city = $row_DETSOK['DETSOK_city'];

    if (strpos($city, 'def:') !== false) {
        $new_string = explode(':', $city);
        $text = "Detektiven her! Jeg har funnet litt informasjon om " . username_plain($user, $pdo) . " og det viser seg at mafiosoen har " . number($new_string[1]) . " i forsvar. Lykke til!";
    } elseif (strpos($city, 'bunker:') !== false) {
        $new_string = explode(':', $city);
        if ($new_string[1] == 'true') {
            $text = "Detektiven her! Jeg har funnet litt informasjon om " . username_plain($user, $pdo) . " og det viser seg at mafiosoen er i bunker.";
        } else {
            $text = "Detektiven her! Jeg har funnet litt informasjon om " . username_plain($user, $pdo) . " og det viser seg at mafiosoen ikke er i bunker.";
        }
    } else {
        $text = "Detektiven her! Jeg har funnet litt informasjon om " . username_plain($user, $pdo) . " og det viser seg at jeg fant mafiosoen i " . city_name($city);
    }

    send_notification($acc_id, $text, $pdo);

    $sql = "UPDATE detektiv_sok SET DETSOK_active = 1 WHERE DETSOK_id = $id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$total_hk_ = (race_club_drift($_SESSION['ID'], $pdo) + race_club_drag($_SESSION['ID'], $pdo) + race_club_race($_SESSION['ID'], $pdo));
$total_hk = $total_hk_ / 3;
if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 7) {
    mission_update($total_hk, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
}

$sql_DETSOK = "SELECT * FROM marked WHERE MARKED_date < " . time() . "";
$stmt_DETSOK = $pdo->query($sql_DETSOK);
while ($row = $stmt_DETSOK->fetch(PDO::FETCH_ASSOC)) {

    $marked_id = $row['MARKED_id'];
    $marked_seller = $row['MARKED_seller'];
    $marked_cat = $row['MARKED_cat'];
    $marked_private = $row['MARKED_private'];
    $marked_info = $row['MARKED_info'];
    $marked_price = $row['MARKED_price'];
    $marked_date = $row['MARKED_date'];

    if ($marked_cat == 0) {
        $jsonobj = $marked_info;

        $obj = json_decode($jsonobj);

        $car_id = $obj->car_id;
        $city = $obj->car_city;

        update_garage($marked_seller, $car_id, $city, $pdo);
    } elseif ($marked_cat == 1) {

        $jsonobj = $marked_info;

        $obj = json_decode($jsonobj);

        $thing_type = $obj->thing_type;
        $amount = $obj->amount;

        for ($i = 0; $i < $amount; $i++) {
            update_things($marked_seller, $thing_type, $pdo);
        }
    } elseif ($marked_cat == 2) {
        give_poeng($marked_seller, $marked_info, $pdo);
    } elseif ($marked_cat == 3) {
        give_bullets($marked_seller, $marked_info, $pdo);
    } elseif ($marked_cat == 4) {
        $sql = "INSERT INTO charm (CH_acc_id, CH_charm) VALUES (?,?)";
        $pdo->prepare($sql)->execute([$marked_seller, $marked_info]);
    } elseif ($marked_cat == 5) {
        $jsonobj = $marked_info;

        $obj = json_decode($jsonobj);

        $firma_type = $obj->type;
        $city = $obj->city;

        if ($firma_type == 0) {
            $sql = "INSERT INTO flyplass (FP_city, FP_owner) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$city, $marked_seller]);
        }
        if ($firma_type == 1) {
            $sql = "INSERT INTO kf_owner (KFOW_city, KFOW_acc_id) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$city, $marked_seller]);
        }
        if ($firma_type == 2) {
            $sql = "INSERT INTO fengsel_direktor (FENGDI_city, FENGDI_acc_id) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$city, $marked_seller]);
        }
        if ($firma_type == 3) {
            $sql = "INSERT INTO rc_owner (RCOWN_city, RCOWN_acc_id) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$city, $marked_seller]);
        }
        if ($firma_type == 4) {
            $sql = "INSERT INTO blackjack_owner (BJO_city, BJO_owner) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$city, $marked_seller]);
        }
        if ($firma_type == 5) {
            $sql = "INSERT INTO bunker_owner (BUNOWN_city, BUNOWN_acc_id) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$city, $marked_seller]);
        }
    } elseif ($marked_cat == 6) {
        give_weed($marked_seller, $marked_info, $pdo);
    }

    $sql = "DELETE FROM marked WHERE MARKED_id = " . $marked_id . "";
    $pdo->exec($sql);

    $text = "Salget ditt på marked har stått for lenge og derfor fjernet.";
    send_notification($marked_seller, $text, $pdo);
}

$query = $pdo->prepare("SELECT * FROM konk WHERE KONK_to <= ? AND KONK_status = ?");
$query->execute(array(time(), 0));
$konk_row = $query->fetch(PDO::FETCH_ASSOC);

if ($konk_row) {
    $winner_array = array();

    $position = 0;
    $sql = "SELECT KU_acc_id, KU_count FROM konk_user ORDER BY KU_count DESC";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        if ($position >= 4) {
        } else {

            $arr = json_decode($konk_row['KONK_prize'], true);

            if ($position === 0 && AS_session_row($row['KU_acc_id'], 'AS_mission', $pdo) == 48) {
                mission_update(AS_session_row($row['KU_acc_id'], 'AS_mission_count', $pdo) + 1, AS_session_row($row['KU_acc_id'], 'AS_mission', $pdo), mission_criteria(AS_session_row($row['KU_acc_id'], 'AS_mission', $pdo)), $row['KU_acc_id'], $pdo);
            }

            $money = $arr[$position];
            $text = "Du kom på " . ($position + 1) . ". plass på " . $konk_row['KONK_title'] . " konkurranse og får " . number($money) . " kr";
            send_notification($row['KU_acc_id'], $text, $pdo);
            give_money($row['KU_acc_id'], $money, $pdo);
            array_push($winner_array, $row['KU_acc_id']);
        }

        $position++;
    }

    $sql = "DELETE FROM konk_user";
    $pdo->exec($sql);
    // Endre status på konk

    $sql = "UPDATE konk SET KONK_status = ?, KONK_winners = ? WHERE KONK_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([1, json_encode($winner_array), $konk_row['KONK_id']]);
}
