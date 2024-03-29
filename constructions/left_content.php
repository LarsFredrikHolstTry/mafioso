<?php

$query = $pdo->prepare("SELECT CTAX_tax, CTAX_city FROM city_tax ORDER BY CTAX_tax ASC LIMIT 1");
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);

$lowest_tax = $row['CTAX_tax'] ?? 0;
$lowest_tax_city = $row['CTAX_city'] ?? 0;

$notInLowestCity = $lowest_tax_city != AS_session_row($_SESSION['ID'], 'AS_city', $pdo) && get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > $lowest_tax;

$query = $pdo->prepare("SELECT FIRM_acc_id FROM firma WHERE FIRM_collected = 0 AND FIRM_acc_id = " . $_SESSION['ID'] . "");
$query->execute();
$row_firm = $query->fetch(PDO::FETCH_ASSOC);

$payout = false;

if ($row_firm) {
    $payout = true;
}

?>

<div class="user_container">
    <a href="?side=profil&id=<?php echo $_SESSION['ID']; ?>"><img id="left_avatar" src="<?php echo AS_session_row($_SESSION['ID'], 'AS_avatar', $pdo); ?>">
    </a>
    <ul class="user_ul">
        <li class="df aic"><i title="Brukernavn" class="mgr-12 fi fi-rr-user"></i> <a style="color: <?= role_colors(ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo), $_SESSION['ID'], $pdo) ?>" href="?side=profil&id=<?php echo $_SESSION['ID']; ?>"><?php echo username_plain($_SESSION['ID'], $pdo); ?></a></li>
        <li class="df aic"><i title="Rank" class="mgr-12 fi fi-rr-trophy"></i> <a href="#"><?php echo rank_list(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo)); ?></a></li>
        <li class="df aic"><i title="Penger" class="mgr-12 fi fi-rr-dollar"></i> <a id="saldo_left" href="?side=banken"><?php echo str_replace('{value}', number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo)), $useLang->index->money); ?></a></li>
        <li class="df aic"><i title="By" class="mgr-12 fi fi-rr-building"></i> <a href="?side=flyplass">
                <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?>
                (<?php echo output_city_tax(get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?>)
            </a></li>

        <li class="df aic"><i title="Forsvar" class="mgr-12 fi fi-rr-shield"></i> <a href="?side=drap&p=fp">
                <?php

                if (family_member_exist($_SESSION['ID'], $pdo)) {

                    $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);

                    $addon = get_forsvar_from_family(
                        get_family_defence($my_family_id, $pdo),
                        get_my_familyrole($_SESSION['ID'], $pdo),
                        total_family_members($my_family_id, $pdo)
                    );
                } else {
                    $addon = 0;
                }

                $half_hp = false;
                $total = AS_session_row($_SESSION['ID'], 'AS_def', $pdo) + $addon;

                if (half_fp($_SESSION['ID'], $pdo)) {
                    $half_hp = true;
                    $total = $total / 2;
                }


                echo number($total) . ' stk';

                ?>
            </a>
            <?php

            if ($half_hp) {
                echo '<span class="chip chip_error" style="margin-left: 10px">Halvvert</span>';
            }

            ?>
        </li>
        <li class="df aic"><i title="Poeng" class="mgr-12 fi fi-rr-diamond"></i> <a href="?side=poeng">
                <?php echo str_replace('{value}', number(AS_session_row($_SESSION['ID'], 'AS_points', $pdo)), $useLang->index->gems); ?>
            </a></li>


        <?php if ($prodOrDev == 'dev') { ?>
            <li class="df aic"><i class="mgr-12 fi fi-rr-database"></i>
                <a target="_blank" href='http://localhost/phpmyadmin/index.php?route=/database/structure&server=1&db=<?php echo $db_name ?>'>
                    DB: <?= $db_name ?>
                </a>
            </li>
        <?php } ?>

    </ul>
</div>

<ul class="left_ul">
<?php

//Garasje og lager antall / maxantall
//Garasje
$cars_in_garage = total_cars($_SESSION['ID'], $pdo);
$max_garage = US_max_cars($_SESSION['ID'], $pdo);
$full_garage = $cars_in_garage >= $max_garage;
//Lager
$things_in_storage = total_things($_SESSION['ID'], $pdo);
$max_storage = US_max_things($_SESSION['ID'], $pdo);
$full_storage = $things_in_storage >= $max_storage;

?>

    <?php if (has_eiendeler($_SESSION['ID'], $pdo)) { ?>
        <a href="?side=bedrift">
            <li <?php if ($side == 'bedrift') {
                    echo 'class="active"';
                } ?>>
                <?php echo $useLang->action->myBusiness; ?>
            </li>
        </a>
    <?php } ?>

    <a href="?side=banken">
        <li <?php if ($side == 'banken') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->bank; ?>
            <span id="saldo_top" class="chip chip_blue">
                <?php
                echo str_replace('{value}', big_number_format(AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo), 3), $useLang->index->money);
                ?>
            </span>
        </li>
    </a>

    <a href="?side=marked">
        <li <?php if ($side == 'marked') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->market; ?>
            <?php $sales = 0;
            for ($i = 0; $i < 7; $i++) {
                $sales = $sales + marked_listings($i, $pdo);
            }

            if ($sales == 1) {
                $amount = ' nytt salg';
            } else {
                $amount = ' nye salg';
            }
            if ($sales > 0) {
                echo pill(number($sales) . ' ' . $amount, 'success');
            }
            ?>
        </li>
    </a>
    <a href="?side=firma">
        <li <?php if ($side == 'firma') {
                echo 'class="active"';
            } ?>>Firma
            <?php if ($payout) {
                echo pill('Hent penger', 'success');
            } ?>

        </li>
    </a>
