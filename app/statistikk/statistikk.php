<?php

include 'functions/blackjack.php';

if(isset($_GET['my_top_10'])){
    
    $i = 1;

    $sql = 'SELECT 
        accounts_stat.AS_id, (accounts_stat.AS_money + accounts_stat.AS_bankmoney) as total_money
    FROM 
        accounts_stat, accounts
    WHERE
        accounts_stat.AS_id = accounts.ACC_id
    AND 
        accounts.ACC_type = 0
    ORDER BY total_money DESC
    ';
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
        if($row['AS_id'] == $_SESSION['ID']){
            echo feedback("Du er på ". $i . ". plass med mest penger", "blue");
        }

        $i++;
     } 
    } 

    if(isset($_GET['my_top_10_rank'])){
    
        $i = 1;
    
        $sql = 'SELECT
        accounts_stat.AS_id, (accounts_stat.AS_exp + 0) as total_exp
        FROM 
        accounts_stat, accounts
        WHERE
        accounts_stat.AS_id = accounts.ACC_id
        AND 
        accounts.ACC_type = 0
        ORDER BY total_exp DESC
        ';
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        
            if($row['AS_id'] == $_SESSION['ID']){
                echo feedback("Du er på ". $i . ". plass med mest høyest rank", "blue");
            }
    
            $i++;
         } 
        } 

?>

