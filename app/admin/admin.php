<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_POST['refund'])) {

    $sql = "SELECT * FROM crypto_rigs";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $total_money = 0;

        $gpu = $row['CRR_gpu'] * 850000;
        $motherboard = $row['CRR_motherboard'] * 100000;
        $fan = $row['CRR_fan'] * 20000;
        $psu = $row['CRR_psu'] * 50000;

        if ($row['CRR_pig']) {
            give_poeng($row['CRR_acc_id'], 500, $pdo);
            send_notification($row['CRR_acc_id'], "Du hadde sparegris-miner på en kryptominer og får derfor 250 tilbake siden funksjonen fjernes.", $pdo);
        }

        $total_money = $gpu + $motherboard + $fan + $psu;

        give_money($row['CRR_acc_id'], $total_money, $pdo);
        send_notification($row['CRR_acc_id'], "Du hadde kryptorigg og får derfor " . number($total_money) . " tilbake siden funksjonen fjernes.", $pdo);
    }
}

if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) >= 1) {

    $pages[0] = "nyhet";
    $pages[1] = "cronjobs";
    $pages[2] = "spillere";
    $pages[3] = "ip_sjekk";
    $pages[4] = "support";
    $pages[5] = "ban_spiller";
    $pages[6] = "wiki_endre";
    $pages[7] = "wiki_slett";
    $pages[8] = "wiki_new";
    $pages[9] = "2x_helg";
    $pages[10] = "heatmap_krim";
    $pages[11] = "edit_session";
    $pages[12] = "send_varsel";
    $pages[13] = "konk";
    $pages[14] = "forum_slett";
    $pages[15] = "forum_steng";
    $pages[16] = "send_varsel_alle";
    $pages[17] = "forum_rediger";
    $pages[18] = "new_crypto";
    $pages[19] = "crypto_oversikt";
    $pages[20] = "oppdrag_oversikt";
    $pages[21] = "drapsfri";

    $admin = array(1, 11);
    $forummod = array(1, 2, 3, 5, 11);

?>
    <div class="col-12 single">
        <?php

        if (isset($_GET['adminside'])) {
            $filename = "app/admin/" . $_GET['adminside'] . ".php";
            if (file_exists($filename)) {
                include("app/admin/" . $_GET['adminside'] . ".php");
            } else {
                echo feedback('Siden eksisterer ikke eller er under utvikling', "blue");
            }
        } else {

        ?>
            <div class="col-7">
                <div class="content">

                    <h3>Adminpanel</h3>
                    <?php for ($i = 0; $i < count($pages); $i++) {
                        if (in_array($i, $admin) && ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) == 2) {
                        } elseif (in_array($i, $forummod) && ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) == 1) {
                        } else {
                    ?>
                            <div class="col-3">
                                <a href="?side=admin&adminside=<?php echo $pages[$i]; ?>">
                                    <?php echo str_replace("_", " ", $pages[$i]);
                                    if ($i == 4 && total_support_unread($pdo) > 0) {
                                        echo ': ' . total_support_unread($pdo) . '';
                                    } ?>
                                </a>
                            </div>
                    <?php }
                    } ?>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <div class="col-5">
                <div class="content">
                    <h4>Statistikk</h4>
                    <ul>
                        <li>Penger i spillet: <?php echo number(admin_total_money_out($pdo)); ?> kr</li>
                        <li>Penger i banken: <?php echo number(admin_total_money_bank($pdo)); ?> kr</li>
                        <li>Penger totalt: <?php echo number(admin_total_money_bank($pdo) + admin_total_money_out($pdo)); ?> kr</li>
                        <li>Poeng totalt: <?php echo number(poeng_total($pdo)); ?></li>

                    </ul>
                    <?php if ($_SESSION['ID'] === 1) { ?>
                        <form method="post">
                            <input type="submit" name="refund" value="Refund crypto">
                        </form>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php  } else {
    echo feedback("Ingen adgang", "error");
} ?>