</ul>
<ul class="left_ul">
    <?=
    $prodOrDev == 'dev' ? '
        <a href="?side=experimental">
        <li>Experimental</li>
    </a>
    ' : ''
    ?>
    <a href="?side=garasje">
        <li <?php if ($side == 'garasje') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->garage; ?>
            <?php $thisPill = ($full_garage ? 'error' : 'blue');
            echo pill(number($cars_in_garage) . " / " . number($max_garage), $thisPill) ?>
        </li>
    </a>
    <a href="?side=lager">
        <li <?php if ($side == 'lager') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->storageUnit; ?>
            <?php $thisPill = ($full_storage ? 'error' : 'blue');
            echo pill(number($things_in_storage) . " / " . number($max_storage), $thisPill) ?>
        </li>
    </a>
    <a href="?side=flyplass">
        <li <?php if ($side == 'flyplass') {
                echo 'class="active"';
            } ?>>
            <?php echo $useLang->action->airport; ?>
            <?php if ($notInLowestCity) { ?>
                <a href="?side=flyplass&reise=<?= $lowest_tax_city ?>" class="chip blue chip_button">Reis til <?= city_name($lowest_tax_city) ?> (<?= $lowest_tax ?>% skatt)</a>
            <?php } ?>
        </li>
    </a>
    <a href="?side=smugling">
        <li <?php if ($side == 'smugling') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->drugSmuggling; ?></li>
    </a>
    <a href="?side=oppdrag">
        <li <?php if ($side == 'oppdrag') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->mission; ?>
            <?php
            echo AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) + 1;
            if (in_array(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), $missions_numberz)) {

                if (in_array(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), $missions_with_generic_values, true)) {
                    $value = get_mission_value($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), $pdo);
                } else {
                    $value = AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo);
                }

                echo pill(big_number_format($value, 2) . " av " . big_number_format(mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), 2)), 'blue'); ?>
            <?php } ?>
        </li>
    </a>
    <a href="?side=hurtig_oppdrag">
        <li <?php if ($side == 'hurtig_oppdrag') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->quest; ?></li>
    </a>
    <a href="?side=aksjemarked">
        <li <?php if ($side == 'aksjemarked') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->stockMarket; ?></li>
    </a>
    <a href="?side=familie">
        <li <?php if ($side == 'familie') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->family; ?>
            <?php

            if (family_member_exist($_SESSION['ID'], $pdo)) {
                echo " - " . get_my_familyname($_SESSION['ID'], $pdo);

                $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);

                if (get_my_familyrole($_SESSION['ID'], $pdo) != 3) {
                    if (total_applicants($my_family_id, $pdo) > 0) {
                        echo "<span style='color: var(--ready-color)'> (" . total_applicants($my_family_id, $pdo) . ") </span>";
                    }
                }
            }
            ?></li>
    </a>
    <a href="?side=dagensSafe">
        <li <?php if ($side == 'dagensSafe') {
                echo 'class="active"';
            } ?>>Dagens safe

            <?php

            $stmt = $pdo->prepare("SELECT * FROM safe_number");
            $stmt->execute();
            $row = $stmt->fetch();

            $hasWinner = false;

            if($row){
                $hasWinner = $row['SAFE_winner'] != 0;
            }

            $stmt_ = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
            $stmt_->execute(array(
                ':cd_id' => $_SESSION['ID']
            ));
            $row_ = $stmt_->fetch(PDO::FETCH_ASSOC);

            $time_left_safe_ = $row_['CD_safe'] - time();

            if ($time_left_safe_ <= 0 && !$hasWinner) {
                echo pill($useLang->misc->ready, 'success');
            }

            ?>

        </li>
    </a>
</ul>
<ul class="left_ul">
    <a href="?side=drap">
        <li <?php if ($side == 'drap' && (isset($_GET['p']) && $_GET['p'] != "skytetrening")) {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->murderOrganization; ?></li>
    </a>
    <a href="?side=drap&p=skytetrening">
        <li <?php if (isset($_GET['p']) && $_GET['p'] == "skytetrening") {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->shootingPractice; ?>
            <?php

            $stmt_ = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
            $stmt_->execute(array(
                ':cd_id' => $_SESSION['ID']
            ));
            $row_ = $stmt_->fetch(PDO::FETCH_ASSOC);

            $time_left_weapon_ = $row_['CD_weapon'] - time();

            if ($time_left_weapon_ <= 0 && AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo) != 17) {
                echo pill($useLang->misc->ready, 'success');
            }

            ?>

        </li>
    </a>
</ul>
<ul class="left_ul last" style="padding-bottom: 200px;">
    <a href="?side=casino">
        <li <?php if ($side == 'casino') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->casino; ?></li>
    </a>
    <a href="?side=lotto">
        <li <?php if ($side == 'lotto') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->lotto; ?>

            <?php if (lotto_jackpot($pdo) > 0) {

                $jackpot = lotto_total_coupon($pdo) * 4500;
                echo pill(str_replace('{value}', big_number_format($jackpot, 0), $useLang->index->money), 'blue');
            } ?></li>
    </a>
    <a href="?side=kast_mynt">
        <li <?php if ($side == 'kast_mynt') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->tossCoins; ?>
            <?php if (total_kast_mynt_games($pdo) >= 1) {
                echo pill(total_kast_mynt_games($pdo) . ' spill', 'blue');
            }
            ?></li>
    </a>
    <a href="?side=terninger">
        <li <?php if ($side == 'terninger') {
                echo 'class="active"';
            } ?>><?php echo $useLang->action->dices; ?>
            <?php if (total_terning_games($pdo) >= 1) {
                echo pill(total_terning_games($pdo) . ' spill', 'blue');
            } ?>
        </li>
    </a>
</ul>