<div class="col-9 single">
    <div class="content">
        <div class="col-12 single">
            <h3 style="text-align: center;"><?php echo $useLang->statistics->header ?></h3>
            <br>
            <table>
                <tr>
                    <td><?php echo $useLang->statistics->userAmount ?></td>
                    <td><?php echo number(total_users($pdo)); ?> stk</td>
                </tr>
                <tr>
                    <td><?php echo $useLang->statistics->aliveAmount ?></td>
                    <td><?php echo number(total_users($pdo) - total_users_dead($pdo)); ?> stk</td>
                </tr>
                <tr>
                    <td><?php echo $useLang->statistics->deadAmount ?></td>
                    <td><?php echo number(total_users_dead($pdo)); ?> stk</td>
                </tr>
                <tr>
                    <td><?php echo $useLang->statistics->totalMoney ?></td>
                    <td><?php 
                    $total = admin_total_money_out($pdo) 
                    + admin_total_money_bank($pdo) 
                    + family_money($pdo) 
                    + kf_money($pdo)
                    + fengsel_money($pdo)
                    + rc_money($pdo)
                    + flyplass_money($pdo)
                    + swiss_money($pdo)
                    + bunker_money($pdo)
                    + bj_money($pdo)
                    + crypto_money($pdo);

                    echo str_replace('{value}', number($total), $useLang->index->money);

                    ?></td>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <th>Prestasjon</th>
                    <th>Bruker</th>
                </tr>
                <tr>
                    <td>Flest utbrytninger:</td>
                    <td><?php

                        $stmt = $pdo->prepare('
                        SELECT 
                            user_statistics.US_acc_id, user_statistics.US_jail
                        FROM 
                            user_statistics
                        INNER JOIN 
                            accounts
                        ON 
                            user_statistics.US_acc_id = accounts.ACC_id
                        WHERE NOT
                            accounts.ACC_type IN (4, 5)
                        ORDER BY 
                            user_statistics.US_jail DESC
                        LIMIT 1
                        ');
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo ACC_username($row['US_acc_id'], $pdo) . ' <span class="description">' . number($row['US_jail']) . ' stk</span>';

                        ?></td>
                </tr>
                <tr>
                    <td>Flest vellykkede kriminalitet:</td>
                    <td><?php

                        $stmt = $pdo->prepare('
                        SELECT 
                            user_statistics.US_acc_id, (user_statistics.US_krim_v + 0) as total_krim
                        FROM 
                            user_statistics
                        INNER JOIN 
                            accounts
                        ON 
                            user_statistics.US_acc_id = accounts.ACC_id
                        WHERE NOT
                            accounts.ACC_type IN (4, 5)
                        ORDER BY 
                            total_krim DESC
                        LIMIT 1');
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo ACC_username($row['US_acc_id'], $pdo) . ' <span class="description">' . number($row['total_krim']) . ' stk</span>';

                        ?></td>
                </tr>
                <tr>
                    <td>Flest vellykkede biltyveri:</td>
                    <td><?php

                        $stmt = $pdo->prepare('
                        SELECT 
                            user_statistics.US_acc_id, (user_statistics.US_gta_v + 0) as total_gta
                        FROM 
                            user_statistics
                        INNER JOIN 
                            accounts
                        ON 
                            user_statistics.US_acc_id = accounts.ACC_id
                        WHERE NOT
                            accounts.ACC_type IN (4, 5)
                        ORDER BY 
                            total_gta DESC
                        LIMIT 1');
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo ACC_username($row['US_acc_id'], $pdo) . ' <span class="description">' . number($row['total_gta']) . ' stk</span>';

                        ?></td>
                </tr>
                <tr>
                    <td>Flest vellykkede brekk:</td>
                    <td><?php

                        $stmt = $pdo->prepare('
                        SELECT 
                            user_statistics.US_acc_id, (user_statistics.US_brekk_v + 0) as total_brekk
                        FROM 
                            user_statistics
                        INNER JOIN 
                            accounts
                        ON 
                            user_statistics.US_acc_id = accounts.ACC_id
                        WHERE NOT
                            accounts.ACC_type IN (4, 5)
                        ORDER BY 
                        total_brekk DESC
                        LIMIT 1');
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo ACC_username($row['US_acc_id'], $pdo) . ' <span class="description">' . number($row['total_brekk']) . ' stk</span>';

                        ?></td>
                </tr>
                <tr>
                    <td>Flest hestekrefter:</td>
                    <td><?php

                        $stmt = $pdo->prepare('
                        SELECT 
                        race_club.RC_acc_id, ((RC_drift + RC_drag + RC_race) / 3) as total_hk
                        FROM 
                            race_club
                        INNER JOIN 
                            accounts
                        ON 
                            race_club.RC_acc_id = accounts.ACC_id
                        WHERE NOT
                            accounts.ACC_type IN (4, 5)
                        ORDER BY 
                            total_hk DESC
                        LIMIT 1');
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        echo ACC_username($row['RC_acc_id'], $pdo) . ' <span class="description">' . number($row['total_hk']) . ' HK</span>';

                        ?></td>
                </tr>
            </table>
            <br>
            <style>
                th,
                td {
                    padding: 8px;
                }
            </style>

            <div class="col-6">
                <div style="display: flex; justify-content: space-between">
                <h4 style="padding-bottom: 10px;">Topp 10 rikest</h4>
                <a href="?side=statistikk&my_top_10" class="small_header">Sjekk min posisjon</a>
            </div>
                <table>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th>Brukernavn</th>
                        <th>Pengerank</th>
                    </tr>
                    <?php

                    $i = 1;

                    $sql = 'SELECT 
                        accounts_stat.AS_id, (accounts_stat.AS_money + accounts_stat.AS_bankmoney) as total_money
                    FROM 
                        accounts_stat, accounts
                    WHERE
                        accounts_stat.AS_id = accounts.ACC_id
                    AND 
                        accounts.ACC_type = 0
                    ORDER BY total_money DESC
                    LIMIT 10
                    ';
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <tr>
                            <td style="color:rgba(255,255,255, .5);"><?php echo $i; ?>.</td>
                            <td><?php echo ACC_username($row['AS_id'], $pdo); ?></td>
                            <td><?php echo money_rank($row['total_money'], $pdo); ?></td>
                        </tr>
                    <?php $i++;
                    } ?>
                </table>
            </div>
            <div class="col-6">
            <div style="display: flex; justify-content: space-between">
                <h4 style="padding-bottom: 10px;">Topp 10 rank</h4>
                <a href="?side=statistikk&my_top_10_rank" class="small_header">Sjekk min posisjon</a>
            </div>
                <table>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>Brukernavn</th>
                        <th>Rank</th>
                    </tr>
                    <?php

                    $i = 1;

                    $sql = 'SELECT 
accounts_stat.AS_id, (accounts_stat.AS_exp + 0) as total_exp
FROM 
accounts_stat, accounts
WHERE
accounts_stat.AS_id = accounts.ACC_id
AND 
accounts.ACC_type = 0
ORDER BY total_exp DESC
LIMIT 10';
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <tr>
                            <td style="color:rgba(255,255,255, .5);"><?php echo $i; ?>.</td>
                            <td><?php echo ACC_username($row['AS_id'], $pdo); ?></td>
                            <td><?php echo rank_list(get_rank_from_exp($row['total_exp'])); ?></td>
                        </tr>
                    <?php $i++;
                    } ?>
                </table>
            </div>

            <div style="clear: both;"></div>

            <h4 style="margin-bottom: 10px;">By-oversikt</h4>
            <div style="overflow:auto !important;">
                <table style="border-collapse: collapse !important; border-spacing: 0 !important; width: 100% !important;">
                    <tr>
                        <th></th>
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <th>
                                <?php
                                echo country_flags($i) . ' ' . city_name($i); ?>
                            </th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="background-color: rgba(255,255,255, .05);">Byskatt</td>
                        <?php for ($a = 0; $a < 5; $a++) { ?>
                            <td>
                                <?php echo output_city_tax(get_city_tax($a, $pdo)); ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="background-color: rgba(255,255,255, .05);">Territorium</td>
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <td>
                                <?php if (get_city_owner($i, $pdo)) {
                                    echo '<a href="?side=familieprofil&id=' . get_city_owner($i, $pdo) . '">' . get_familyname(get_city_owner($i, $pdo), $pdo) . '</a>';
                                } else {
                                    echo '<span class="description">Ingen</span>';
                                } ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="background-color: rgba(255,255,255, .05);">Flyplass</td>
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <td>
                                <?php
                                if (firma_on_marked(0, $i, $pdo)) {
                                    echo '<a href="?side=marked&id=5">Marked</a>';
                                } elseif (airport_existence($i, $pdo)) {
                                    echo '<a href="?side=profil&id=' . airport_owner($i, $pdo) . '">' . ACC_username(airport_owner($i, $pdo), $pdo) . '</a>';
                                } else {
                                    echo '<span class="description">Ingen</span>';
                                } ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="background-color: rgba(255,255,255, .05);">Kulefabrikk</td>
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <td>
                                <?php
                                if (firma_on_marked(1, $i, $pdo)) {
                                    echo '<a href="?side=marked&id=5">Marked</a>';
                                } elseif (kf_owner_exist($i, $pdo)) {
                                    echo ACC_username(get_kf_owner($i, $pdo), $pdo);
                                } else {
                                    echo '<span class="description">Ingen</span>';
                                }
                                ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="background-color: rgba(255,255,255, .05);">Fengsel</td>
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <td>
                                <?php
                                if (firma_on_marked(2, $i, $pdo)) {
                                    echo '<a href="?side=marked&id=5">Marked</a>';
                                } elseif (fengsel_direktor_exist($i, $pdo)) {
                                    echo ACC_username(get_fengselsdirektor($i, $pdo), $pdo);
                                } else {
                                    echo '<span class="description">Ingen</span>';
                                } ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="background-color: rgba(255,255,255, .05);">Streetrace</td>
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <td>
                                <?php
                                if (firma_on_marked(3, $i, $pdo)) {
                                    echo '<a href="?side=marked&id=5">Marked</a>';
                                } elseif (race_club_existence($i, $pdo)) {
                                    echo ACC_username(race_club_owner($i, $pdo), $pdo);
                                } else {
                                    echo '<span class="description">Ingen</span>';
                                }
                                ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="background-color: rgba(255,255,255, .05);">Blackjack</td>
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <td>
                                <?php
                                if (firma_on_marked(4, $i, $pdo)) {
                                    echo '<a href="?side=marked&id=5">Marked</a>';
                                } elseif (blackjack_owner_exist($i, $pdo)) {
                                    echo ACC_username(blackjack_owner_incity($i, $pdo), $pdo);
                                } else {
                                    echo '<span class="description">Ingen</span>';
                                }
                                ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="background-color: rgba(255,255,255, .05);">Bunker</td>
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <td>
                                <?php
                                if (firma_on_marked(5, $i, $pdo)) {
                                    echo '<a href="?side=marked&id=5">Marked</a>';
                                } elseif (bunker_owner_exist($i, $pdo)) {
                                    echo ACC_username(bunker_owner($i, $pdo), $pdo);
                                } else {
                                    echo '<span class="description">Ingen</span>';
                                }
                                ?>
                            </td>
                        <?php } ?>
                    </tr>
                </table>
            </div>
            <br>
            <h4 style="margin-bottom: 10px;"><?php echo $useLang->statistics->family->header ?></h4>
            <table id="family_table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th><?php echo $useLang->statistics->family->expRanked ?></th>
                        <th><?php echo $useLang->statistics->family->familyName ?></th>
                        <th><?php echo $useLang->statistics->family->members ?></th>
                        <th><?php echo $useLang->statistics->family->boss ?></th>
                        <th><?php echo $useLang->statistics->family->underboss ?></th>
                        <th><?php echo $useLang->statistics->family->advisor ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $max_members = 20;
                    $i = 1;

                    $sql = "
                    SELECT 
                    family_member.FAMMEM_acc_id, family.FAM_id, family.FAM_name, family_member.FAMMEM_fam_id, accounts_stat.AS_id, SUM(accounts_stat.AS_daily_exp) as total_exp
                FROM 
                    family, family_member, accounts_stat
                WHERE 
                    family.FAM_id = family_member.FAMMEM_fam_id
                AND 
                    family_member.FAMMEM_acc_id = accounts_stat.AS_id
                GROUP BY 
                    family.FAM_id
                ORDER BY 
                    total_exp DESC
                    ";
                    $stmt = $pdo->query($sql);
                    while ($row_fam = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $query = $pdo->prepare("SELECT * FROM family_member WHERE FAMMEM_fam_id=? AND FAMMEM_role = ?");
                        $query->execute(array($row_fam['FAM_id'], 0));
                        $ACC_leader = $query->fetch(PDO::FETCH_ASSOC);

                        $query = $pdo->prepare("SELECT * FROM family_member WHERE FAMMEM_fam_id=? AND FAMMEM_role = ?");
                        $query->execute(array($row_fam['FAM_id'], 1));
                        $ACC_direktor = $query->fetch(PDO::FETCH_ASSOC);

                        $query = $pdo->prepare("SELECT * FROM family_member WHERE FAMMEM_fam_id=? AND FAMMEM_role = ?");
                        $query->execute(array($row_fam['FAM_id'], 2));
                        $ACC_radgiver = $query->fetch(PDO::FETCH_ASSOC);

                    ?>

                        <tr>
                            <td style="color:rgba(255,255,255, .5);"><?php echo $i; ?>.</td>
                            <td><?php echo number($row_fam['total_exp']); ?> EXP</td>
                            <td><a href="?side=familieprofil&id=<?php echo $row_fam['FAM_id']; ?>"><?php echo $row_fam['FAM_name']; ?></a></td>
                            <td><a href="?side=familie_medlemmer&id=<?php echo $row_fam['FAM_id'] ?>"><?php echo total_family_members($row_fam['FAM_id'], $pdo); ?> / <?php echo $max_members; ?></a></td>
                            <td><?php echo ACC_username($ACC_leader['FAMMEM_acc_id'], $pdo); ?></td>
                            <td><?php if($ACC_direktor && $ACC_direktor['FAMMEM_acc_id']){ echo ACC_username($ACC_direktor['FAMMEM_acc_id'], $pdo); } else { echo '<span style="color: rgb(170, 180, 190);">Ingen</span>'; } ?></td>
                            <td><?php if($ACC_radgiver && $ACC_radgiver['FAMMEM_acc_id']){ echo ACC_username($ACC_radgiver['FAMMEM_acc_id'], $pdo); } else { echo '<span style="color: rgb(170, 180, 190);">Ingen</span>'; } ?></td>
                        </tr>
                    <?php $i++;
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var $tbody = $('#family_table tbody');
    $tbody.find('tr').sort(function(a, b) {
        var tda = $(a).find('td:eq(0)').number();
        var tdb = $(b).find('td:eq(0)').number();
        return tda < tdb ? 1 :
            tda > tdb ? -1 :
            0;
    }).appendTo($tbody);
</script>