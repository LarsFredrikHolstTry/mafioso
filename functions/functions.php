<?php

function number($amount)
{
    if ($amount == null || $amount == '') {
        return 0;
    } else {
        return number_format($amount, 0, '.', ' ');
    }
}

/**
 * Forenkler store tall til f.eks. 10.8k, 50.2m, 10mrd osv. 
 * @param int|float $num Tallet som skal forkortes
 * @param int $decimals Maksimalt antall desimaler (Default er 0)
 * @return string  Forkortet tall-streng / fallback til number()
 */
function big_number_format(float $num, int $decimals = 0)
{
    if ($num < 1e3) return number($num); //Fallback til number() dersom $num er under tusen

    /** Sørg for at tallene er rangert fra høy til lav for
     * å utnytte dette ved utregning. Kommenter ut det som ikke trengs.
     * @see https://snl.no/tabell_over_store_tall
     */
    $forkortelser = array(
        1e15 => array('full' => 'Billiard', 'kort' => 'brd', 'flertall' => 'er'),
        1e12 => array('full' => 'Billion', 'kort' => 'bill', 'flertall' => 'er'),
        1e9 => array('full' => 'Milliard', 'kort' => 'mrd', 'flertall' => 'er'),
        1e6 => array('full' => 'Million', 'kort' => 'm', 'flertall' => 'er'),
        //   1e3 => array('full' => 'Tusen', 'kort' => 'k', 'flertall => ''), //(Disse tallene er kanskje korte nok i seg selv maks 6 siffer)
    );

    // Løper gjennom $shrts fra høy til lav frem til den finner det som passer
    foreach ($forkortelser as $stortTall => $tallord) {
        if ($num >= $stortTall) {
            $displayNumber = $num / (float) $stortTall;

            //Caster til float igjen kun for å ikke ta med desimaler dersom de uansett er x.00
            $formatert = (float) number_format($displayNumber, $decimals, '.', " ");
            return $formatert . " <span class='number-abbreviation' title='" . $tallord["full"] . ($formatert >= 2 ? $tallord["flertall"] : '') . "'>" . $tallord["kort"] . "</span>";
        }
    }

    //Her havner man kun dersom ingen forkortelser påtreffes i foreach loopen
    ///fallback til number-funksjonen
    return number($num);
}

if (isset($_SESSION['ID'])) {
    if (family_member_exist($_SESSION['ID'], $pdo)) {
        $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);
        $addon = get_forsvar_from_family(get_family_defence($my_family_id, $pdo), get_my_familyrole($_SESSION['ID'], $pdo), total_family_members($my_family_id, $pdo));
    } else {
        $addon = 0;
    }
}

$missions_numberz = array(2, 3, 4, 7, 11, 12, 16, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53);
$missions_with_generic_values = array(1, 5, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 21, 23, 25, 26, 27, 28, 29, 32, 33, 34, 35, 36, 39, 51, 52, 53);

function get_mission_value(int $acc_id, int $mission, $pdo)
{
    $value = 0;

    if ($mission == 1) {
        $value = AS_session_row($acc_id, 'AS_protection', $pdo);
    } elseif ($mission == 5) {
        if (get_detektiv_lvl($acc_id, $pdo) > 0) {
            $value = 1;
        } else {
            $value = 0;
        }
    } elseif ($mission == 7) {
        $value = ((race_club_drift($acc_id, $pdo) + race_club_drag($acc_id, $pdo) + race_club_race($acc_id, $pdo)) / 3);
    } elseif ($mission == 8) {
        $value = amount_of_car($acc_id, 0, 4, $pdo);
    } elseif ($mission == 9) {
        if (family_member_exist($acc_id, $pdo)) {
            $value = 1;
        }
    } elseif ($mission == 10) {
        $value = amount_of_things($acc_id, 1, $pdo);
    } elseif ($mission == 11) {
        $value = AS_session_row($acc_id, 'AS_money', $pdo);
    } elseif ($mission == 13) {
        $value = amount_of_car($acc_id, 1, 16, $pdo);
    } elseif ($mission == 14) {
        $value = amount_of_things($acc_id, 29, $pdo);
    } elseif ($mission == 15) {
        $value = AS_session_row($acc_id, 'AS_def', $pdo);
    } elseif ($mission == 16) {
        $value = get_daily_weed($acc_id, $pdo);
    } elseif ($mission == 17) {
        $value = amount_of_things($acc_id, 30, $pdo);
    } elseif ($mission == 18) {
        $value = amount_of_things($acc_id, 25, $pdo);
    } elseif ($mission == 21) {
        $value = AS_session_row($acc_id, 'AS_def', $pdo);
    } elseif ($mission == 23) {
        $value = amount_of_things($acc_id, 10, $pdo);
    } elseif ($mission == 25) {
        $value = amount_of_car($acc_id, 1, 18, $pdo);
    } elseif ($mission == 26) {
        $value = amount_of_smugling_things($acc_id, 2, $pdo);
    } elseif ($mission == 27) {
        $value = amount_of_car($acc_id, 3, 14, $pdo);
    } elseif ($mission == 28) {
        $value = amount_of_things($acc_id, 13, $pdo);
    } elseif ($mission == 29) {
        $value = AS_session_row($acc_id, 'AS_money', $pdo);
    } elseif ($mission == 32) {
        $value = amount_of_things($acc_id, 29, $pdo);
    } elseif ($mission == 33) {
        $value = amount_of_things($acc_id, 20, $pdo);
    } elseif ($mission == 34) {
        $value = amount_of_things($acc_id, 26, $pdo);
    } elseif ($mission == 35) {
        $value = amount_of_things($acc_id, 28, $pdo);
    } elseif ($mission == 36) {
        $value = amount_of_things($acc_id, 22, $pdo);
    } elseif ($mission == 39) {
        $value = amount_of_car($acc_id, 0, 10, $pdo);
    } elseif ($mission == 51) {
        $value = amount_of_car($acc_id, 2, 19, $pdo);
    } elseif ($mission == 52) {
        $value = amount_of_car($acc_id, 0, 20, $pdo) + amount_of_car($acc_id, 1, 20, $pdo) + amount_of_car($acc_id, 2, 20, $pdo) + amount_of_car($acc_id, 3, 20, $pdo) + amount_of_car($acc_id, 4, 20, $pdo);
    } elseif ($mission == 53) {
        $value = amount_of_car($acc_id, 0, 21, $pdo) + amount_of_car($acc_id, 1, 21, $pdo) + amount_of_car($acc_id, 2, 21, $pdo) + amount_of_car($acc_id, 3, 21, $pdo) + amount_of_car($acc_id, 4, 21, $pdo);
    }

    return $value ?? 0;
}

function get_acc_id($username, $pdo)
{
    $query = $pdo->prepare("SELECT ACC_id FROM accounts WHERE ACC_username=?");
    $query->execute(array($username));
    $ACC_row = $query->fetch(PDO::FETCH_ASSOC);

    return $ACC_row['ACC_id'] ?? NULL;
}

function role_colors($role_nr)
{
    $role[0] = "#FFFFFF";
    $role[1] = "#ffa500";
    $role[2] = "#3F9FDF";
    $role[3] = "#E91E63";
    $role[4] = "#9c0000";
    $role[5] = "#607D8B";
    $role[6] = "#921FF0";
    $role[7] = "#7D572C";

    return $role[$role_nr];
}

function roles($role_nr)
{
    $role[0] = "Bruker";
    $role[1] = "Forum moderator";
    $role[2] = "Moderator";
    $role[3] = "Administrator";
    $role[4] = "Død";
    $role[5] = "Utestengt";
    $role[6] = "Assisterende administrator";
    $role[7] = "NPC";

    return $role[$role_nr];
}

function AS_session_row($id, $what, $pdo)
{
    $query = $pdo->prepare("SELECT $what as AS_row FROM accounts_stat WHERE AS_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['AS_row'] ?? NULL;
}

function DE_session_row($id, $what, $pdo)
{
    $query = $pdo->prepare("SELECT $what as DE_row FROM diamond_event WHERE DE_acc_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['DE_row'] ?? NULL;
}

function ACC_session_row($id, $what, $pdo)
{
    $query = $pdo->prepare("SELECT $what as ACC_row FROM accounts WHERE ACC_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['ACC_row'] ?? NULL;
}

function US_session_row($id, $what, $pdo)
{
    $query = $pdo->prepare("SELECT $what as US_row FROM user_statistics WHERE US_acc_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['US_row'] ?? NULL;
}

function US_max_cars($id, $pdo)
{
    $query = $pdo->prepare("SELECT US_max_cars FROM user_statistics WHERE US_acc_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['US_max_cars'] ?? NULL;
}

function US_max_things($id, $pdo)
{
    $query = $pdo->prepare("SELECT US_max_things FROM user_statistics WHERE US_acc_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['US_max_things'] ?? NULL;
}

function ACC_status($id, $pdo)
{
    $query = $pdo->prepare("SELECT ACC_status FROM accounts WHERE ACC_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['ACC_status'] ?? NULL;
}

function status_last_active($id, $pdo)
{
    $query = $pdo->prepare("SELECT ACC_last_active FROM accounts WHERE ACC_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    $last_active = $row['ACC_last_active'];

    if ($row) {
        if (($last_active + 1800) > time()) {
            return ' <span style="color: green;">•</span>';
        } elseif (($row['ACC_last_active'] + 3600) <= time() && ($row['ACC_last_active'] + 1800) > time()) {
            return ' <span style="color: orange;">•</span>';
        } else {
            return ' <span style="color: red;">•</span>';
        }
    }
}

function ACC_username($id, $pdo)
{
    $query = $pdo->prepare("SELECT ACC_id, ACC_username, ACC_type, ACC_last_active FROM accounts WHERE ACC_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if ($row['ACC_type'] == 3) {
            $font_weight = 'bolder';
        } else {
            $font_weight = 'normal';
        }

        if (AS_session_row($id, 'AS_rank', $pdo) == 15) {
            $rank_color = 'color: #DD6E0F !important;';
        } else {
            $rank_color = '';
        }

        return '
        <div class="tooltip_user"><a style="font-weight: ' . $font_weight . '; color: ' . role_colors($row['ACC_type']) . '" href="?side=profil&id=' . $row['ACC_id'] . '">' . $row['ACC_username'] . '</a>
        <div class="tooltiptext_user" style="text-align: left;">
                Brukernavn: <a style="color: ' . role_colors($row['ACC_type']) . '" href="?side=profil&id=' . $row['ACC_id'] . '">' . $row['ACC_username'] . '</a>' . status_last_active($id, $pdo) . '<br>
                Rank: <span style="' . $rank_color . '"> ' . rank_list(AS_session_row($id, 'AS_rank', $pdo)) . '</span><br>
                Pengestatus: ' . money_rank(AS_session_row($id, 'AS_money', $pdo) + AS_session_row($id, 'AS_bankmoney', $pdo)) . '<br>
                Sist aktiv: ' . date_to_text($row['ACC_last_active']) . '
        </div>
    </div>';
    }
}

function username_plain($id, $pdo)
{
    $query = $pdo->prepare("SELECT ACC_id, ACC_username, ACC_type FROM accounts WHERE ACC_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['ACC_username'] ?? NULL;
}

function remove_space($value)
{
    return str_replace(' ', '', $value);
}

function number_valid($number)
{
    $number = remove_space($number);
    if (is_numeric($number) && $number > 0) {
        return true;
    } else {
        return false;
    }
}

function give_poeng($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE accounts_stat SET AS_points = AS_points + ? WHERE AS_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $id]);
}

function take_poeng($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE accounts_stat SET AS_points = AS_points - ? WHERE AS_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $id]);
}

function active_superhelg($pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM super_helg WHERE SHELG_start <= ? AND SHELG_end >= ?");
    $stmt->execute([time(), time()]);
    $row = $stmt->fetchColumn();

    return $row == 0 ? false : true;
}

function active_superhelg_title($pdo)
{
    $stmt = $pdo->prepare("SELECT SHELG_title FROM super_helg WHERE SHELG_start <= ? AND SHELG_end >= ?");
    $stmt->execute([time(), time()]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['SHELG_title'] ?? '';
}

function active_drapsfri($pdo)
{
    $date = time();

    $stmt = $pdo->prepare("SELECT count(*) FROM drapsfri WHERE DRAPFRI_start <= ? AND DRAPFRI_end >= ?");
    $stmt->execute([$date, $date]);
    $row = $stmt->fetchColumn();

    return $row == 0 ? false : true;
}

function drapsfri_info($info, $pdo)
{
    if (active_drapsfri($pdo)) {

        if ($info && $info == 'DRAPFRI_start') {
            $select = 'DRAPFRI_start';
        } else {
            $select = 'DRAPFRI_end';
        }

        $stmt = $pdo->prepare("SELECT " . $select . " FROM drapsfri WHERE DRAPFRI_start <= ? AND DRAPFRI_end >= ?");
        $stmt->execute([time(), time()]);
        $row = $stmt->fetchColumn();

        return $row;
    }
}

function give_exp($id, $amount, $pdo)
{
    $amount = floor($amount);

    if (active_energy_drink($id, $pdo)) {
        $amount = $amount * 2;
    }

    if (active_superhelg($pdo)) {
        $amount = $amount * 2;
    }

    $sql = "UPDATE accounts_stat SET AS_exp = AS_exp + ?, AS_daily_exp = AS_daily_exp + ? WHERE AS_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $amount, $id]);
}

function take_exp($id, $amount, $pdo)
{
    $amount = floor($amount);

    $sql = "UPDATE accounts_stat SET AS_exp = AS_exp - ? WHERE AS_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $id]);
}

function give_money($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE accounts_stat SET AS_money = AS_money + ? WHERE AS_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $id]);
}

function take_money($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE accounts_stat SET AS_money = AS_money - ? WHERE AS_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $id]);
}

function give_bank_money($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE accounts_stat SET AS_bankmoney = AS_bankmoney + ? WHERE AS_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $id]);
}

function take_bank_money($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE accounts_stat SET AS_bankmoney = AS_bankmoney - ? WHERE AS_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $id]);
}

