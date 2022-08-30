<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../functions/functions.php';
include_once '../../functions/dates.php';

// Oppdater crypto priser
$query = $pdo->query('SELECT * FROM supported_crypto');
$query->execute();
$sql = "UPDATE supported_crypto SET SUC_date = ?, SUC_price = ? WHERE SUC_ticker = ? ";
foreach ($query as $row) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([time(), GetPrice($row['SUC_ticker'], "NOK"), $row['SUC_ticker']]);
}

$car_id = mt_rand(0, 18);
$thing_id = mt_rand(0, 28);

$amount1 = mt_rand(1, 10);
$amount2 = mt_rand(1, 10);

$sql = "INSERT INTO hurtig_oppdrag (HO_car_id, HO_car_amount, HO_thing_id, HO_thing_amount, HO_date) VALUES (?,?,?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$car_id, $amount1, $thing_id, $amount2, time()]);

// byskatt
$byskatt[0] = 0;
$byskatt[1] = 5;
$byskatt[2] = 10;
$byskatt[3] = 15;
$byskatt[4] = 20;
$byskatt[5] = 25;
$byskatt[6] = 30;
$byskatt[7] = 35;
$byskatt[8] = 40;
$byskatt[9] = 45;
$byskatt[10] = 50;
$byskatt[11] = 55;
$byskatt[12] = 60;
$byskatt[13] = 65;
$byskatt[14] = 70;
$byskatt[15] = 75;

