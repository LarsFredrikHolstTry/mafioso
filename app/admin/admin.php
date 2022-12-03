<?php

if (!isset($_GET['side'])) {
    die();
}

if(isset($_GET['adminside'])){
    $whitelistedPage = $_GET['adminside'] == 'forum_rediger' || $_GET['adminside'] == 'forum_slett';
}

if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) >= 1 || $whitelistedPage) {

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
    $pages[18] = "oppdrag_oversikt";
    $pages[19] = "drapsfri";
    $pages[20] = "fotballVm";

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
                </div>
            </div>
        <?php } ?>
    </div>
<?php  } else {
    echo feedback("Ingen adgang", "error");
} ?>