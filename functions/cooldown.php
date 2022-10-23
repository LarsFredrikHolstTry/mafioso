<?php

function airport_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_airport < ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function airport_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_airport = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function airport_reset_cd($acc_id, $pdo)
{
    $sql = "UPDATE cooldown SET CD_airport = 0 WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function crime_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_crime <= ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function crime_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_crime = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function safe_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_safe <= ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function safe_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_safe = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function gta_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_gta < ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function gta_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_gta = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function brekk_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_brekk < ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function brekk_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_brekk = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function steal_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_steal< ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function steal_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_steal = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function weapon_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_weapon< ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function weapon_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_weapon = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function rc_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_rc< ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function rc_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_rc = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function race_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_race< ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function race_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_race = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function kill_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_kill< ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function kill_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_kill = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function heist_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_heist< ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function heist_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_heist = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function event_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_event < ' . time() . '');
    $stmt->execute(array(
        ':cd_acc_id' => $acc_id
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function event_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_event = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}