for ($i = 0; $i < 6; $i++) {
    $city_tax_new = mt_rand(0, 15);

    $sql = "UPDATE city_tax SET CTAX_tax = " . $byskatt[$city_tax_new] . " WHERE CTAX_city = $i";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$crypto_active_hours_requirement = 12;
$query = $pdo->prepare(
    'SELECT crypto_rigs.* FROM crypto_rigs
    INNER JOIN accounts ON ACC_id = CRR_acc_id
    WHERE (' . time() . ' - ACC_last_active) < (' . $crypto_active_hours_requirement . ' * 3600)'
);
$query->execute();
foreach ($query as $row) {
    if (rigg_stabil($row['CRR_psu'], $row['CRR_motherboard'], $row['CRR_fan'], $row['CRR_gpu'])) {
        // Hent crypto ID fra riggen
        $stmt = $pdo->prepare("SELECT CRR_crypto, CRR_pig FROM crypto_rigs WHERE CRR_id = :id");
        $stmt->execute(['id' => $row['CRR_id']]);
        $row_cr = $stmt->fetch();

        // Crypto ID fra riggen
        $crypto = $row_cr['CRR_crypto'];

        // Hent hvor mye krypto per gpu fra crypto
        $stmt = $pdo->prepare("SELECT SUC_price, SUC_price_pr_gpu FROM supported_crypto WHERE SUC_id = :id");
        $stmt->execute(['id' => $crypto]);
        $row_support = $stmt->fetch();

        // Crypto per gpu
        $crypto_per_gpu = $row_support['SUC_price_pr_gpu'];
        // Regn ut hvor mye crypto man skal få per time
        // GPU * krypto per grpu
        $give_amount = ($row['CRR_gpu'] * $crypto_per_gpu);

        if ($row_cr['CRR_pig'] == 1) {
            $give_amount = $give_amount * 2;
        }

        $give_amount = floor($give_amount);

        if (AS_session_row($row['CRR_acc_id'], 'AS_mission', $pdo) == 31) {
            mission_update(AS_session_row($row['CRR_acc_id'], 'AS_mission_count', $pdo) + round($give_amount * $row_support['SUC_price']), AS_session_row($row['CRR_acc_id'], 'AS_mission', $pdo), mission_criteria(AS_session_row($row['CRR_acc_id'], 'AS_mission', $pdo)), $row['CRR_acc_id'], $pdo);
        }

        // Sjekk om brukeren har krypto med crypto id fra før
        $query = $pdo->prepare("SELECT CRU_crypto FROM crypto_user WHERE CRU_acc_id = ? AND CRU_crypto = ?");
        $query->execute(array($row['CRR_acc_id'], $crypto));
        $row_exist = $query->fetch(PDO::FETCH_ASSOC);

        if ($row_exist) {
            $sql = "UPDATE crypto_user SET CRU_amount = CRU_amount + ? WHERE CRU_crypto = ? AND CRU_acc_id = ? ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$give_amount, $crypto, $row['CRR_acc_id']]);
        } else {
            $sql = "INSERT INTO crypto_user (CRU_amount, CRU_crypto, CRU_acc_id) VALUES (?, ?, ?)";
            $pdo->prepare($sql)->execute([$give_amount, $crypto, $row['CRR_acc_id']]);
        }
    }
}

/** Slett familie og eiendeler fra innaktive spillere */
$time = time();
$active_days_requirement = 5;
$query = $pdo->prepare("SELECT ACC_id, ACC_username, (($time - CONVERT(ACC_last_active, INTEGER)) / 86400) AS days_inactive FROM accounts HAVING days_inactive >= $active_days_requirement;");
$query->execute();
foreach ($query as $inactive_account) {
    if (
        family_member_exist($inactive_account['ACC_id'], $pdo) &&
        get_my_familyrole($inactive_account['ACC_id'], $pdo) === 0
    ) { // Inaktiv spiller er familieleder
        $family_id = get_my_familyID($inactive_account['ACC_id'], $pdo);
        $family_name = get_familyname($family_id, $pdo);

        if ($family_name === "Ledelsen") { // Ikke legg ned ledelsen!
            continue;
        }

        //Send notifikasjon til leder
        send_notification($inactive_account['ACC_id'], "Familien din, $family_name ble lagt ned fordi du har vært innaktiv i $active_days_requirement døgn.", $pdo);

        //Send notifikasjon til medlemmer
        foreach (get_all_family_members($family_id, $pdo) as $member) {
            if ($member['FAMMEM_role'] === 0) continue; //Hopp over notifikasjon for leder

            send_notification($member['FAMMEM_acc_id'], "Familien du var medlem i, $family_name ble lagt ned fordi lederen deres, " . username_plain($inactive_account['ACC_id'], $pdo) . " har vært innaktiv i $active_days_requirement døgn.", $pdo);
        }

        //Slett medlemmer
        $sql = "DELETE FROM family_member WHERE FAMMEM_fam_id = $family_id";
        $pdo->exec($sql);

        //Slett hele familien
        $sql = "DELETE FROM family WHERE FAM_id = $family_id";
        $pdo->exec($sql);

        //Slett eventuelle teritorier eid av familie
        $sql = "DELETE FROM territorium WHERE TE_family_id = $family_id";
        $pdo->exec($sql);
    }

    $acc_id = $inactive_account['ACC_id'];
    // Fengsel eiendel
    $sql = "DELETE FROM fengsel_direktor WHERE FENGDI_acc_id = $acc_id";
    if ($pdo->exec($sql)) {
        send_notification($inactive_account['ACC_id'], "Du mistet fengselet ditt fordi du har vært innaktiv i $active_days_requirement døgn.", $pdo);
    }

    // Race Club
    $sql = "DELETE FROM rc_owner WHERE RCOWN_acc_id = $acc_id";
    if ($pdo->exec($sql)) {
        send_notification($inactive_account['ACC_id'], "Du mistet kjørebanen din fordi du har vært innaktiv i $active_days_requirement døgn.", $pdo);
    }

    // Flyplass
    $sql = "DELETE FROM flyplass WHERE FP_owner = $acc_id";
    if ($pdo->exec($sql)) {
        send_notification($inactive_account['ACC_id'], "Du mistet flyplassen din fordi du har vært innaktiv i $active_days_requirement døgn.", $pdo);
    }

    // Kulefabrikk
    $sql = "DELETE FROM kf_owner WHERE KFOW_acc_id = $acc_id";
    if ($pdo->exec($sql)) {
        send_notification($inactive_account['ACC_id'], "Du mistet kulefabrikken din fordi du har vært innaktiv i $active_days_requirement døgn.", $pdo);
    }

    // bj
    $sql = "DELETE FROM blackjack_owner WHERE BJO_owner = $acc_id";
    if ($pdo->exec($sql)) {
        send_notification($inactive_account['ACC_id'], "Du mistet blackjacken din fordi du har vært innaktiv i $active_days_requirement døgn.", $pdo);
    }

    // Bunker
    shut_down_bunker($inactive_account['ACC_id'], $pdo);
    send_notification($inactive_account['ACC_id'], "Du mistet bunkeren din fordi du har vært innaktiv i $active_days_requirement døgn.", $pdo);
}