function take_bullets($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE accounts_stat SET AS_bullets = (AS_bullets - $amount) WHERE AS_id='" . $id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function give_bullets($id, $amount, $pdo)
{
    $sql = "UPDATE accounts_stat SET AS_bullets = (AS_bullets + $amount) WHERE AS_id='" . $id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function give_fp($amount, $acc_id, $pdo)
{
    $sql = "UPDATE accounts_stat SET AS_def = (AS_def + $amount) WHERE AS_id = $acc_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function take_fp($amount, $acc_id, $pdo)
{
    $sql = "UPDATE accounts_stat SET AS_def = (AS_def - $amount) WHERE AS_id = $acc_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function user_exist($username, $pdo)
{
    $sthandler = $pdo->prepare("SELECT ACC_username FROM accounts WHERE ACC_username = :name");
    $sthandler->bindParam(':name', $username);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function isRussian($text)
{
    return preg_match('/[А-Яа-яЁё]/u', $text);
}

function getUserIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function verificate_me($code, $pdo)
{
    $query = $pdo->prepare("SELECT VER_acc_id FROM verification WHERE VER_hash = ?");
    $query->execute(array($code));
    $row_ver = $query->fetch(PDO::FETCH_ASSOC);

    if ($row_ver) {
        $id = $row_ver['VER_acc_id'];

        $sql = "UPDATE accounts SET ACC_status = 1 WHERE ACC_id=" . $id . "";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        echo feedback("Brukeren er nå aktivert! Ha en hyggelig spill-opplevelse.", "success");
    }
}

function players_online($pdo)
{
    $differanse = time() - 1800;
    $online = 0;

    $sql = "SELECT * FROM accounts WHERE ACC_last_active > '$differanse' AND ACC_type NOT IN (4, 5)";
    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $online++;
    }

    return $online;
}

function send_notification($to, $text, $pdo)
{
    $date = time();
    $sql = "INSERT INTO notification (NO_acc_id, NO_text, NO_date) VALUES (?,?,?)";
    $pdo->prepare($sql)->execute([$to, $text, $date]);
}

function total_notifications($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM notification WHERE NO_acc_id = :id");
    $stmt->execute(['id' => $id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function new_notifications($id, $pdo)
{
    $query = $pdo->prepare("SELECT count(NO_new) as count FROM notification WHERE NO_acc_id = ? AND NO_new = ?");
    $query->execute(array($id, 0));
    $row_ver = $query->fetch(PDO::FETCH_ASSOC);

    return $row_ver['count'] ?? NULL;
}

function get_random_lotto($acc_id, $pdo)
{
    $query = $pdo->prepare("SELECT LOTTO_acc_id FROM lotto WHERE NOT LOTTO_acc_id = ? ORDER BY RAND() LIMIT 1");
    $query->execute(array($acc_id));
    $rand_row = $query->fetch(PDO::FETCH_ASSOC);
    return $rand_row['LOTTO_acc_id'] ?? NULL;
}

function money_rank($amount)
{
    $money_amount_from[0] =     0;
    $money_amount_to[0] =       1000;
    $money_amount_from[1] =     $money_amount_to[0];
    $money_amount_to[1] =       10000;
    $money_amount_from[2] =     $money_amount_to[1];
    $money_amount_to[2] =       100000;
    $money_amount_from[3] =     $money_amount_to[2];
    $money_amount_to[3] =       500000;
    $money_amount_from[4] =     $money_amount_to[3];
    $money_amount_to[4] =       10000000;
    $money_amount_from[5] =     $money_amount_to[4];
    $money_amount_to[5] =       25000000;
    $money_amount_from[6] =     $money_amount_to[5];
    $money_amount_to[6] =       50000000;
    $money_amount_from[7] =     $money_amount_to[6];
    $money_amount_to[7] =       100000000;
    $money_amount_from[8] =     $money_amount_to[7];
    $money_amount_to[8] =       200000000;
    $money_amount_from[9] =     $money_amount_to[8];
    $money_amount_to[9] =       500000000;
    $money_amount_from[10] =    $money_amount_to[9];
    $money_amount_to[10] =      1000000000;
    $money_amount_from[11] =    $money_amount_to[10];
    $money_amount_to[11] =      5000000000;
    $money_amount_from[12] =    $money_amount_to[11];
    $money_amount_to[12] =      10000000000;
    $money_amount_from[13] =    $money_amount_to[12];
    $money_amount_to[13] =      25000000000;
    $money_amount_from[14] =    $money_amount_to[13];
    $money_amount_to[14] =      50000000000;
    $money_amount_from[15] =    $money_amount_to[14];
    $money_amount_to[15] =      100000000000;
    $money_amount_from[16] =    $money_amount_to[15];
    $money_amount_to[16] =      INF;

    $money_rank[0] = "Taxisjåfør";
    $money_rank[1] = "Pusher";
    $money_rank[2] = "Arbeider";
    $money_rank[3] = "Langer";
    $money_rank[4] = "Middel klasse";
    $money_rank[5] = "Øvrige klasse";
    $money_rank[6] = "Rik";
    $money_rank[7] = "Middels rik";
    $money_rank[8] = "Millionær";
    $money_rank[9] = "Multimillionær";
    $money_rank[10] = "Mangemillionær";
    $money_rank[11] = "Skipsmegler";
    $money_rank[12] = "Aksjemegler";
    $money_rank[13] = "Børssjef";
    $money_rank[14] = "Hotell-investor";
    $money_rank[15] = "Oljesjeik";
    $money_rank[16] = "Forretningsmagnat";

    $i = 0;

    while ($i < count($money_rank)) {
        if ($amount >= $money_amount_from[$i] && $amount < $money_amount_to[$i]) {
            return $money_rank[$i];
        }
        $i++;
    }
}

function country_flags($city)
{
    $country_flag[0] = '<img title="Norge" class="flag_icon" src="img/flags/no.svg">';
    $country_flag[1] = '<img title="USA" class="flag_icon" src="img/flags/us.svg">';
    $country_flag[2] = '<img title="England" class="flag_icon" src="img/flags/en.svg">';
    $country_flag[3] = '<img title="Tyskland" class="flag_icon" src="img/flags/de.svg">';
    $country_flag[4] = '<img title="Kina" class="flag_icon" src="img/flags/cn.svg">';

    return $country_flag[$city];
}

function city_name($city)
{
    $city_name[0] = "Oslo";
    $city_name[1] = "Las Vegas";
    $city_name[2] = "London";
    $city_name[3] = "Berlin";
    $city_name[4] = "Beijing";
    $city_name[5] = "Abu Dhabi";

    return $city_name[$city];
}

function get_rank_from_exp($exp)
{
    if ($exp >= 0 && $exp < 20) {
        $rank = 0;
    } elseif ($exp >= 20 && $exp < 100) {
        $rank = 1;
    } elseif ($exp >= 100 && $exp < 250) {
        $rank = 2;
    } elseif ($exp >= 250 && $exp < 750) {
        $rank = 3;
    } elseif ($exp >= 750 && $exp < 1500) {
        $rank = 4;
    } elseif ($exp >= 1500 && $exp < 3000) {
        $rank = 5;
    } elseif ($exp >= 3000 && $exp < 5000) {
        $rank = 6;
    } elseif ($exp >= 5000 && $exp < 10000) {
        $rank = 7;
    } elseif ($exp >= 10000 && $exp < 16000) {
        $rank = 8;
    } elseif ($exp >= 16000 && $exp < 25000) {
        $rank = 9;
    } elseif ($exp >= 25000 && $exp < 50000) {
        $rank = 10;
    } elseif ($exp >= 50000 && $exp < 100000) {
        $rank = 11;
    } elseif ($exp >= 100000 && $exp < 250000) {
        $rank = 12;
    } elseif ($exp >= 250000 && $exp < 500000) {
        $rank = 13;
    } elseif ($exp >= 500000 && $exp < 1000000) {
        $rank = 14;
    } elseif ($exp >= 1000000) {
        $rank = 15;
    }

    return $rank;
}

function exp_from($rank)
{
    $rank_exp_from[0] =    0;
    $rank_exp_from[1] =    20;
    $rank_exp_from[2] =    100;
    $rank_exp_from[3] =    250;
    $rank_exp_from[4] =    750;
    $rank_exp_from[5] =    1500;
    $rank_exp_from[6] =    3000;
    $rank_exp_from[7] =    5000;
    $rank_exp_from[8] =    10000;
    $rank_exp_from[9] =    16000;
    $rank_exp_from[10] = 25000;
    $rank_exp_from[11] = 50000;
    $rank_exp_from[12] = 100000;
    $rank_exp_from[13] = 250000;
    $rank_exp_from[14] = 500000;
    $rank_exp_from[15] = 1000000;

    return $rank_exp_from[$rank];
}

function exp_to($rank)
{
    $rank_exp_to[0] =    20;
    $rank_exp_to[1] =    100;
    $rank_exp_to[2] =    250;
    $rank_exp_to[3] =    750;
    $rank_exp_to[4] =    1500;
    $rank_exp_to[5] =    3000;
    $rank_exp_to[6] =    5000;
    $rank_exp_to[7] =    10000;
    $rank_exp_to[8] =    16000;
    $rank_exp_to[9] =    25000;
    $rank_exp_to[10] =     50000;
    $rank_exp_to[11] =     100000;
    $rank_exp_to[12] =     250000;
    $rank_exp_to[13] =     500000;
    $rank_exp_to[14] =     1000000;
    $rank_exp_to[15] =     INF;

    return $rank_exp_to[$rank];
}

function rank_list($rank)
{
    $rank_name[0] = "Sivilist";
    $rank_name[1] = "Pøbel";
    $rank_name[2] = "Soldat";
    $rank_name[3] = "Konsulent";
    $rank_name[4] = "Viserådmann";
    $rank_name[5] = "Rådmann";
    $rank_name[6] = "Viseguvernør";
    $rank_name[7] = "Guvernør";
    $rank_name[8] = "Visesenator";
    $rank_name[9] = "Senator";
    $rank_name[10] = "Visekonge";
    $rank_name[11] = "Konge";
    $rank_name[12] = "Visepresident";
    $rank_name[13] = "President";
    $rank_name[14] = "Legende";
    $rank_name[15] = "Mafioso";

    return $rank_name[$rank];
}

/**
 * Få tak i hvilken rank i rekken en rank er
 * Brukes for å ikke eksponere faktiske EXP folk har i sortering
 * @param string $rank_name Ranknavn
 * @return int Rankindeks
 */
function rank_index($rank_name)
{
    $rank_index["Sivilist"] = 0;
    $rank_index["Pøbel"] = 1;
    $rank_index["Soldat"] = 2;
    $rank_index["Konsulent"] = 3;
    $rank_index["Viserådmann"] = 4;
    $rank_index["Rådmann"] = 5;
    $rank_index["Viseguvernør"] = 6;
    $rank_index["Guvernør"] = 7;
    $rank_index["Visesenator"] = 8;
    $rank_index["Senator"] = 9;
    $rank_index["Visekonge"] = 10;
    $rank_index["Konge"] = 11;
    $rank_index["Visepresident"] = 12;
    $rank_index["President"] = 13;
    $rank_index["Legende"] = 14;
    $rank_index["Mafioso"] = 15;

    return $rank_index[$rank_name];
}

function rank_prize($rank)
{
    $rank_prize[0] =  500000;
    $rank_prize[1] =  1250000;
    $rank_prize[2] =  2500000;
    $rank_prize[3] =  5000000;
    $rank_prize[4] =  7500000;
    $rank_prize[5] =  10000000;
    $rank_prize[6] =  12500000;
    $rank_prize[7] =  15000000;
    $rank_prize[8] =  17500000;
    $rank_prize[9] =  20000000;
    $rank_prize[10] = 35000000;
    $rank_prize[11] = 50000000;
    $rank_prize[12] = 75000000;
    $rank_prize[13] = 100000000;
    $rank_prize[14] = 250000000;
    $rank_prize[15] = 500000000;

    return $rank_prize[$rank];
}

function rank_progress($rank, $exp)
{
    if (!$exp || $exp == 0) {
        return 0;
    } else {
        if ($rank < 15) {
            $rankplusen = ($rank + 1);
            $percent4 = (exp_from($rankplusen) - $exp);
            $percent5 = (exp_to($rank) - exp_from($rank));
            $percent6 = ($percent4 / $percent5) * 100;
            return $percent7 = (100 - $percent6);
        } elseif ($rank == 15) {
            return $percent7 = 100;
        }
    }
}

function check_rankup($id, $rank, $exp, $pdo)
{
    if (rank_progress($rank, $exp) >= 100 && $rank < 15) {
        $sql = "UPDATE accounts_stat SET AS_rank = (AS_rank + 1) WHERE AS_id='" . $id . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $text = " ranket opp til " . rank_list($rank + 1) . "";
        $sql = "INSERT INTO last_events (LAEV_user, LAEV_text, LAEV_date) VALUES (?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $text, time()]);

        verve_premie($rank + 1, $id, $pdo);

        give_money($id, rank_prize($rank + 1), $pdo);
        $text = "Gratulerer, du har ranket opp til " . rank_list($rank + 1) . " og får " . number(rank_prize($rank + 1)) . " kr";
        send_notification($id, $text, $pdo);
    }
}

function get_city_tax($city, $pdo)
{
    $query = $pdo->prepare("SELECT CTAX_tax FROM city_tax WHERE CTAX_city=?");
    $query->execute(array($city));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['CTAX_tax'] ?? NULL;
}

function output_city_tax($city_tax)
{
    if ($city_tax <= 10) {
        echo '<span style="color: green;">' . $city_tax . '%</span>';
    } elseif ($city_tax >= 11 && $city_tax <= 20) {
        echo '<span style="color: orange;">' . $city_tax . '%</span>';
    } elseif ($city_tax >= 21 && $city_tax <= 35) {
        echo '<span style="color: #e94c24;">' . $city_tax . '%</span>';
    } elseif ($city_tax >= 36 && $city_tax <= 100) {
        echo '<span style="color: #fe2b2c;">' . $city_tax . '%</span>';
    }
}

function jail_owner_already($id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM fengsel_direktor WHERE FENGDI_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return !$row ? false : true;
}

function put_in_jail($acc_id, $time, $reason, $pdo)
{
    $sql = "INSERT INTO fengsel (FENG_acc_id, FENG_countdown, FENG_reason) VALUES (?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$acc_id, $time, $reason]);
}

function price_buyout_jail($acc_id, $pdo)
{
    $query = $pdo->prepare("SELECT FENG_countdown FROM fengsel WHERE FENG_acc_id = ?");
    $query->execute(array($acc_id));
    $JAIL_row = $query->fetch(PDO::FETCH_ASSOC);

    return $JAIL_row['FENG_countdown'] ?? NULL;
}

function people_in_jail($pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM fengsel");
    $stmt->execute();
    $count = $stmt->fetchColumn();

    return $count;
}

function fengsel_direktor_exist($city, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM fengsel_direktor WHERE FENGDI_city = ?");
    $stmt->execute([$city]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function get_fengselsdirektor($city, $pdo)
{
    $query = $pdo->prepare("SELECT FENGDI_acc_id FROM fengsel_direktor WHERE FENGDI_city = ?");
    $query->execute(array($city));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['FENGDI_acc_id'] ?? NULL;
}

function get_fengselsbank($city, $pdo)
{
    $query = $pdo->prepare("SELECT FENGDI_bank FROM fengsel_direktor WHERE FENGDI_city = ?");
    $query->execute(array($city));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['FENGDI_bank'] ?? NULL;
}

function player_in_jail($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM fengsel WHERE FENG_acc_id = ?");
    $stmt->execute([$acc_id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function change_city($id, $city, $pdo)
{
    $sql = "UPDATE accounts_stat SET AS_city = ? WHERE AS_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$city, $id]);
}

function get_city_price($city, $pdo)
{
    $city_price[0] = 9420;
    $city_price[1] = 12430;
    $city_price[2] = 9450;
    $city_price[3] = 8520;
    $city_price[4] = 12530;

    $query = $pdo->prepare("SELECT FLYPRIS_price FROM flyplass_pris WHERE FLYPRIS_city = ?");
    $query->execute(array($city));
    $FLYPRIS_row = $query->fetch(PDO::FETCH_ASSOC);

    if (!$FLYPRIS_row) {
        return $city_price[$city];
    } else {
        return $FLYPRIS_row['FLYPRIS_price'] ?? NULL;
    }
}

function city_price($city, $pdo)
{
    for ($i = 0; $i < 5; $i++) {
        $city_price[$i] = get_city_price($city, $pdo);
    }

    return $city_price[$city];
}

function edit_city_price($city, $price, $pdo)
{
    $query = $pdo->prepare("SELECT FLYPRIS_price FROM flyplass_pris WHERE FLYPRIS_city = ?");
    $query->execute(array($city));
    $FLYPRIS_row = $query->fetch(PDO::FETCH_ASSOC);

    if (!$FLYPRIS_row) {
        $sql = "INSERT INTO flyplass_pris (FLYPRIS_price, FLYPRIS_city) VALUES (?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$price, $city]);
    } else {
        $sql = "UPDATE flyplass_pris SET FLYPRIS_price = $price WHERE FLYPRIS_city='" . $city . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

function car($id)
{
    // Low-tier
    $car[0] = "Toyota Corolla";
    $car[1] = "Mercedes-Benz C-Klasse";
    $car[2] = "Honda Jazz";
    $car[3] = "Volvo 240";
    $car[4] = "Toyota Verso";
    $car[5] = "Audi A4";

    // Mid-tier
    $car[6] = "Audi Q3";
    $car[7] = "Mazda CX-3";
    $car[8] = "BMW X3";
    $car[9] = "Tesla Model Y";
    $car[10] = "Mercedes-Benz E-Klasse";
    $car[11] = "Nissan Qashqai";

    // high-tier
    $car[12] = "Audi e-tron 55";
    $car[13] = "BMW X5";
    $car[14] = "Tesla Model X";
    $car[15] = "Jaguar I-Pace";
    $car[16] = "Mercedes-Benz Geländewagen G63";
    $car[17] = "Audi Q4";

    // RARE
    $car[18] = "Rolls Royce Wraith";

    // SUPER RARE
    $car[19] = "1955 Mercedes 300SL Gullwing";
    $car[20] = "Bugatti La Voiture Noire";
    $car[21] = "Rolls-Royce Boat Tail";

    return $car[$id];
}

function car_price($id)
{
    // Low-tier
    $car_price[0] = 23000;
    $car_price[1] = 41000;
    $car_price[2] = 13000;
    $car_price[3] = 8000;
    $car_price[4] = 63000;
    $car_price[5] = 81000;

    // Mid-tier
    $car_price[6] = 229000;
    $car_price[7] = 184900;
    $car_price[8] = 175000;
    $car_price[9] = 298000;
    $car_price[10] = 149700;
    $car_price[11] = 138000;

    // High-tier
    $car_price[12] = 789000;
    $car_price[13] = 829000;
    $car_price[14] = 819000;
    $car_price[15] = 759000;
    $car_price[16] = 899000;
    $car_price[17] = 898000;

    // RARE
    $car_price[18] = 5450000;

    // SUPER RARE
    $car_price[19] = 39800000;
    $car_price[20] = 150005000;
    $car_price[21] = 240200000;

    return $car_price[$id];
}

function amount_of_specific_car($acc_id, $car_id, $city, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM garage WHERE GA_acc_id = ? AND GA_car_id = ? AND GA_car_city = ?");
    $stmt->execute([$acc_id, $car_id, $city]);
    $count = $stmt->fetchColumn();

    return $count;
}

function get_city_car($acc_id, $car_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT GA_car_city FROM garage WHERE GA_acc_id = ? AND GA_car_id = ?");
    $stmt->execute([$acc_id, $car_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['GA_car_city'] ?? NULL;
}

function amount_of_car($acc_id, $city, $id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM garage WHERE GA_acc_id = ? AND GA_car_id = ? AND GA_car_city = ?");
    $stmt->execute([$acc_id, $id, $city]);
    $count = $stmt->fetchColumn();

    return $count;
}

function update_garage($acc_id, $car_id, $city_id, $pdo)
{
    $sql = "INSERT INTO garage (GA_acc_id, GA_car_id, GA_car_city) VALUES (?,?,?)";
    $pdo->prepare($sql)->execute([$acc_id, $car_id, $city_id]);
}

function total_cars($acc_id, $pdo)
{
    $cars_on_market = total_marked_listings_user($acc_id, 0, $pdo);

    $stmt = $pdo->prepare("SELECT count(*) FROM garage WHERE GA_acc_id = ?");
    $stmt->execute([$acc_id]);
    $count = $stmt->fetchColumn();

    return $count + $cars_on_market;
}


function total_things($acc_id, $pdo)
{
    $things_on_market = total_marked_listings_user($acc_id, 1, $pdo);

    $stmt = $pdo->prepare("SELECT count(*) FROM things WHERE TH_acc_id = ? AND NOT TH_type = 39");
    $stmt->execute([$acc_id]);
    $count = $stmt->fetchColumn();

    return $count + $things_on_market;
}

function value_all_cars($acc, $pdo)
{
    $value = 0;
    $sql = "SELECT GA_car_id FROM garage WHERE GA_acc_id = " . $acc . "";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $value = $value + car_price($row['GA_car_id']);
    }
    return $value;
}

function garage_car_exist($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM garage WHERE GA_acc_id = ?");
    $stmt->execute([$acc_id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function showBBcodes($text)
{
    // BBcode array
    $find = array(
        '~\[center\](.*?)\[/center\]~s',
        '~\[b\](.*?)\[/b\]~s',
        '~\[i\](.*?)\[/i\]~s',
        '~\[u\](.*?)\[/u\]~s',
        '~\[quote\](.*?)\[/quote\]~s',
        '~\[size=(.*?)\](.*?)\[/size\]~s',
        '~\[url=((?:ftp|https?)://.*?)\](.*?)\[/url\]~s',
        '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
        '~\[smiley\](.*?)\[/smiley\]~s',
        '~\[color=((?:[a-zA-Z]|#[a-fA-F0-9]{3,6})+)\](.*?)\[/color\]~s',
        '~\[youtube\](.*?)\[/youtube\]~s',
        '~\[tr\](.*?)\[/tr\]~s',
        '~\[spotify\](.*?)\[/spotify\]~s',
        '~\[spotify compact\](.*?)\[/spotify\]~s',
    );
    // HTML tags to replace BBcode
    $replace = array(
        '<center>$1</center>',
        '<b>$1</b>',
        '<i>$1</i>',
        '<u>$1</u>',
        '<pre>$1</' . 'pre>',
        '<span style="font-size:$1px;">$2</span>',
        '<a href="$1" target="_blank" style="text-decoration: none; color: white;">$2</a>',
        '<img src="$1" class="bb_code_img" />',
        '<img src="img/emoticons/$1.png" alt="" />',
        '<span style="color:$1;">$2</span>',
        '<embed width="420" height="315" src="https://www.youtube.com/v/$1">',
        '<span id="transparent">$1</span>',
        '<iframe src="https://open.spotify.com/embed/$1" width="100%" height="380" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>',
        '<iframe src="https://open.spotify.com/embed/$1" width="100%" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>',
    );
    // Replacing the BBcodes with corresponding HTML tags
    return preg_replace($find, $replace, $text);
}

function thing($id)
{
    // low-tier
    $thing[0] = "Laptop";
    $thing[1] = "Støvsuger";
    $thing[2] = "Potteplante";
    $thing[3] = "Hasjplante";
    $thing[4] = "Komfyr";
    $thing[5] = "Dørmatte";
    $thing[6] = "Ringeklokke";
    $thing[7] = "Sko-stativ";
    $thing[8] = "Pokal";
    $thing[9] = "Motorolje";

    // mid-tier
    $thing[10] = "Dobbeltseng";
    $thing[11] = "Kjøleskap";
    $thing[12] = "Tørketrommel";
    $thing[13] = "Stereoanlegg";
    $thing[14] = '55" tommer TV';
    $thing[15] = '75" TV';
    $thing[16] = "Stasjonær PC";
    $thing[17] = "Pass";
    $thing[18] = "Visakort";
    $thing[19] = "Mastercard";

    // high-tier
    $thing[20] = "Diamant ring";
    $thing[21] = "Pushwagner maleri";
    $thing[22] = "Persisk teppe";
    $thing[23] = "Gullklokke";
    $thing[24] = "Gull pokal";
    $thing[25] = "Bitz bestikk";
    $thing[26] = "Lysestake";
    $thing[27] = "Damp-ovn";
    $thing[28] = "Samsung smart fridge";

    // RARE
    $thing[29] = "Energidrikk"; // gir dobbel exp i 30 min
    $thing[30] = "Hemmelig kiste"; // Kan gi penger, kuler eller ingenting.

    $thing[31] = 'Påskeegg';
    $thing[32] = "Familie energidrikk"; // gir dobbel exp i 30 min

    // Julegave
    $thing[33] = "Julegave";

    $thing[34] = "Happy hour 1t";
    $thing[35] = "Happy hour 3t";
    $thing[36] = "Happy hour 6t";
    $thing[37] = "Happy hour 12t";
    $thing[38] = "Happy hour 24t";

    $thing[39] = "Diamant";

    return $thing[$id];
}

function thing_price($id)
{
    $thing_price[0] = 3500;
    $thing_price[1] = 2100;
    $thing_price[2] = 500;
    $thing_price[3] = 750;
    $thing_price[4] = 2400;
    $thing_price[5] = 120;
    $thing_price[6] = 50;
    $thing_price[7] = 500;
    $thing_price[8] = 250;
    $thing_price[9] = 900;
    $thing_price[10] = 5400;
    $thing_price[11] = 5300;
    $thing_price[12] = 4200;
    $thing_price[13] = 2400;
    $thing_price[14] = 4444;
    $thing_price[15] = 7777;
    $thing_price[16] = 8240;
    $thing_price[17] = 3200;
    $thing_price[18] = 0;
    $thing_price[19] = 0;
    $thing_price[20] = 15000;
    $thing_price[21] = 35000;
    $thing_price[22] = 17000;
    $thing_price[23] = 14000;
    $thing_price[24] = 7200;
    $thing_price[25] = 1750;
    $thing_price[26] = 690;
    $thing_price[27] = 4995;
    $thing_price[28] = 44900;

    // rare
    $thing_price[29] = 0;
    $thing_price[30] = 0;

    $thing_price[31] = 0;
    $thing_price[32] = 0;

    $thing_price[33] = 0;
    $thing_price[34] = 0;
    $thing_price[35] = 0;
    $thing_price[36] = 0;
    $thing_price[37] = 0;
    $thing_price[38] = 0;

    $thing_price[39] = 0;

    return $thing_price[$id];
}

function value_all_things($id, $pdo)
{
    $value = 0;
    $sql = "SELECT TH_type FROM things WHERE TH_acc_id = " . $id . "";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $value = $value + thing_price($row['TH_type']);
    }
    return $value;
}

function amount_of_things($acc_id, $id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM things WHERE TH_acc_id = ? AND TH_type = ?");
    $stmt->execute([$acc_id, $id]);
    $count = $stmt->fetchColumn();

    return $count;
}

function things_exist($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM things WHERE TH_acc_id = ?");
    $stmt->execute([$acc_id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function total_bought_stocks($id, $pdo)
{
    $query = $pdo->prepare("SELECT SUM(STLG_price) as sum FROM stocks_logg WHERE STLG_acc_id = ? AND STLG_action = ?");
    $query->execute(array($id, 0));
    $LOTTO_row = $query->fetch(PDO::FETCH_ASSOC);

    if ($LOTTO_row) {
        return $LOTTO_row['sum'] ?? NULL;
    } else {
        return 0;
    }
}

function total_sold_stocks($id, $pdo)
{
    $query = $pdo->prepare("SELECT SUM(STLG_price) as sum FROM stocks_logg WHERE STLG_acc_id = ? AND STLG_action = ?");
    $query->execute(array($id, 1));
    $LOTTO_row = $query->fetch(PDO::FETCH_ASSOC);

    if ($LOTTO_row) {
        return $LOTTO_row['sum'] ?? NULL;
    } else {
        return 0;
    }
}

function get_daily_weed($id, $pdo)
{
    $query = $pdo->prepare("SELECT AS_weed FROM accounts_stat WHERE AS_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row && !is_numeric($row['AS_weed'])) {
        return 0;
    } else {
        return $row['AS_weed'] ?? NULL;
    }
}

function smugling_demand($id, $pdo)
{
    $equipment_demand[0] = 2;
    $equipment_demand[1] = 5;
    $equipment_demand[2] = 10;

    $demand = 0;

    $sthandler = $pdo->prepare("SELECT * FROM smugling WHERE SMUG_acc_id = :id");
    $sthandler->bindParam(':id', $id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        $sql = "SELECT * FROM smugling WHERE SMUG_acc_id = " . $id . "";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $demand = $demand + $equipment_demand[$row['SMUG_type']];
        }
    }

    return $demand;
}

function smugling_money_midnight($id, $pdo)
{
    $equipment_price[0] = 2500000;
    $equipment_price[1] = 25000000;
    $equipment_price[2] = 250000000;

    $equipment_pr_krim[0] = 1250;
    $equipment_pr_krim[1] = 12500;
    $equipment_pr_krim[2] = 125000;

    $money = 0;

    $sthandler = $pdo->prepare("SELECT * FROM smugling WHERE SMUG_acc_id = :id");
    $sthandler->bindParam(':id', $id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        $sql = "SELECT * FROM smugling WHERE SMUG_acc_id = " . $id . "";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $money = $money + $equipment_pr_krim[$row['SMUG_type']];
        }
    }

    return $money;
}

function remove_equipment($id, $sell_option, $pdo)
{
    $sql = "DELETE FROM smugling WHERE SMUG_acc_id = $id AND SMUG_type = $sell_option LIMIT 1";
    $pdo->exec($sql);
}

function total_equipment($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM smugling WHERE SMUG_acc_id = ?");
    $stmt->execute([$acc_id]);
    return $count = $stmt->fetchColumn();
}

function amount_of_pages($forum_id, $pdo)
{
    $limit = 10;
    $query = "SELECT count(*) FROM forum WHERE FRM_topic_id = $forum_id";

    $s = $pdo->query($query);
    $total_results = $s->fetchColumn();
    $total_pages = ceil($total_results / $limit);

    if ($total_pages == 0) {
        $total_pages = 1;
    }

    return $total_pages;
}

function total_posts($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM forum WHERE FRM_acc_id = :id");
    $stmt->execute(['id' => $id]);
    $count = $stmt->fetchColumn();

    return $count;
}

function edited_before($forum_id, $pdo)
{
    $sthandler = $pdo->prepare("SELECT * FROM forum_edited WHERE FEDIT_forum_id = :id");
    $sthandler->bindParam(':id', $forum_id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function edited_date($forum_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT FEDIT_date FROM forum_edited WHERE FEDIT_forum_id = ?');
    $stmt->bindParam(1, $forum_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['FEDIT_date'] ?? NULL;
}

function edited_by($forum_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT FEDIT_by FROM forum_edited WHERE FEDIT_forum_id = ?');
    $stmt->bindParam(1, $forum_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['FEDIT_by'] ?? NULL;
}

function get_forum_likes($forum_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM forum_likes WHERE FLIKES_forum_id = ? AND FLIKES_type = ?");
    $stmt->execute([$forum_id, 0]);
    $likes = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT count(*) FROM forum_likes WHERE FLIKES_forum_id = ? AND FLIKES_type = ?");
    $stmt->execute([$forum_id, 1]);
    $dislikes = $stmt->fetchColumn();

    return $likes - $dislikes;
}

function total_kast_mynt_games($pdo)
{
    $query = $pdo->prepare("SELECT COUNT(*) as count FROM kast_mynt");
    $query->execute(array());
    $KM_row = $query->fetch(PDO::FETCH_ASSOC);

    return $KM_row['count'] ?? NULL;
}

$max_airports = 5;
function airport_ownership($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM flyplass WHERE FP_owner = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetchColumn();

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function airport_existence($city, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM flyplass WHERE FP_city = ?');
    $stmt->bindParam(1, $city, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return false;
    } else {
        return true;
    }
}

function airport_owner($city, $pdo)
{
    $stmt = $pdo->prepare('SELECT FP_owner FROM flyplass WHERE FP_city = ?');
    $stmt->bindParam(1, $city, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['FP_owner'] ?? NULL;
}

function give_airport_money($owner, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE flyplass SET FP_money = (FP_money + $amount) WHERE FP_owner='" . $owner . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function airport_city_owner($id, $pdo)
{
    $stmt = $pdo->prepare('SELECT FP_city FROM flyplass WHERE FP_owner = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['FP_city'] ?? NULL;
}

function airport_money($id, $city, $pdo)
{
    $stmt = $pdo->prepare('SELECT FP_money FROM flyplass WHERE FP_owner = ? AND FP_city = ' . $city . '');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['FP_money'] ?? NULL;
}

function airport_owner_already($id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM flyplass WHERE FP_owner = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return !$row ? false : true;
}

function max_eiendeler($id, $pdo)
{
    $eiendeler = 0;

    $stmt = $pdo->prepare('SELECT * FROM rc_owner WHERE RCOWN_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_rc = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_rc) {
        $eiendeler = $eiendeler + 1;
    }

    $stmt = $pdo->prepare('SELECT * FROM kf_owner WHERE KFOW_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_kf = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_kf) {
        $eiendeler = $eiendeler + 1;
    }

    $stmt = $pdo->prepare('SELECT * FROM fengsel_direktor WHERE FENGDI_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_fengsel = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_fengsel) {
        $eiendeler = $eiendeler + 1;
    }

    $stmt = $pdo->prepare('SELECT * FROM flyplass WHERE FP_owner = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_fp = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_fp) {
        $eiendeler = $eiendeler + 1;
    }

    $stmt = $pdo->prepare('SELECT * FROM blackjack_owner WHERE BJO_owner = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_bj = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_bj) {
        $eiendeler = $eiendeler + 1;
    }

    if ($eiendeler >= 4) {
        return true;
    } else {
        return false;
    }
}

function has_eiendeler($id, $pdo)
{
    $eiendeler = 0;

    $stmt = $pdo->prepare('SELECT * FROM rc_owner WHERE RCOWN_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_rc = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_rc) {
        return true;
    }

    $stmt = $pdo->prepare('SELECT * FROM kf_owner WHERE KFOW_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_kf = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_kf) {
        return true;
    }

    $stmt = $pdo->prepare('SELECT * FROM fengsel_direktor WHERE FENGDI_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_fengsel = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_fengsel) {
        return true;
    }

    $stmt = $pdo->prepare('SELECT * FROM flyplass WHERE FP_owner = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_fp = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_fp) {
        return true;
    }

    $stmt = $pdo->prepare('SELECT * FROM blackjack_owner WHERE BJO_owner = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row_bj = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row_bj) {
        return true;
    }
}

function get_family_bullets($fam_id, $pdo)
{
    $query = $pdo->prepare("SELECT FAM_bullets FROM family WHERE FAM_id = ?");
    $query->execute(array($fam_id));
    $family_row = $query->fetch(PDO::FETCH_ASSOC);

    if (is_numeric($family_row['FAM_bullets'])) {
        return $family_row['FAM_bullets'] ?? NULL;
    } else {
        return 0;
    }
}

function get_familyname($fam_id, $pdo)
{
    $query = $pdo->prepare("SELECT FAM_name FROM family WHERE FAM_id = ?");
    $query->execute(array($fam_id));
    $family_row = $query->fetch(PDO::FETCH_ASSOC);

    return $family_row['FAM_name'] ?? NULL;
}

function get_my_familyname($id, $pdo)
{
    $query = $pdo->prepare("SELECT FAMMEM_fam_id FROM family_member WHERE FAMMEM_acc_id=?");
    $query->execute(array($id));
    $family_mem_row = $query->fetch(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT FAM_name FROM family WHERE FAM_id = ?");
    $query->execute(array($family_mem_row['FAMMEM_fam_id']));
    $family_row = $query->fetch(PDO::FETCH_ASSOC);

    return $family_row['FAM_name'] ?? NULL;
}

function get_my_familybank($fam_id, $pdo)
{
    $query = $pdo->prepare("SELECT FAM_bank FROM family WHERE FAM_id = ?");
    $query->execute(array($fam_id));
    $family_row = $query->fetch(PDO::FETCH_ASSOC);

    return $family_row['FAM_bank'] ?? NULL;
}

function get_familyID($name, $pdo)
{
    $query = $pdo->prepare("SELECT FAM_id FROM family WHERE FAM_name = ?");
    $query->execute(array($name));
    $family_row = $query->fetch(PDO::FETCH_ASSOC);

    return $family_row['FAM_id'] ?? NULL;
}

function get_my_familyID($id, $pdo)
{
    $query = $pdo->prepare("SELECT FAMMEM_fam_id FROM family_member WHERE FAMMEM_acc_id=?");
    $query->execute(array($id));
    $family_mem_row = $query->fetch(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT FAM_id FROM family WHERE FAM_id = ?");
    $query->execute(array($family_mem_row['FAMMEM_fam_id']));
    $family_row = $query->fetch(PDO::FETCH_ASSOC);

    return $family_row['FAM_id'] ?? NULL;
}

function get_my_familyrole($id, $pdo)
{
    $query = $pdo->prepare("SELECT FAMMEM_role FROM family_member WHERE FAMMEM_acc_id=?");
    $query->execute(array($id));
    $family_mem_row = $query->fetch(PDO::FETCH_ASSOC);

    return $family_mem_row['FAMMEM_role'] ?? NULL;
}

function total_families($pdo)
{
    $ledelsen = "Ledelsen";
    $stmt = $pdo->prepare("SELECT count(*) FROM family WHERE NOT FAM_name = ?");
    $stmt->execute(['Ledelsen']);
    $count = $stmt->fetchColumn();

    return $count;
}

function total_family_okonom($family_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM family_member WHERE FAMMEM_fam_id = ? AND FAMMEM_role = ?");
    $stmt->execute([$family_id, 2]);
    $count = $stmt->fetchColumn();

    return $count;
}

function total_family_direktor($family_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM family_member WHERE FAMMEM_fam_id = ? AND FAMMEM_role = ?");
    $stmt->execute([$family_id, 1]);
    $count = $stmt->fetchColumn();

    return $count;
}

function total_family_sjef($family_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM family_member WHERE FAMMEM_fam_id = ? AND FAMMEM_role = ?");
    $stmt->execute([$family_id, 0]);
    $count = $stmt->fetchColumn();

    return $count;
}

function total_family_members($family_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM family_member WHERE FAMMEM_fam_id = ?");
    $stmt->execute([$family_id]);
    $count = $stmt->fetchColumn();

    return $count;
}

function make_family($family_name, $acc_id, $pdo)
{
    $sql = "INSERT INTO family (FAM_name, FAM_date) VALUES (?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$family_name, time()]);

    $stmt = $pdo->query("SELECT LAST_INSERT_ID() FROM family");
    $lastId = $stmt->fetchColumn();

    $sql = "INSERT INTO family_member (FAMMEM_acc_id, FAMMEM_fam_id, FAMMEM_date) VALUES (?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$acc_id, $lastId, time()]);
}

function family_member_exist($id, $pdo)
{
    $sthandler = $pdo->prepare("SELECT FAMMEM_acc_id FROM family_member WHERE FAMMEM_acc_id = :id");
    $sthandler->bindParam(':id', $id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function family_roles($role_nr)
{
    $family_role[0] = "Sjef";
    $family_role[1] = "Direktør";
    $family_role[2] = "Økonom";
    $family_role[3] = "Medlem";

    return $family_role[$role_nr];
}

function family_exist($family_name, $pdo)
{
    $sthandler = $pdo->prepare("SELECT FAM_name FROM family WHERE FAM_name = :name");
    $sthandler->bindParam(':name', $family_name);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function family_exist_id($family_id, $pdo)
{
    $sthandler = $pdo->prepare("SELECT FAM_name FROM family WHERE FAM_id = :id");
    $sthandler->bindParam(':id', $family_id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function get_family_bon($acc_id, $pdo)
{
    $query = $pdo->prepare("SELECT FAMMEM_fam_id FROM family_member WHERE FAMMEM_acc_id = ?");
    $query->execute(array($acc_id));
    $fam_row = $query->fetch(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT FAM_bedrift FROM family WHERE FAM_id = ?");
    $query->execute(array($fam_row['FAMMEM_fam_id']));
    $bedr_row = $query->fetch(PDO::FETCH_ASSOC);

    return $bedr_row['FAM_bedrift'] ?? NULL;
}

function get_family_member($fam_id, $role, $pdo)
{
    $query = $pdo->prepare("SELECT FAMMEM_acc_id FROM family_member WHERE FAMMEM_fam_id = ? AND FAMMEM_role = ?");
    $query->execute(array($fam_id, $role));
    $family_row = $query->fetch(PDO::FETCH_ASSOC);

    return $family_row['FAMMEM_acc_id'] ?? NULL;
}

function get_family_bedrift($fam_id, $pdo)
{
    $query = $pdo->prepare("SELECT FAM_bedrift FROM family WHERE FAM_id = ?");
    $query->execute(array($fam_id));
    $family_row = $query->fetch(PDO::FETCH_ASSOC);

    return $family_row['FAM_bedrift'] ?? NULL;
}

function get_family_defence($fam_id, $pdo)
{
    $query = $pdo->prepare("SELECT FAM_forsvar FROM family WHERE FAM_id = ?");
    $query->execute(array($fam_id));
    $family_row = $query->fetch(PDO::FETCH_ASSOC);

    if (is_numeric($family_row['FAM_forsvar'])) {
        return $family_row['FAM_forsvar'] ?? NULL;
    } else {
        return 0;
    }
}

function give_family_defence($fam_id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE family SET FAM_forsvar = (FAM_forsvar + $amount) WHERE FAM_id='" . $fam_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function take_family_defence($fam_id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE family SET FAM_forsvar = (FAM_forsvar + $amount) WHERE FAM_id='" . $fam_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function total_applicants($family_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM family_applicant WHERE FAMAP_fam_id = ?");
    $stmt->execute([$family_id]);
    $count = $stmt->fetchColumn();

    if ($count != 0) {
        return $count;
    }
}

function territorium_ready($acc_id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_acc_id AND CD_territorium < ' . time() . '');
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

function territorium_give_cooldown($acc_id, $time, $pdo)
{
    $sql = "UPDATE cooldown SET CD_territorium = $time WHERE CD_acc_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}


function city_owner_exist($city, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM territorium WHERE TE_city = ?");
    $stmt->execute([$city]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function get_city_owner($city, $pdo)
{
    $query = $pdo->prepare("SELECT TE_family_id FROM territorium WHERE TE_city = ?");
    $query->execute(array($city));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['TE_family_id'] ?? NULL;
}

function family_owner_exist($family_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM territorium WHERE TE_family_id = ?");
    $stmt->execute([$family_id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function innbyggere($city, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM accounts_stat WHERE AS_city = ?");
    $stmt->execute([$city]);
    $count = $stmt->fetchColumn();

    return $count;
}

function amount_of_city_owned($family_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM territorium WHERE TE_family_id = ?");
    $stmt->execute([$family_id]);
    $count = $stmt->fetchColumn();

    return $count;
}

function give_territorium_money($city, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE territorium SET TE_money = TE_money + ? WHERE TE_city = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $city]);
}

function get_family_leader($family_id, $pdo)
{
    $query = $pdo->prepare("SELECT FAMMEM_acc_id FROM family_member WHERE FAMMEM_role = ? AND FAMMEM_fam_id = ?");
    $query->execute(array(0, $family_id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['FAMMEM_acc_id'] ?? NULL;
}

function get_city_money($city, $pdo)
{
    $query = $pdo->prepare("SELECT TE_money FROM territorium WHERE TE_city = ?");
    $query->execute(array($city));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['TE_money'] ?? NULL;
}

function give_my_family_money($fam_id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE family SET FAM_bank = (FAM_bank + $amount) WHERE FAM_id='" . $fam_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function take_my_family_money($fam_id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE family SET FAM_bank = (FAM_bank - $amount) WHERE FAM_id='" . $fam_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function update_donationlist($fam_id, $acc_id, $action, $value, $pdo)
{
    $date = time();
    $sql = "INSERT INTO family_donation (FAMDON_fam_id, FAMDON_acc_id, FAMDON_action, FAMDON_value, FAMDON_date) VALUES (?,?,?,?,?)";
    $pdo->prepare($sql)->execute([$fam_id, $acc_id, $action, $value, $date]);
}

function give_kf_money($city, $amount, $pdo)
{
    $sql = "UPDATE kf_owner SET KFOW_money = (KFOW_money + $amount) WHERE KFOW_city = $city";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function take_kf_money($city, $amount, $pdo)
{
    $sql = "UPDATE kf_owner SET KFOW_money = (KFOW_money - $amount) WHERE KFOW_city = $city";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function kf_owner($id, $pdo)
{
    $sthandler = $pdo->prepare("SELECT * FROM kf_owner WHERE KFOW_acc_id = :id");
    $sthandler->bindParam(':id', $id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function kf_owner_exist($city, $pdo)
{
    $sthandler = $pdo->prepare("SELECT * FROM kf_owner WHERE KFOW_city = :city");
    $sthandler->bindParam(':city', $city);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function get_kf_money($city, $pdo)
{
    $query = $pdo->prepare("SELECT KFOW_money FROM kf_owner WHERE KFOW_city = ?");
    $query->execute(array($city));
    $kf_row = $query->fetch(PDO::FETCH_ASSOC);

    return $kf_row['KFOW_money'] ?? NULL;
}

function get_kf_owner($city, $pdo)
{
    $query = $pdo->prepare("SELECT KFOW_acc_id FROM kf_owner WHERE KFOW_city = ?");
    $query->execute(array($city));
    $kf_row = $query->fetch(PDO::FETCH_ASSOC);

    return $kf_row['KFOW_acc_id'] ?? NULL;
}

function get_kf_owner_city($id, $pdo)
{
    $query = $pdo->prepare("SELECT KFOW_city FROM kf_owner WHERE KFOW_acc_id = ?");
    $query->execute(array($id));
    $kf_row = $query->fetch(PDO::FETCH_ASSOC);

    if ($kf_row) {
        return $kf_row['KFOW_city'] ?? NULL;
    }
}

function active_terning($acc_id, $pdo)
{
    $sthandler = $pdo->prepare("SELECT TERN_player FROM terninger WHERE TERN_player = :id");
    $sthandler->bindParam(':id', $acc_id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function active_lotto($id, $pdo)
{
    $query = $pdo->prepare("SELECT LOTTO_acc_id FROM lotto WHERE LOTTO_acc_id = ?");
    $query->execute(array($id));
    $LOTTO_row = $query->fetch(PDO::FETCH_ASSOC);

    return $LOTTO_row ? true : false;
}

function lotto_coupon($id, $pdo)
{
    $total_cupons = 0;

    $sql = "SELECT LOTTO_amount FROM lotto WHERE LOTTO_acc_id = $id";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $total_cupons = $total_cupons + $row['LOTTO_amount'];
    }

    return $total_cupons;
}

function buy_lotto_coupon($acc_id, $amount, $pdo)
{
    $date = time();
    $sql = "INSERT INTO lotto (LOTTO_acc_id, LOTTO_amount, LOTTO_date) VALUES (?, ?, ?)";
    $pdo->prepare($sql)->execute([$acc_id, $amount, $date]);
}

function lotto_jackpot($pdo)
{
    $query = $pdo->prepare("SELECT SUM(LOTTO_amount) as sum FROM lotto");
    $query->execute(array());
    $LOTTO_row = $query->fetch(PDO::FETCH_ASSOC);

    return $LOTTO_row['sum'] ?? NULL;
}

function lotto_total_coupon($pdo)
{
    $query = $pdo->prepare("SELECT SUM(LOTTO_amount) as sum FROM lotto");
    $query->execute(array());
    $LOTTO_row = $query->fetch(PDO::FETCH_ASSOC);

    return $LOTTO_row['sum'] ?? NULL;
}

function send_pm_main($pm_id, $id_from, $id_to, $title, $pdo)
{
    $sql = "INSERT INTO pm (PM_pmid, PM_acc_id_from, PM_acc_id_to, PM_title, PM_date) VALUES (?,?,?,?,?)";
    $pdo->prepare($sql)->execute([$pm_id, $id_from, $id_to, $title, time()]);
}

function send_pm_text($pm_id, $acc_id, $text, $pdo)
{
    $sql = "INSERT INTO pm_text (PMT_pmid, PMT_acc_id, PMT_text, PMT_date) VALUES (?,?,?,?)";
    $pdo->prepare($sql)->execute([$pm_id, $acc_id, $text, time()]);
}

function set_pm_new($pm_id, $id_from, $id_to, $pdo)
{
    $old = 0;
    $new = 1;
    $sql = "INSERT INTO pm_new (PMN_pmid, PMN_acc_id1, PMN_acc_id1_new, PMN_acc_id2, PMN_acc_id2_new) VALUES (?,?,?,?,?)";
    $pdo->prepare($sql)->execute([$pm_id, $id_from, $old, $id_to, $new]);
}

function unread_pm($ID, $pdo)
{
    $postboks = 0;
    $sql = "SELECT PMN_acc_id1, PMN_acc_id1_new, PMN_acc_id2, PMN_acc_id2_new FROM pm_new WHERE PMN_acc_id1 = $ID OR PMN_acc_id2= $ID";
    $stmt = $pdo->query($sql);
    while ($row_pm_new = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row_pm_new['PMN_acc_id1'] == $ID && $row_pm_new['PMN_acc_id1_new'] > 0) {
            $postboks++;
        } elseif ($row_pm_new['PMN_acc_id2'] == $ID && $row_pm_new['PMN_acc_id2_new'] > 0) {
            $postboks++;
        }
    }

    return $postboks++;
}

function pm_exist($id, $pdo)
{
    $sthandler = $pdo->prepare("SELECT * FROM pm WHERE PM_pmid = :id");
    $sthandler->bindParam(':id', $id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function check_protection($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM accounts_stat WHERE AS_id = :id AND AS_protection = :protect");
    $stmt->execute(['id' => $acc_id, 'protect' => 0]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function get_city($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT AS_city FROM accounts_stat WHERE AS_id = :id");
    $stmt->execute(['id' => $acc_id]);
    $row = $stmt->fetch();

    return $row['AS_city'] ?? NULL;
}

function start_heist($type, $countdown, $leader, $city, $status, $info, $pdo)
{
    $sql = "INSERT INTO heist (HEIST_type, HEIST_countdown, HEIST_leader, HEIST_city, HEIST_status, HEIST_info) VALUES (?,?,?,?,?,?)";
    $pdo->prepare($sql)->execute([$type, $countdown, $leader, $city, $status, $info]);

    $last_id = $pdo->lastInsertId();

    $sql = "INSERT INTO heist_members (HEIME_acc_id, HEIME_heist_id) VALUES (?,?)";
    $pdo->prepare($sql)->execute([$leader, $last_id]);
}

function active_heist($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT HEIME_heist_id FROM heist_members WHERE HEIME_acc_id = :id");
    $stmt->execute(['id' => $acc_id]);
    $row = $stmt->fetch();

    if ($row) {
        return true;
    } else {
        return false;
    }
}

function heist_exist($heist_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT HEIST_id FROM heist WHERE HEIST_id = :id");
    $stmt->execute(['id' => $heist_id]);
    $row = $stmt->fetch();

    if ($row) {
        return true;
    } else {
        return false;
    }
}

function get_my_heistID($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT HEIME_heist_id FROM heist_members WHERE HEIME_acc_id = :id");
    $stmt->execute(['id' => $acc_id]);
    $row = $stmt->fetch();

    return $row['HEIME_heist_id'] ?? NULL;
}

function heist_row($id, $what, $pdo)
{
    $query = $pdo->prepare("SELECT $what as AS_row FROM heist WHERE HEIST_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['AS_row'] ?? NULL;
}

/**
 * Get members of a heist by ID
 *
 * @param int $id HEIST_id
 * @param PDO $pdo PDO
 * @return Array of objects (attributes are ACC_id and is_leader)
 */
function heist_members($id, $pdo)
{
    $query = $pdo->prepare(
        "SELECT HEIME_acc_id AS ACC_id, HEIST_leader AS is_leader FROM heist_members LEFT JOIN heist ON HEIME_acc_id=HEIST_leader AND HEIME_heist_id=HEIST_id WHERE HEIME_heist_id=?"
    );
    $query->execute([$id]);

    return $query->fetchAll(PDO::FETCH_OBJ) ?? NULL;
}

/**
 * End a heist by ID
 *
 * @param int $id HEIST_id
 * @param PDO $pdo PDO
 */
function heist_end($id, $pdo)
{
    $sql = "DELETE heist, heist_members FROM heist INNER JOIN heist_members ON HEIST_id=HEIME_heist_id WHERE HEIST_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

/**
 * Unused function (Consider removal)
 * @deprecated Use heist_members($id, $pdo) instead
 */
function heistmember_row($id, $what, $pdo)
{
    $query = $pdo->prepare("SELECT $what as AS_row FROM heist_members WHERE HEIME_heist_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['AS_row'] ?? NULL;
}

/**
 * Unused function (Consider removal)
 * @deprecated Use count(heist_members($id, $pdo)) instead
 */
function amount_in_heist($heist_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM heist_members WHERE HEIME_heist_id = :id");
    $stmt->execute(['id' => $heist_id]);
    $count = $stmt->fetchColumn();

    return $count;
}

function total_open_heist($pdo)
{
    $heist_id = 0;
    $stmt = $pdo->prepare("SELECT count(*) FROM heist WHERE HEIST_status = :id");
    $stmt->execute(['id' => $heist_id]);
    $count = $stmt->fetchColumn();

    return $count;
}

function upgrade_garage($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE user_statistics SET US_max_cars = (US_max_cars + $amount) WHERE US_acc_id='" . $id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function upgrade_thing($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE user_statistics SET US_max_things = (US_max_things + $amount) WHERE US_acc_id='" . $id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function get_ransoffer($id, $pdo)
{
    $zero = 0;
    $min_cash = 25000;

    $query = $pdo->prepare("SELECT AS_id FROM accounts_stat WHERE AS_protection = ? AND NOT AS_id = ? AND AS_money >= $min_cash ORDER BY RAND()");
    $query->execute(array($zero, $id));
    $rob_row = $query->fetch(PDO::FETCH_ASSOC);

    if ($rob_row) {
        return $rob_row['AS_id'] ?? NULL;
    } else {
        return false;
    }
}

function update_things($acc_id, $thing_id, $pdo)
{
    $sql = "INSERT INTO things (TH_acc_id, TH_type) VALUES (?,?)";
    $pdo->prepare($sql)->execute([$acc_id, $thing_id]);
}

function give_weed($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE accounts_stat SET AS_weed = (AS_weed + $amount) WHERE AS_id='" . $id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function take_weed($id, $amount, $pdo)
{
    $amount = floor($amount);
    $sql = "UPDATE accounts_stat SET AS_weed = (AS_weed - $amount) WHERE AS_id='" . $id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function amount_of_equipment($id, $i, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM smugling WHERE SMUG_acc_id = ? AND SMUG_type = ?");
    $stmt->execute([$id, $i]);
    $count = $stmt->fetchColumn();

    return $count;
}

function equipment_exist($id, $type, $pdo)
{
    $sthandler = $pdo->prepare("SELECT * FROM smugling WHERE SMUG_acc_id = :id AND SMUG_type = :smug_type");
    $sthandler->bindParam(':id', $id);
    $sthandler->bindParam(':smug_type', $type);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function max_equipment($id, $max, $pdo)
{
    $sthandler = $pdo->prepare("SELECT *, COUNT(*) AS count FROM smugling WHERE SMUG_acc_id = :id AND count > $max");
    $sthandler->bindParam(':id', $id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function get_smug_days($id, $pdo)
{
    $sthandler = $pdo->prepare("SELECT * FROM smugling_daily WHERE SMDAI_acc_id = :id");
    $sthandler->bindParam(':id', $id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        $query = $pdo->prepare("SELECT SMDAI_days FROM smugling_daily WHERE SMDAI_acc_id=?");
        $query->execute(array($id));
        $smdai = $query->fetch(PDO::FETCH_ASSOC);

        return $smdai['SMDAI_days'] ?? NULL;
    }
}

function give_double_exp($acc_id, $pdo)
{
    $date = time() + 10800;
    $sql = "INSERT INTO double_xp (DX_acc_id, DX_date) VALUES (?,?)";
    $pdo->prepare($sql)->execute([$acc_id, $date]);
}

function remove_energy_drink($acc_id, $pdo)
{
    $sql = "DELETE FROM things WHERE TH_type = 29 AND TH_acc_id = $acc_id LIMIT 1";
    $pdo->exec($sql);
}

function use_energy_drink($acc_id, $pdo)
{
    $energydrink_id = 29;
    $sthandler_energy = $pdo->prepare("SELECT TH_type FROM things WHERE TH_type = :type");
    $sthandler_energy->bindParam(':type', $energydrink_id);
    $sthandler_energy->execute();

    if ($sthandler_energy->rowCount() > 0) {
        $sthandler = $pdo->prepare("SELECT DX_acc_id FROM double_xp WHERE DX_acc_id = :id");
        $sthandler->bindParam(':id', $acc_id);
        $sthandler->execute();

        if ($sthandler->rowCount() > 0) {
            echo feedback("Du har allerede en aktiv energidrikk.", "error");
        } else {
            give_double_exp($acc_id, $pdo);
            remove_energy_drink($acc_id, $pdo);

            if (AS_session_row($acc_id, 'AS_mission', $pdo) == 50) {
                mission_update(AS_session_row($acc_id, 'AS_mission_count', $pdo) + 1, AS_session_row($acc_id, 'AS_mission', $pdo), mission_criteria(AS_session_row($acc_id, 'AS_mission', $pdo)), $acc_id, $pdo);
            }

            echo feedback("Du drakk en energidrikk! Du har nå dobbel exp i 3 timer.", "success");
        }
    } else {
        echo feedback("Du har ingen energidrikk.", "fail");
    }
}

function active_energy_drink($acc_id, $pdo)
{
    $sthandler = $pdo->prepare("SELECT DX_acc_id FROM double_xp WHERE DX_acc_id = :id");
    $sthandler->bindParam(':id', $acc_id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function energy_drink_time($id, $pdo)
{
    $query = $pdo->prepare("SELECT DX_date FROM double_xp WHERE DX_acc_id = ?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['DX_date'] ?? NULL;
}

function get_family_members($family_id, $role, $pdo)
{
    $query = $pdo->prepare("SELECT FAMMEM_acc_id as row FROM family_member WHERE FAMMEM_fam_id = ? AND FAMMEM_role = ?");
    $query->execute(array($family_id, $role));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return $row['row'] ?? NULL;
    } else {
        return false;
    }
}

/**
 * Get all family members, and their roles
 * @param int $family_id Family ID
 * @param mixed $pdo PDO
 * @return array [['FAMMEM_acc_id', FAMMEM_role], [...], [...]]
 */
function get_all_family_members($family_id, $pdo)
{
    $query = $pdo->prepare("SELECT FAMMEM_acc_id, FAMMEM_role from family_member where FAMMEM_fam_id = ?");
    $query->execute([$family_id]);
    $members = $query->fetchAll();

    return $members;
}

function get_rc_price($city, $pdo)
{

    $stmt = $pdo->prepare("SELECT count(*) FROM rc_owner WHERE RCOWN_city = ?");
    $stmt->execute([$city]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $query = $pdo->prepare("SELECT RCOWN_price FROM rc_owner WHERE RCOWN_city = ?");
        $query->execute(array($city));
        $RC_row = $query->fetch(PDO::FETCH_ASSOC);

        return $RC_row['RCOWN_price'] ?? NULL;
    } else {
        return 500000;
    }
}

function race_club_drag($id, $pdo)
{
    $query = $pdo->prepare("SELECT RC_drag FROM race_club WHERE RC_acc_id=?");
    $query->execute(array($id));
    $RC_row = $query->fetch(PDO::FETCH_ASSOC);

    return $RC_row['RC_drag'] ?? NULL;
}

function race_club_drift($id, $pdo)
{
    $query = $pdo->prepare("SELECT RC_drift FROM race_club WHERE RC_acc_id=?");
    $query->execute(array($id));
    $RC_row = $query->fetch(PDO::FETCH_ASSOC);

    return $RC_row['RC_drift'] ?? NULL;
}

function race_club_race($id, $pdo)
{
    $query = $pdo->prepare("SELECT RC_race FROM race_club WHERE RC_acc_id=?");
    $query->execute(array($id));
    $RC_row = $query->fetch(PDO::FETCH_ASSOC);

    return $RC_row['RC_race'] ?? NULL;
}

function train_rc($id, $type, $amount, $pdo)
{
    if ($type == "drift") {
        $sql = "UPDATE race_club SET RC_drift = (RC_drift + $amount) WHERE RC_acc_id='" . $id . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } elseif ($type == "drag") {
        $sql = "UPDATE race_club SET RC_drag = (RC_drag + $amount) WHERE RC_acc_id='" . $id . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } elseif ($type == "race") {
        $sql = "UPDATE race_club SET RC_race = (RC_race + $amount) WHERE RC_acc_id='" . $id . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}

function insert_to_last_race($id, $contestant, $winner, $pdo)
{
    $sql = "INSERT INTO last_race (LR_acc_id, LR_contestant, LR_winner, LR_date) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $contestant, $winner, time()]);
}

$max_rc_owners = 5;
function race_club_ownership($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM rc_owner WHERE RCOWN_acc_id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetchColumn();

    if (!$row) {
        return true;
    } else {
        return false;
    }
}

function race_club_owner_already($id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM rc_owner WHERE RCOWN_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return false;
    } else {
        return true;
    }
}

function race_club_existence($city, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM rc_owner WHERE RCOWN_city = ?');
    $stmt->bindParam(1, $city, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return false;
    } else {
        return true;
    }
}

function race_club_owner($city, $pdo)
{
    $stmt = $pdo->prepare('SELECT RCOWN_acc_id FROM rc_owner WHERE RCOWN_city = ?');
    $stmt->bindParam(1, $city, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return $row['RCOWN_acc_id'] ?? NULL;
    }
}

function race_club_city_owner($id, $pdo)
{
    $stmt = $pdo->prepare('SELECT RCOWN_city FROM rc_owner WHERE RCOWN_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['RCOWN_city'] ?? NULL;
}

function race_club_bank($city, $pdo)
{
    $stmt = $pdo->prepare('SELECT RCOWN_bank FROM rc_owner WHERE RCOWN_city = ?');
    $stmt->bindParam(1, $city, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['RCOWN_bank'] ?? NULL;
}

function get_car_id($garage_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT GA_car_id FROM garage WHERE GA_id = :id");
    $stmt->execute(['id' => $garage_id]);
    $garage = $stmt->fetch();

    return $garage['GA_car_id'] ?? NULL;
}

function remove_car($garage_id, $pdo)
{
    $sql = "DELETE FROM garage WHERE GA_id = $garage_id";
    $pdo->exec($sql);
}

function get_thing_id($thing_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT TH_type FROM things WHERE TH_id = :id");
    $stmt->execute(['id' => $thing_id]);
    $things = $stmt->fetch();

    return $things['TH_type'] ?? NULL;
}

function remove_thing($thing_id, $pdo)
{
    $sql = "DELETE FROM things WHERE TH_id = $thing_id";
    $pdo->exec($sql);
}

function get_random_hk_contestant($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT RC_drift, RC_drag, RC_race FROM race_club WHERE RC_acc_id = :id ORDER BY RAND() LIMIT 1");
    $stmt->execute(['id' => $id]);
    $rc = $stmt->fetch();

    $total_hk = $rc ?? ($rc['RC_drift'] + $rc['RC_drag'] + $rc['RC_race']) / 3;
    return $total_hk;
}

function get_random_rc_acc_id_contestant($id, $pdo)
{
    $stmt = $pdo->prepare("
    SELECT 
        race_club.RC_acc_id
    FROM 
        race_club
    INNER JOIN 
        accounts
    ON 
        race_club.RC_acc_id = accounts.ACC_id
    WHERE NOT
        race_club.RC_acc_id = :id
    AND
        accounts.ACC_type IN (0, 1, 2, 3)
    ORDER BY 
        RAND()
    LIMIT 1");
    $stmt->execute(['id' => $id]);
    $rc = $stmt->fetch();

    if (!$rc) {
        return null;
    } else {
        return $rc['RC_acc_id'] ?? NULL;
    }
}

function amount_of_smugling_things($acc_id, $type, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM smugling WHERE SMUG_acc_id = ? AND SMUG_type = ?");
    $stmt->execute([$acc_id, $type]);
    $count = $stmt->fetchColumn();

    return $count;
}

function charm($type)
{
    $charm[0] = "img/profile/crown.svg";
    $charm[1] = "img/profile/clown.svg";
    $charm[2] = "img/profile/hacker.svg";
    $charm[3] = "img/profile/rocket.svg";
    $charm[4] = "img/profile/logo.svg";
    $charm[5] = "img/profile/hat.svg";
    $charm[6] = "img/profile/love.svg";
    $charm[7] = "img/profile/peace.svg";
    $charm[8] = "img/profile/marijuana.svg";
    $charm[9] = "img/profile/mining.svg";
    $charm[10] = "img/profile/pumpkin.svg";
    $charm[11] = "img/profile/cat.svg";
    $charm[12] = "img/profile/mask_1.svg";
    $charm[13] = "img/profile/mask_2.svg";
    $charm[14] = "img/profile/mask_3.svg";
    $charm[15] = "img/profile/mask_4.svg";
    $charm[16] = "img/profile/mask_5.svg";
    $charm[17] = "img/profile/mask_6.svg";
    $charm[18] = "img/profile/pumpkin1.svg";
    $charm[19] = "img/profile/pumpkin2.svg";
    $charm[20] = "img/profile/pumpkin3.svg";
    $charm[21] = "img/profile/pumpkin4.svg";
    $charm[22] = "img/profile/pumpkin5.svg";
    $charm[23] = "img/profile/pumpkin6.svg";
    $charm[24] = "img/profile/pumpkin7.svg";
    $charm[25] = "img/profile/pumpkin8.svg";
    $charm[26] = "img/profile/pumpkin9.svg";
    $charm[27] = "img/profile/pumpkin10.svg";
    $charm[28] = "img/profile/pumpkin11.svg";
    $charm[29] = "img/profile/pumpkin12.svg";
    $charm[30] = "img/profile/pumpkin13.svg";
    $charm[31] = "img/profile/pumpkin14.svg";
    $charm[32] = "img/profile/pumpkin15.svg";
    $charm[33] = "img/profile/pumpkin16.svg";
    $charm[34] = "img/profile/handcuffs.svg";

    return $charm[$type];
}

function charm_desc($type)
{
    $charm_desc[0] = "Krone";
    $charm_desc[1] = "Klovn";
    $charm_desc[2] = "Betatester";
    $charm_desc[3] = "To the moon";
    $charm_desc[4] = "Mafioso 2";
    $charm_desc[5] = "Detektiv";
    $charm_desc[6] = "Alt er love";
    $charm_desc[7] = "Peace";
    $charm_desc[8] = "Weed";
    $charm_desc[9] = "Mining";
    $charm_desc[10] = "Halloween";
    $charm_desc[11] = "Katt";
    $charm_desc[12] = "maske #1";
    $charm_desc[13] = "maske #2";
    $charm_desc[14] = "maske #3";
    $charm_desc[15] = "maske #4";
    $charm_desc[16] = "maske #5";
    $charm_desc[17] = "maske #6";
    $charm_desc[18] = "Gresskar #1";
    $charm_desc[19] = "Gresskar #2";
    $charm_desc[20] = "Gresskar #3";
    $charm_desc[21] = "Gresskar #4";
    $charm_desc[22] = "Gresskar #5";
    $charm_desc[23] = "Gresskar #6";
    $charm_desc[24] = "Gresskar #7";
    $charm_desc[25] = "Gresskar #8";
    $charm_desc[26] = "Gresskar #9";
    $charm_desc[27] = "Gresskar #10";
    $charm_desc[28] = "Gresskar #11";
    $charm_desc[29] = "Gresskar #12";
    $charm_desc[30] = "Gresskar #13";
    $charm_desc[31] = "Gresskar #14";
    $charm_desc[32] = "Gresskar #15";
    $charm_desc[33] = "Gresskar #16";
    $charm_desc[34] = "Bryt ut Skitzo";

    return $charm_desc[$type];
}

function get_active_charm($id, $pdo)
{
    $query = $pdo->prepare("SELECT CH_charm FROM charm WHERE CH_acc_id = ? AND CH_use = ?");
    $query->execute(array($id, 1));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['CH_charm'] ?? NULL;
}

function give_half_hp($acc_id, $pdo)
{
    $date = time() + 14400;
    $sql = "INSERT INTO half_fp (HALFFP_acc_id, HALFFP_date) VALUES (?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$acc_id, $date]);
}

function half_fp($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM half_fp WHERE HALFFP_acc_id = ?");
    $stmt->execute([$acc_id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return false;
    } else {
        return true;
    }
}

function market_list($seller, $cat, $private, $info, $price, $pdo)
{
    $date = time() + 43200;
    $sql = "INSERT INTO marked (MARKED_seller, MARKED_cat, MARKED_private, MARKED_info, MARKED_price, MARKED_date) VALUES (?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$seller, $cat, $private, $info, $price, $date]);
}

function marked_exist($id, $pdo)
{
    $sthandler = $pdo->prepare("SELECT MARKED_id FROM marked WHERE MARKED_id = :id");
    $sthandler->bindParam(':id', $id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function total_marked_listings($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM marked WHERE MARKED_seller = ?");
    $stmt->execute([$acc_id]);
    $count = $stmt->fetchColumn();

    return $count;
}

function total_marked_listings_user($acc_id, $cat, $pdo)
{

    $total_things = 0;

    $query = $pdo->prepare('SELECT MARKED_info FROM marked WHERE MARKED_seller = :id AND MARKED_cat = :cat');
    $query->execute(array(':id' => $acc_id, ':cat' => $cat));

    foreach ($query as $row) {
        $obj = json_decode($row['MARKED_info']);
        if ($cat == 1) {
            $amount = $obj->amount;
        } else {
            $amount = $obj->car_amount;
        }

        $total_things = $total_things + $amount;
    }

    return $total_things;
}

function marked_listings($cat, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM marked WHERE MARKED_cat = ? AND MARKED_private = ''");
    $stmt->execute([$cat]);
    $count = $stmt->fetchColumn();

    return $count;
}

function GetPrice($from, $to)
{
    $from = (trim(strtoupper($from)));
    $to = (trim(strtoupper($to)));
    $url = 'curl -s -H "CB-VERSION: 2017-12-06" "https://api.coinbase.com/v2/prices/' . $from . '-' . $to . '/spot"';
    $tmp = shell_exec($url);
    $data = json_decode($tmp, true);

    if ($data && $data['data'] && $data['data']['amount']) {
        return (float)$data['data']['amount'];
    }

    return null;
}

function get_detektiv_lvl($id, $pdo)
{
    $query = $pdo->prepare("SELECT DETK_lvl FROM detektiv WHERE DETK_acc_id=?");
    $query->execute(array($id));
    $DETK_row = $query->fetch(PDO::FETCH_ASSOC);

    return $DETK_row['DETK_lvl'] ?? NULL;
}

function get_todays_utfordring($id, $pdo)
{
    $query = $pdo->prepare("SELECT * FROM dagens_utfordring ORDER BY DAUT_date DESC LIMIT 1");
    $query->execute(array());
    $utford = $query->fetch(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT * FROM dagens_utfordring_user WHERE DAUTUS_acc_id = ?");
    $query->execute(array($id));
    $utford_user = $query->fetch(PDO::FETCH_ASSOC);

    $arr = json_decode($utford['DAUT_json']);
    $payout = $utford['DAUT_payout_char'];
    $payout_val = $utford['DAUT_payout_id'];

    if ($utford_user) {
        $amount_arr[0] = $utford_user['DAUTUS_crime'];
        $amount_arr[1] = $utford_user['DAUTUS_gta'];
        $amount_arr[2] = $utford_user['DAUTUS_brekk'];
        $amount_arr[3] = $utford_user['DAUTUS_heist'];
        $amount_arr[4] = $utford_user['DAUTUS_stjel'];
    } else {
        $amount_arr = array(0, 0, 0, 0, 0);
    }


    echo '<div>
        <b style="color: var(--button-bg-color);">Utbetaling: ';
    if ($payout == 0) {
        echo number($payout_val) . " kr";
    } elseif ($payout == 1) {
        echo number($payout_val) . " kuler";
    } elseif ($payout == 2) {
        echo number($payout_val) . " forsvar";
    } elseif ($payout == 3) {
        echo 'Energidrikk';
    } elseif ($payout == 4) {
        echo 'Hemmelig kiste';
    }

    echo '</b></div><br>';

    for ($h = 0; $h < count($arr); $h++) {
        if ($h == 0) {
            $msg = $arr[$h] . "</b> kriminaliteter";
        } elseif ($h == 1) {
            $msg = $arr[$h] . "</b> biltyveri";
        } elseif ($h == 2) {
            $msg = $arr[$h] . "</b> brekk";
        } elseif ($h == 3) {
            $msg = $arr[$h] . "</b> heist";
        } elseif ($h == 4) {
            $msg = $arr[$h] . "</b> stjel fra bruker";
        }

        if ($amount_arr[$h] * (100 / $arr[$h]) < 100) {
            $math = $amount_arr[$h] * (100 / $arr[$h]);
        } else {
            $math = 100;
        }

        echo '<div>Utfør <b style="color: var(--button-bg-color);">' . $msg . '<b style="float: right;">' . $amount_arr[$h] . '/' . $arr[$h] . '</b></div>';

        echo '<div class="wrapper" style="margin-top: 5px;">
			<div class="progress-bar">
				<span class="progress-bar-fill" style="width: ' . $math . '%;"></span>
			</div>
		</div><br>';
    }
}

function update_dagens_utfordring($id, $type, $pdo)
{
    $query = $pdo->prepare("SELECT * FROM dagens_utfordring_user WHERE DAUTUS_acc_id = ?");
    $query->execute(array($id));
    $utford_user = $query->fetch(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT * FROM dagens_utfordring ORDER BY DAUT_date DESC LIMIT 1");
    $query->execute(array());
    $utford = $query->fetch(PDO::FETCH_ASSOC);

    if (!$utford) {
        return; // Missing daily challange in database. Consider handeling this better
    }
    $arr = json_decode($utford['DAUT_json']);

    if (!$utford_user) {
        $sql = "INSERT INTO dagens_utfordring_user (DAUTUS_acc_id) VALUES (?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    if ($type == 0) {
        $sql = "UPDATE dagens_utfordring_user SET DAUTUS_crime = DAUTUS_crime + 1 WHERE DAUTUS_acc_id = ? ";
    } elseif ($type == 1) {
        $sql = "UPDATE dagens_utfordring_user SET DAUTUS_gta = DAUTUS_gta + 1 WHERE DAUTUS_acc_id = ? ";
    } elseif ($type == 2) {
        $sql = "UPDATE dagens_utfordring_user SET DAUTUS_brekk = DAUTUS_brekk + 1 WHERE DAUTUS_acc_id = ? ";
    } elseif ($type == 3) {
        $sql = "UPDATE dagens_utfordring_user SET DAUTUS_heist = DAUTUS_heist + 1 WHERE DAUTUS_acc_id = ? ";
    } elseif ($type == 4) {
        $sql = "UPDATE dagens_utfordring_user SET DAUTUS_stjel = DAUTUS_stjel + 1 WHERE DAUTUS_acc_id = ? ";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    $score = 0;

    if ($utford_user && $arr) {
        $utfordring_arr[0] = $utford_user['DAUTUS_crime'];
        $utfordring_arr[1] = $utford_user['DAUTUS_gta'];
        $utfordring_arr[2] = $utford_user['DAUTUS_brekk'];
        $utfordring_arr[3] = $utford_user['DAUTUS_heist'];
        $utfordring_arr[4] = $utford_user['DAUTUS_stjel'];

        for ($i = 0; $i < count($arr); $i++) {
            if ($arr && $utfordring_arr && $utfordring_arr[$i] >= $arr[$i])
                $score++;
        }
    } else {
        $utfordring_arr = array($score, $score, $score, $score, $score);
    }

    if ($arr && $score >= count($arr) && $utford_user['DAUTUS_status'] == 0) {
        if ($utford['DAUT_payout_char'] == 0) {
            give_money($id, $utford['DAUT_payout_id'], $pdo);
            $text = "Du har vellykket utført dagens utfordring og får " . number($utford['DAUT_payout_id']) . " kr";
        } elseif ($utford['DAUT_payout_char'] == 1) {
            give_bullets($id, $utford['DAUT_payout_id'], $pdo);
            $text = "Du har vellykket utført dagens utfordring og får " . number($utford['DAUT_payout_id']) . " kuler";
        } elseif ($utford['DAUT_payout_char'] == 2) {
            give_fp($utford['DAUT_payout_id'], $id, $pdo);
            $text = "Du har vellykket utført dagens utfordring og får " . number($utford['DAUT_payout_id']) . " forsvarspoeng";
        } elseif ($utford['DAUT_payout_char'] == 3) {
            update_things($id, 29, $pdo);
            $text = "Du har vellykket utført dagens utfordring og får en energidrikk";
        } elseif ($utford['DAUT_payout_char'] == 4) {
            update_things($id, 30, $pdo);
            $text = "Du har vellykket utført dagens utfordring og får en hemmelig skiste";
        }

        send_notification($id, $text, $pdo);

        $sql = "UPDATE dagens_utfordring_user SET DAUTUS_status = 1 WHERE DAUTUS_acc_id = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}

function create_new_utfordring($pdo)
{
    $arr = array();

    for ($i = 0; $i < mt_rand(3, 5); $i++) {
        if ($i == 0) {
            array_push($arr, mt_rand(10, 30));
        } elseif ($i == 1) {
            array_push($arr, mt_rand(10, 20));
        } elseif ($i == 2) {
            array_push($arr, mt_rand(10, 20));
        } elseif ($i == 3) {
            array_push($arr, mt_rand(1, 3));
        } elseif ($i == 4) {
            array_push($arr, mt_rand(10, 20));
        }
    }

    $payout_arr[0] = mt_rand(1000000, 50000000);    // penger
    $payout_arr[1] = mt_rand(1, 15);                // kuler
    $payout_arr[2] = mt_rand(200, 10000);           // forsvar
    $payout_arr[3] = 1;                             // Energidrikk
    $payout_arr[4] = 2;                             // Hemmelig kiste

    $total_score = 0;
    $payout = 0;

    for ($j = 0; $j < count($arr); $j++) {
        $total_score = $total_score + $arr[$j];
    }

    if ($total_score >= 80 && $total_score <= 100) {
        $payout = 4;
    } elseif ($total_score >= 70 && $total_score < 80) {
        $payout = 3;
    } elseif ($total_score >= 60 && $total_score < 70) {
        $payout = 2;
    } elseif ($total_score >= 50 && $total_score < 60) {
        $payout = 1;
    } elseif ($total_score >= 1 && $total_score < 50) {
        $payout;
    }

    $arr = json_encode($arr);

    $sql = "INSERT INTO dagens_utfordring (DAUT_date, DAUT_json, DAUT_payout_char, DAUT_payout_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([time(), $arr, $payout, $payout_arr[$payout]]);
}

function money_by_midnight($money_value, $pdo)
{

    if ($money_value == null) {
        $money_value = 0;
    }

    $money[0] = 25000000; // 25 000 000
    $money[1] = 100000000; // 100 000 000
    $money[2] = 250000000; // 250 000 000
    $money[3] = 500000000; // 500 000 000

    $tenant[0] = 0.1;
    $tenant[1] = 0.07;
    $tenant[2] = 0.05;
    $tenant[3] = 0.025;

    if ($money_value < $money[0]) {
        return round($money_value * $tenant[0]);
    } elseif ($money_value >= $money[0] && $money_value < $money[1]) {
        return round($money_value * $tenant[1]);
    } elseif ($money_value >= $money[1] && $money_value < $money[2]) {
        return round($money_value * $tenant[2]);
    } elseif ($money_value >= $money[2]  && $money_value <= $money[3]) {
        return round($money_value * $tenant[3]);
    } else {
        return round($money_value * 0);
    }
}

function amount_of_ip_register($ip, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM accounts WHERE ACC_ip_register = ?");
    $stmt->execute([$ip]);
    $count = $stmt->fetchColumn();

    return $count;
}

function amount_of_ip_last_active($ip, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM accounts WHERE ACC_ip_latest = ?");
    $stmt->execute([$ip]);
    $count = $stmt->fetchColumn();

    return $count;
}

function get_banned_reason($acc_id, $pdo)
{
    $query = $pdo->prepare("SELECT BAN_reason FROM banned WHERE BAN_acc_id = ?");
    $query->execute(array($acc_id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['BAN_reason'] ?? NULL;
}

function firma_on_marked($type, $city, $pdo)
{
    $firma_json = array('type' => "$type", 'city' => "$city");

    $query = $pdo->prepare("SELECT * FROM marked WHERE MARKED_info = ? AND MARKED_cat = ?");
    $query->execute(array(json_encode($firma_json), 5));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return false;
    } else {
        return true;
    }
}

function get_best_fam($pdo)
{
    $best_fam = null;
    $best_fam_score = 0;

    $sql = "SELECT * FROM family WHERE NOT FAM_name = 'Ledelsen'";
    $stmt = $pdo->query($sql);
    while ($row_fam = $stmt->fetch(PDO::FETCH_ASSOC)) {

        if (family_get_total_score($row_fam['FAM_id'], $pdo) > $best_fam_score) {
            $best_fam_score = family_get_total_score($row_fam['FAM_id'], $pdo);
            $best_fam = $row_fam['FAM_id'];
        }
    }

    return $best_fam ?? NULL;
}

function family_get_total_score($fam_id, $pdo)
{
    $query = $pdo->prepare("SELECT SUM(accounts_stat.AS_daily_exp) as sum_daily_exp
                            FROM family_member
                            INNER JOIN accounts_stat ON family_member.FAMMEM_acc_id = accounts_stat.AS_id
                            WHERE family_member.FAMMEM_fam_id = $fam_id
                            ORDER BY sum_daily_exp DESC");
    $query->execute(array());
    $daily_row = $query->fetch(PDO::FETCH_ASSOC);

    return $daily_row['sum_daily_exp'] ?? NULL;
}

function weapons($weapon_nr)
{
    $weapon[0] = "Glock 19";
    $weapon[1] = "Five Seven";
    $weapon[2] = "Tec-9";
    $weapon[3] = "Desert Eagle";
    $weapon[4] = "MP5-SD";
    $weapon[5] = "CZ75-Auto";
    $weapon[6] = "MP7";
    $weapon[7] = "MAC-10";
    $weapon[8] = "MP5-S";
    $weapon[9] = "UZI";
    $weapon[10] = "MP9";
    $weapon[11] = "M4A4";
    $weapon[12] = "M4A1-S";
    $weapon[13] = "AK-47";
    $weapon[14] = "AUG";
    $weapon[15] = "SSG 08";
    $weapon[16] = "AWP";
    $weapon[17] = "Javelin";
    $weapon[18] = "Tanks";
    $weapon[19] = "Ubåt";

    return $weapon[$weapon_nr];
}

function get_acc_id_without_protection($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT 
    AS_id 
    FROM 
    accounts_stat 
    WHERE 
    AS_protection = :protect 
    AND NOT 
    AS_id = :id 
    ORDER BY 
    RAND() 
    LIMIT 1");
    $stmt->execute(['protect' => 0, 'id' => $id]);
    $user = $stmt->fetch();

    return $user['AS_id'] ?? NULL;
}


function get_forsvar_from_family($forsvar, $role, $amount_of_members)
{
    $half =         $forsvar * .5;
    $twentyfive =   $forsvar * .25;
    $fifteen =      $forsvar * .15;

    if ($role == 0) {
        return $half;
    } elseif ($role == 1) {
        return $twentyfive;
    } elseif ($role == 2) {
        return $fifteen;
    } elseif ($role == 3) {
        return ($forsvar - ($half + $twentyfive + $fifteen)) / $amount_of_members;
    }
}

function total_terning_games($pdo)
{
    $query = $pdo->prepare("SELECT COUNT(*) as count FROM terninger");
    $query->execute(array());
    $KM_row = $query->fetch(PDO::FETCH_ASSOC);

    return $KM_row['count'] ?? NULL;
}



function get_random_thing_id($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT TH_id FROM things WHERE TH_acc_id = :id ORDER BY RAND() LIMIT 1");
    $stmt->execute(['id' => $id]);
    $things = $stmt->fetch();

    return $things['TH_id'] ?? NULL;
}

function get_random_car_id($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT GA_car_id FROM garage WHERE GA_acc_id = :id ORDER BY RAND() LIMIT 1");
    $stmt->execute(['id' => $id]);
    $things = $stmt->fetch();

    return $things['GA_car_id'] ?? NULL;
}

function get_random_garage_id($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT GA_acc_id FROM garage WHERE NOT GA_acc_id = :id ORDER BY RAND() LIMIT 1");
    $stmt->execute(['id' => $id]);
    $garage = $stmt->fetch();

    return $garage['GA_acc_id'] ?? NULL;
}

function kill_user($acc_id, $pdo)
{
    if (family_member_exist($acc_id, $pdo)) {
        if (get_my_familyrole($acc_id, $pdo) == 0) {
            $my_family_id = get_my_familyID($acc_id, $pdo);
            $family_name = get_familyname($my_family_id, $pdo);

            //Send notifikasjon til familiemedlemmer at familien er blitt lagt ned fordi leder døde
            foreach (get_all_family_members($my_family_id, $pdo) as $member) {
                if ($member['FAMMEM_role'] === 0) continue; //Hopp over notifikasjon for leder

                send_notification($member['FAMMEM_acc_id'], "Familien du var medlem i, $family_name ble lagt ned fordi lederen deres, " . username_plain($acc_id, $pdo) . " har blitt drept.", $pdo);
            }

            $sql = "DELETE FROM family_member WHERE FAMMEM_fam_id = $my_family_id";
            $pdo->exec($sql);

            $sql = "DELETE FROM family WHERE FAM_id = $my_family_id";
            $pdo->exec($sql);

            $sql = "DELETE FROM territorium WHERE TE_family_id = " . $my_family_id . "";
            $pdo->exec($sql);
        } else {
            //Send notifikasjon til familiemedlemmer at et familiemedlem døde
            foreach (get_all_family_members(get_my_familyID($acc_id, $pdo), $pdo) as $member) {
                if ($member['FAMMEM_acc_id'] === $acc_id) continue; //Hopp over notifikasjon for den drepte

                send_notification($member['FAMMEM_acc_id'], username_plain($acc_id, $pdo) . " som var medlem av familien din har blitt drept.", $pdo);
            }

            $sql = "DELETE FROM family_member WHERE FAMMEM_acc_id = $acc_id";
            $pdo->exec($sql);
        }
    }

    // Fengsel
    $sql = "DELETE FROM fengsel_direktor WHERE FENGDI_acc_id = $acc_id";
    $pdo->exec($sql);
    // Race Club
    $sql = "DELETE FROM rc_owner WHERE RCOWN_acc_id = $acc_id";
    $pdo->exec($sql);
    // Flyplass
    $sql = "DELETE FROM flyplass WHERE FP_owner = $acc_id";
    $pdo->exec($sql);
    // Kulefabrikk
    $sql = "DELETE FROM kf_owner WHERE KFOW_acc_id = $acc_id";
    $pdo->exec($sql);
    // bj
    $sql = "DELETE FROM blackjack_owner WHERE BJO_owner = $acc_id";
    $pdo->exec($sql);
    // fynn
    $sql = "DELETE FROM marked WHERE MARKED_seller = $acc_id";
    $pdo->exec($sql);
    // Bunker
    shut_down_bunker($acc_id, $pdo);

    // Oppdater ACC-type til 4 = død
    $sql = "UPDATE accounts SET ACC_type = 4 WHERE ACC_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Ta vekk penger fra død
    $sql = "UPDATE accounts_stat SET AS_money = 0 WHERE AS_id='" . $acc_id . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

function cut_string($string)
{

    if (strlen($string) > 20) {
        $string = substr($string, 0, 20);

        return $string . "..";
    } else {
        return $string;
    }
}

function verve_premie($rank, $id, $pdo)
{
    $premie[0] = 0;
    $premie[1] = 2;
    $premie[2] = 4;
    $premie[3] = 6;
    $premie[4] = 10;
    $premie[5] = 15;
    $premie[6] = 20;
    $premie[7] = 25;
    $premie[8] = 30;
    $premie[9] = 50;
    $premie[10] = 75;
    $premie[11] = 100;
    $premie[12] = 150;
    $premie[13] = 200;
    $premie[14] = 350;
    $premie[15] = 500;

    $sthandler = $pdo->prepare("SELECT VERV_acc_id FROM verv WHERE VERV_acc_id = :id");
    $sthandler->bindParam(':id', $id);
    $sthandler->execute();

    if ($sthandler->rowCount() > 0) {
        $stmt = $pdo->prepare("SELECT VERV_by FROM verv WHERE VERV_acc_id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        give_poeng($row['VERV_by'], $premie[$rank], $pdo);
        give_poeng($id, $premie[$rank], $pdo);

        $text = username_plain($id, $pdo) . " som du vervet har ranket opp og derfor får du " . $premie[$rank] . " poeng";
        send_notification($row['VERV_by'], $text, $pdo);

        $text = "Du er vervet av en medspiller og får dermed " . $premie[$rank] . " poeng i rankepremie";
        send_notification($id, $text, $pdo);
    }
}

function points_taken_pr_bullet($weapon)
{
    $points_taken_pr_bullet[0] = 20;
    $points_taken_pr_bullet[1] = 25;
    $points_taken_pr_bullet[2] = 30;
    $points_taken_pr_bullet[3] = 35;
    $points_taken_pr_bullet[4] = 40;
    $points_taken_pr_bullet[5] = 45;
    $points_taken_pr_bullet[6] = 50;
    $points_taken_pr_bullet[7] = 55;
    $points_taken_pr_bullet[8] = 60;
    $points_taken_pr_bullet[9] = 65;
    $points_taken_pr_bullet[10] = 70;
    $points_taken_pr_bullet[11] = 75;
    $points_taken_pr_bullet[12] = 80;
    $points_taken_pr_bullet[13] = 85;
    $points_taken_pr_bullet[14] = 90;
    $points_taken_pr_bullet[15] = 95;
    $points_taken_pr_bullet[16] = 100;
    $points_taken_pr_bullet[17] = 200;
    $points_taken_pr_bullet[18] = 300;
    $points_taken_pr_bullet[19] = 400;

    return $points_taken_pr_bullet[$weapon];
}

function last_event($id, $text, $pdo)
{
    $sql = "INSERT INTO last_events (LAEV_user, LAEV_text, LAEV_date) VALUES (?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $text, time()]);
}

function total_crimes_today($pdo)
{
    $query = $pdo->prepare("SELECT sum(DAUTUS_crime) as count FROM dagens_utfordring_user");
    $query->execute();
    $row_ver = $query->fetch(PDO::FETCH_ASSOC);

    return $row_ver['count'] ?? NULL;
}

function total_brekk_today($pdo)
{
    $query = $pdo->prepare("SELECT sum(DAUTUS_brekk) as count FROM dagens_utfordring_user");
    $query->execute();
    $row_ver = $query->fetch(PDO::FETCH_ASSOC);

    return $row_ver['count'] ?? NULL;
}

function total_gta_today($pdo)
{
    $query = $pdo->prepare("SELECT sum(DAUTUS_gta) as count FROM dagens_utfordring_user");
    $query->execute();
    $row_ver = $query->fetch(PDO::FETCH_ASSOC);

    return $row_ver['count'] ?? NULL;
}

function active_vip($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT CB_vip FROM cashback WHERE CB_acc_id = ? AND CB_vip = ?");
    $stmt->execute([$acc_id, 1]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return false;
    } else {
        return true;
    }
}

function give_cashback($acc_id, $amount, $pdo)
{
    if (!active_vip($acc_id, $pdo)) {
        $amount = $amount * .0001;
    } else {
        $amount = $amount * .0005;
    }

    $amount = floor($amount);

    $stmt = $pdo->prepare("SELECT * FROM cashback WHERE CB_acc_id = ?");
    $stmt->execute([$acc_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $sql = "INSERT INTO cashback (CB_acc_id, CB_saldo) VALUES (?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$acc_id, $amount]);
    } else {
        $sql = "UPDATE cashback SET CB_saldo = CB_saldo + ? WHERE CB_acc_id = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$amount, $acc_id]);
    }
}

/*
* $side = ' garasje '
* $side = ' lager '
*/
function stjel_sjanse_beskyttelse($id, $side, $pdo)
{
    $boost[0] = 10;
    $boost[1] = 10;
    $boost[2] = 15;
    $boost[3] = 15;
    $boost[4] = 20;
    $boost[5] = 30;

    if ($side == 'garasje') {
        $stmt = $pdo->prepare("SELECT BESK_garasje FROM beskyttelse WHERE BESK_acc_id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $total_beskyttelse = 0;
            for ($i = 0; $i <= $row['BESK_garasje']; $i++) {
                $total_beskyttelse = $total_beskyttelse + $boost[$i];
            }

            return $total_beskyttelse;
        } else {
            return 10;
        }
    } else {
        $stmt = $pdo->prepare("SELECT BESK_ting FROM beskyttelse WHERE BESK_acc_id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $total_beskyttelse = 0;
            for ($i = 0; $i <= $row['BESK_ting']; $i++) {
                $total_beskyttelse = $total_beskyttelse + $boost[$i];
            }

            return $total_beskyttelse;
        } else {
            return 10;
        }
    }
}

function update_konk($site, $count, $acc_id, $pdo)
{
    $date = time();

    $stmt = $pdo->prepare("SELECT count(*) FROM konk WHERE KONK_from <= ? AND KONK_to >= ? AND KONK_site = ?");
    $stmt->execute([$date, $date, $site]);
    $active_sh = $stmt->fetchColumn();

    if ($active_sh == 0) {
        return false;
    } else {
        $stmt = $pdo->prepare("SELECT KU_acc_id FROM konk_user WHERE KU_acc_id = :id");
        $stmt->execute(['id' => $acc_id]);
        $row = $stmt->fetch();

        if ($row) {
            $sql = "UPDATE konk_user SET KU_count = KU_count + ? WHERE KU_acc_id = ? ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$count, $acc_id]);
        } else {
            $sql = "INSERT INTO konk_user (KU_acc_id, KU_count) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$acc_id, $count]);
        }
    }
}

function active_konk($pdo)
{
    $date = time();

    $stmt = $pdo->prepare("SELECT count(*) FROM konk WHERE KONK_from <= ? AND KONK_to >= ?");
    $stmt->execute([$date, $date]);
    $active_sh = $stmt->fetchColumn();

    if ($active_sh == 0) {
        return false;
    } else {
        return true;
    }
}

function bunker_owner_already($id, $pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM bunker_owner WHERE BUNOWN_acc_id = ?');
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return !$row ? false : true;
}

function player_in_bunker($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM bunker_active WHERE BUNACT_acc_id = ? AND BUNACT_status = ?");
    $stmt->execute([$acc_id, 1]);
    $count = $stmt->fetchColumn();

    return $count == 0 ? false : true;
}

function time_left_in_bunker($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT BUNACT_cooldown FROM bunker_active WHERE BUNACT_acc_id = ?");
    $stmt->execute([$acc_id]);
    $row = $stmt->fetch();

    return $row['BUNACT_cooldown'] ?? NULL;
}

function mission_criteria($mission_nr)
{
    $mission_criteria[0] = 1;
    $mission_criteria[1] = 1;
    $mission_criteria[2] = 25;
    $mission_criteria[3] = 25;
    $mission_criteria[4] = 25;
    $mission_criteria[5] = 1;
    $mission_criteria[6] = 1;
    $mission_criteria[7] = 100;
    $mission_criteria[8] = 5;
    $mission_criteria[9] = 1;
    $mission_criteria[10] = 3;
    $mission_criteria[11] = 100000000;
    $mission_criteria[12] = 150;
    $mission_criteria[13] = 5;
    $mission_criteria[14] = 1;
    $mission_criteria[15] = 130000;
    $mission_criteria[16] = 100;
    $mission_criteria[17] = 1;
    $mission_criteria[18] = 20;
    $mission_criteria[19] = 1;
    $mission_criteria[20] = 2000000;
    $mission_criteria[21] = 250000;
    $mission_criteria[22] = 50000000;
    $mission_criteria[23] = 30;
    $mission_criteria[24] = 100000000;
    $mission_criteria[25] = 5;
    $mission_criteria[26] = 4;
    $mission_criteria[27] = 10;
    $mission_criteria[28] = 30;
    $mission_criteria[29] = 2500000000;
    $mission_criteria[30] = 500000000;
    $mission_criteria[31] = 1000000000;
    $mission_criteria[32] = 20;
    $mission_criteria[33] = 5;
    $mission_criteria[34] = 13;
    $mission_criteria[35] = 10;
    $mission_criteria[36] = 25;
    $mission_criteria[37] = 100;
    $mission_criteria[38] = 1000000000;
    $mission_criteria[39] = 5;
    $mission_criteria[40] = 150000000;
    $mission_criteria[41] = 200;
    $mission_criteria[42] = 100;
    $mission_criteria[43] = 1;
    $mission_criteria[44] = 100;
    $mission_criteria[45] = 25;
    $mission_criteria[46] = 200;
    $mission_criteria[47] = 50;
    $mission_criteria[48] = 5;
    $mission_criteria[49] = 10;
    $mission_criteria[50] = 20;
    $mission_criteria[51] = 2;
    $mission_criteria[52] = 5;
    $mission_criteria[53] = 10;

    return $mission_criteria[$mission_nr];
}

function mission_update($mission_count, $mission_nr, $mission_objective, $id, $pdo)
{
    $query = $pdo->prepare("SELECT AS_mission_count, AS_mission FROM accounts_stat WHERE AS_id=?");
    $query->execute(array($id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row['AS_mission'] != 0) {
        $sql = "UPDATE accounts_stat SET AS_mission_count = ? WHERE AS_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mission_count, $id]);
    } else {
        $sql = "UPDATE accounts_stat SET AS_mission_count = ? WHERE AS_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([1, $id]);
    }
}

/** 
 * @param $bedrift_type=0 = Flyplass
 * @param $bedrift_type=1 = Kulefabrikk
 * @param $bedrift_type=2 = Fengsel
 * @param $bedrift_type=3 = Streetrace
 * @param $bedrift_type=4 = Blackjack
 * @param $bedrift_type=5 = bunker
 */
function update_bedrift_inntekt($acc_id, $bedrift_type, $city, $money, $pdo)
{
    $date = time();

    $sql = "INSERT INTO bedrift_inntekt (BEIN_acc_id, BEIN_bedrift_type, BEIN_city, BEIN_money, BEIN_date) VALUES (?,?,?,?,?)";
    $pdo->prepare($sql)->execute([$acc_id, $bedrift_type, $city, $money, $date]);
}

function get_max_cashback($vip)
{
    return !$vip ? 200000000 : 1000000000;
}

function get_cashback_saldo($id, $pdo)
{
    $stmt = $pdo->prepare("SELECT CB_saldo FROM cashback WHERE CB_acc_id = :id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row['CB_saldo'] ?? NULL;
}

function bunker_owner_exist($city, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM bunker_owner WHERE BUNOWN_city = ?");
    $stmt->execute([$city]);
    $count = $stmt->fetchColumn();

    $exist = $count == 0 ? false : true;
    return $exist;
}

function bunker_owner($city, $pdo)
{
    $stmt = $pdo->prepare('SELECT BUNOWN_acc_id FROM bunker_owner WHERE BUNOWN_city = ?');
    $stmt->bindParam(1, $city, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $row['BUNOWN_acc_id'] : NULL;
}

function get_bunker_price($id, $pdo)
{
    $query = $pdo->prepare("SELECT BUNCHO_acc_id, BUNCHO_bunkerID FROM bunker_chosen WHERE BUNCHO_city = ? AND BUNCHO_acc_id = ?");
    $query->execute(array(AS_session_row($id, 'AS_city', $pdo), $id));
    $row_buncho = $query->fetch(PDO::FETCH_ASSOC);

    if ($row_buncho) {
        $query = $pdo->prepare("SELECT BUNOWN_price FROM bunker_owner WHERE BUNOWN_id = ?");
        $query->execute(array($row_buncho['BUNCHO_bunkerID']));
        $row_bunker_owner = $query->fetch(PDO::FETCH_ASSOC);

        $price_for_bunker_use = $row_bunker_owner['BUNOWN_price'];
    } else {
        $price_for_bunker_use = 500000;
    }

    return $price_for_bunker_use;
}

/**
 * Stenger alle eventuelle bunkere en spiller eier
 * Flytter spillere som bruker den til statens + sender varsel til disse
 * @param int ACC_ID
 * @param PDO pdo
 * @return int Antall bunkere som ble lagt ned
 */
function shut_down_bunker($owner_id, $pdo)
{
    $bunkers_shut_down = 0;

    // Få info om bunker
    $sql = "SELECT * FROM bunker_owner WHERE BUNOWN_acc_id = $owner_id";
    foreach ($pdo->query($sql) as $bunker) { // NOTE: Denne skal i utganspunktet maksimalt inneholde en bunker enn så lenge det kun er tillatt å eie en bunker
        // Finn eventuelle brukere av bunkeren
        $query = $pdo->prepare('SELECT BUNCHO_acc_id FROM bunker_chosen WHERE BUNCHO_city = :city');
        $query->execute(array(':city' => $bunker['BUNOWN_city']));

        // For hver eventuelle bunker bruker
        foreach ($query as $bunker_user) {
            // Send varsel til brukere at de er blitt flytta til statens bunker
            send_notification(
                $bunker_user['BUNCHO_acc_id'],
                'Din valgte bunker i ' . city_name($bunker['BUNOWN_city']) . ' er satt til Statens siden  ' . username_plain($owner_id, $pdo) . ' sin bunker har blitt lagt ned.',
                $pdo
            );

            // Slett brukeres valg om å benytte denne bunkeren
            $sql = "DELETE FROM bunker_chosen WHERE BUNCHO_city = " . $bunker['BUNOWN_city'] . " AND BUNCHO_acc_id = " . $bunker_user['BUNCHO_acc_id'] . "";
            $pdo->exec($sql);
        }

        // Slett eier av bunker
        $pdo->exec("DELETE FROM bunker_owner WHERE BUNOWN_acc_id = $owner_id AND BUNOWN_city = " . $bunker['BUNOWN_city']);

        // Inkremer antall bunkere som har blitt lagt ned
        $bunkers_shut_down++;
    }

    return $bunkers_shut_down;
}

function has_liked($forum_id, $id, $pdo)
{
    $stmt = $pdo->prepare("SELECT FLIKES_acc_id FROM forum_likes WHERE FLIKES_forum_id = :forum_id AND FLIKES_type = :forum_type AND FLIKES_acc_id = :acc_id");
    $stmt->execute(['forum_id' => $forum_id, 'forum_type' => 0, 'acc_id' => $id]);
    return $stmt->fetch() ? true : false;
}

function blocked($acc_id, $acc_id_second, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM block WHERE (BL_acc_id = :id AND BL_acc_id_blocked = :id_second) OR (BL_acc_id_blocked = :id AND BL_acc_id = :id_second)");
    $stmt->execute(['id' => $acc_id, 'id_second' => $acc_id_second]);
    return $stmt->fetch() ? true : false;
}

function rigg_stabil($psu, $motherboard, $fan, $gpu)
{
    if ($psu == 0 || $motherboard == 0 || $fan == 0 || $gpu == 0) {
        return false;
    } else {
        $stable = (
            (($motherboard / $gpu) >= 0.2)
            &&
            (($fan / $gpu) >= 2)
            &&
            (($psu / $motherboard) >= 1)
            ?
            true
            :
            false
        );

        return $stable;
    }
}

function user_log($acc_id, $page, $handling, $pdo)
{
    $query = $pdo->prepare("SELECT AS_money, AS_bankmoney, AS_exp, AS_city FROM accounts_stat WHERE AS_id=?");
    $query->execute(array($acc_id));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    $money = $row['AS_money'];
    $bank_money = $row['AS_bankmoney'];
    $exp = $row['AS_exp'];
    $city = $row['AS_city'];

    $sql = "INSERT INTO user_log (UL_acc_id, UL_money_hand, UL_money_bank, UL_exp, UL_city, UL_page, UL_handling, UL_date) VALUES (?,?,?,?,?,?,?,?)";
    $pdo->prepare($sql)->execute([$acc_id, $money, $bank_money, $exp, $city, $page, $handling, time()]);
}

function total_krypto($acc_id, $pdo)
{
    $total_in_crypto = 0;

    $query = $pdo->prepare('SELECT * FROM crypto_user WHERE CRU_acc_id = :id');
    $query->execute(array(':id' => $acc_id));
    foreach ($query as $row) {
        $stmt = $pdo->prepare("SELECT SUC_price FROM supported_crypto WHERE SUC_id = :suc_id");
        $stmt->execute(['suc_id' => $row['CRU_crypto']]);
        $row_crypto = $stmt->fetch();

        $total_in_crypto = $total_in_crypto + ($row['CRU_amount'] * $row_crypto['SUC_price']);
    }

    return $total_in_crypto;
}

function total_in_btc($acc_id, $pdo)
{
    $total_in_crypto = 0;
    $total_in_btc = 0;

    $query = $pdo->prepare('SELECT * FROM crypto_user WHERE CRU_acc_id = :id');
    $query->execute(array(':id' => $acc_id));
    foreach ($query as $row) {
        $stmt = $pdo->prepare("SELECT SUC_price FROM supported_crypto WHERE SUC_id = :suc_id");
        $stmt->execute(['suc_id' => $row['CRU_crypto']]);
        $row_crypto = $stmt->fetch();

        $stmt = $pdo->prepare("SELECT SUC_price FROM supported_crypto WHERE SUC_ticker = :suc_ticker");
        $stmt->execute(['suc_ticker' => 'BTC']);
        $BTC_price = $stmt->fetch();

        $total_in_crypto = $row && $row_crypto && $total_in_crypto + ($row['CRU_amount'] * $row_crypto['SUC_price']);
        $total_in_btc = $total_in_crypto / $BTC_price['SUC_price'];
    }

    return $total_in_btc;
}

function in_shout_queue($pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM shout_queue");
    $stmt->execute([]);
    return $stmt->fetchColumn();
}

function last_shout($pdo)
{
    $query = $pdo->prepare("SELECT SH_date FROM shout ORDER BY SH_date DESC LIMIT 1");
    $query->execute(array());
    $SH_row = $query->fetch(PDO::FETCH_ASSOC);

    return $SH_row['SH_date'] ?? NULL;
}

function newest_shout($pdo)
{
    $query = $pdo->prepare("SELECT SH_acc_id, SH_message FROM shout ORDER BY SH_date DESC");
    $query->execute();
    $SH_row = $query->fetch(PDO::FETCH_ASSOC);

    return $SH_row ? ": <span style='color: #e8e8e8'>" . $SH_row['SH_message'] . "</span>" : '';
}

function newest_shout_user($pdo)
{
    $query = $pdo->prepare("SELECT SH_acc_id, SH_message FROM shout ORDER BY SH_date DESC");
    $query->execute();
    $SH_row = $query->fetch(PDO::FETCH_ASSOC);

    return $SH_row['SH_acc_id'] ?? NULL;
}

function newest_shout_date($pdo)
{
    $query = $pdo->prepare("SELECT SH_date FROM shout ORDER BY SH_date DESC");
    $query->execute();
    $SH_row = $query->fetch(PDO::FETCH_ASSOC);

    return $SH_row['SH_date'] ?? NULL;
}

function forum_cat(int $i, $useLang)
{
    $title[0] = $useLang->forum->general;
    $title[1] = $useLang->forum->sell;
    $title[2] = $useLang->forum->offTopic;
    $title[3] = $useLang->forum->family;

    $title[4] = $useLang->forum->picmaking;
    $title[5] = $useLang->forum->help;
    $title[6] = $useLang->forum->suggestions;

    return $title[$i];
}

function forum_cat_desc(int $i, $useLang)
{
    $desc[0] = $useLang->forum->generalDesc;
    $desc[1] = $useLang->forum->sellDesc;
    $desc[2] = $useLang->forum->offTopicDesc;
    $desc[3] = $useLang->forum->familyDesc;

    $desc[4] = $useLang->forum->picmakingDesc;
    $desc[5] = $useLang->forum->helpDesc;
    $desc[6] = $useLang->forum->suggestionsDesc;

    return $desc[$i];
}
