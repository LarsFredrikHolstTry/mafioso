<?php

function admin_total_money_out($pdo){

    

    $query = $pdo -> prepare('SELECT
        SUM(accounts_stat.AS_money) as sum
    FROM 
        accounts_stat
    INNER JOIN
        accounts
    ON
        accounts_stat.AS_id = accounts.ACC_id
    WHERE
        accounts.ACC_type = 0');
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['sum'] : 0;
}

function admin_total_money_bank($pdo){
    $query = $pdo -> prepare('SELECT
        SUM(accounts_stat.AS_bankmoney) as sum
    FROM 
        accounts_stat
    INNER JOIN
        accounts
    ON
        accounts_stat.AS_id = accounts.ACC_id
    WHERE
        accounts.ACC_type = 0');
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['sum'] : 0;
}

function poeng_total($pdo){
    $query = $pdo -> prepare('SELECT
        SUM(accounts_stat.AS_points) as sum
    FROM 
        accounts_stat
    INNER JOIN
        accounts
    ON
        accounts_stat.AS_id = accounts.ACC_id
    WHERE
        accounts.ACC_type = 0');
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['sum'] : 0;
}

function family_money($pdo){
    $query = $pdo -> prepare("SELECT SUM(FAM_bank) as sum FROM family");
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['sum'] : 0;
}

function kf_money($pdo){
    $query = $pdo -> prepare("SELECT SUM(KFOW_money) as sum FROM kf_owner");
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['sum'] : 0;
}

function fengsel_money($pdo){
    $query = $pdo -> prepare("SELECT SUM(FENGDI_bank) as sum FROM fengsel_direktor");
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['sum'] : 0;
}

function flyplass_money($pdo){
    $query = $pdo -> prepare("SELECT SUM(FP_money) as sum FROM flyplass");
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['sum'] : 0;
}

function rc_money($pdo){
    $query = $pdo -> prepare("SELECT SUM(RCOWN_bank) as sum FROM rc_owner");
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['sum'] : 0;
}

function swiss_money($pdo){
    $query = $pdo -> prepare("SELECT SUM(SWISS_saldo) as sum FROM swiss");
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['sum'] : 0;
}

function bunker_money($pdo){
    $query = $pdo -> prepare("SELECT SUM(BUNOWN_bank) as sum FROM bunker_owner");
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);

    return $row ? $row['sum'] : 0;
}

function bj_money($pdo){
    $query = $pdo -> prepare("SELECT SUM(BJO_bank) as sum FROM blackjack_owner");
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);

    return $row ? $row['sum'] : 0;
}

function crypto_money($pdo){
    $crypto_prices = array();

    $stmt = $pdo->prepare('SELECT SUC_price FROM supported_crypto ORDER BY SUC_id');
    $stmt->execute(array());
    foreach ($stmt as $row) {
        array_push($crypto_prices, $row['SUC_price']);
    }

    $total_in_crypto = 0;

    $stmt = $pdo->prepare('
    SELECT 
        crypto_user.CRU_amount, crypto_user.CRU_acc_id, crypto_user.CRU_crypto
    FROM 
        crypto_user
    INNER JOIN
        accounts
    ON
        crypto_user.CRU_acc_id = accounts.ACC_id
    WHERE NOT
        accounts.ACC_type IN (4, 5)
    ORDER BY 
        CRU_acc_id DESC');
    $stmt->execute(array());
    foreach ($stmt as $row) {
        $total_in_crypto = $total_in_crypto + ($crypto_prices[$row['CRU_crypto'] - 1] * $row['CRU_amount']);
    }

    return $total_in_crypto;
}

function total_support_unread($pdo){
    return $pdo->query("SELECT count(*) FROM support WHERE SP_status = 0")->fetchColumn();
}

function changelog($acc_id, $column, $value_before, $value_after, $changed_by, $pdo){
    $date = time();
    $sql = "INSERT INTO changelog (CL_acc_id, CL_edit_column, CL_value_before, CL_value_after, CL_changed_by, CL_date) VALUES (?,?,?,?,?,?)";
    $pdo -> prepare($sql)->execute([$acc_id, $column, $value_before, $value_after, $changed_by, $date]);
}

function total_users($pdo){
    $query = $pdo -> prepare("SELECT COUNT(*) as users FROM accounts");
    $query -> execute();
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row['users'] ?? NULL;
}

function total_users_dead($pdo){
    $query = $pdo -> prepare("SELECT COUNT(*) as users FROM accounts WHERE ACC_type = ?");
    $query -> execute(array(4));
    $row = $query -> fetch(PDO::FETCH_ASSOC);
    
    return $row['users'] ?? NULL;
}

?>