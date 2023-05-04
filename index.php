<?php

ob_start();

$language = 'no';

$json = file_get_contents('lang/' . $language . '.json');

$useLang = json_decode($json);

include_once 'env.php';
include_once 'functions/feedbacks.php';
include_once 'functions/oppdrag.php';
include_once 'functions/functions.php';
include_once 'functions/dates.php';
include_once 'functions/statistics.php';
include_once 'functions/cooldown.php';
include_once 'functions/admin.php';
include_once 'auth.php';
// include_once 'constructions/check_everytime.php';

if (isset($_GET['side'])) {
    $side = "- " . $_GET['side'];
} else {
    $side = null;
}



$theme[0] = 'css/root_style_blue.css';
$theme[1] = 'css/root_style_grey.css';
$theme[2] = 'css/root_style_pink.css';
$theme[3] = 'css/root_style_green.css';
$theme[4] = 'css/root_style_halloween.css';

$colors[0] = "Blå";
$colors[1] = "Grå";
$colors[2] = "Lilla";
$colors[3] = "Grønn";
$colors[4] = "Superdark";

$color_code[0] = "#252836";
$color_code[1] = "#262626";
$color_code[2] = "#352536";
$color_code[3] = "#263625";
$color_code[4] = "black";

$diamondStart = DateTime::createFromFormat('d-m-Y H:i:s', '15-05-2022 12:00:00')->getTimestamp();
$diamondEnd = DateTime::createFromFormat('d-m-Y H:i:s', '24-06-2022 18:00:00')->getTimestamp();

$activeDiamondEvent = time() >= $diamondStart && time() <= $diamondEnd;

$theme_type = AS_session_row($_SESSION['ID'], 'AS_theme', $pdo);

$starttime = microtime(true);

$isEaster = date('m') == 3 && date('d') == 31 || date('m') == 4 && date('d') == 1;
$isTheEnd = date('m') == 5 && date('d') > 7;


?>

<html lang="nb">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-32" />
    <title>

        Mafioso <?php

                echo str_replace("_", " ", $side);

                ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="css/styling.css?<?php echo time(); ?>">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <!-- GLOBALE DELTE VARIABLER / KONSTANTER FOR JS OG PHP -->
    <script>
        const currentPage = "?side=<?php echo $_GET['side']; ?>";
    </script>
    <script src="js/number.js"></script>

    <link rel="shortcut icon" type="image/jpg" href="img/favicon-32x32.png" />

    <link rel="stylesheet" href="css/uicons-regular-rounded/css/uicons-regular-rounded.css">

    <style>
        <?php include $theme[$theme_type]; ?>
    </style>
</head>

<body onload="startTime(); startDate();">
<?php 

if($isTheEnd){
    echo "<center style='color: white; margin-top: 50px;'>I'll be back <3</center>";
} else {

?>
    <div class="main_container">
        <?php

        if (isset($_POST['bunker_out'])) {
            user_log($_SESSION['ID'], 'bunker', "Går ut av bunker", $pdo);
            $sql = "DELETE FROM bunker_active WHERE BUNACT_acc_id = " . $_SESSION['ID'] . "";
            $pdo->exec($sql);

            user_log($_SESSION['ID'], 'bunker', "Ute av bunker", $pdo);
            header("Location: ?side=drap&p=bunker&bunker_status=out");
        }

        $new_player = false;

        if(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo) < 5000){
            $new_player = true;
        }

        include 'constructions/left.php';

        ?>
        <div class="container">

            <?php

            include 'constructions/mobile/mobile_top.php';
            include 'constructions/mobile/bottom_navigation.php';
            include 'constructions/top_bar.php';

            if (isset($_POST['bunker_in'])) {
                if (player_in_jail($_SESSION['ID'], $pdo)) {
                    echo feedback($useLang->bunker->leaveBunkerInJail, 'error');
                } else {
                    user_log($_SESSION['ID'], 'bunker', "Går inn i bunker", $pdo);
                    $waittime = 86400 + time();

                    $query = $pdo->prepare("SELECT BUNCHO_acc_id, BUNCHO_bunkerID FROM bunker_chosen WHERE BUNCHO_city = ? AND BUNCHO_acc_id = ?");
                    $query->execute(array(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $_SESSION['ID']));
                    $row_buncho = $query->fetch(PDO::FETCH_ASSOC);

                    $price_for_bunker_use = get_bunker_price($_SESSION['ID'], $pdo);

                    if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= $price_for_bunker_use) {
                        $sql = "INSERT INTO bunker_active (BUNACT_acc_id, BUNACT_status, BUNACT_cooldown) VALUES (?,?,?)";
                        $pdo->prepare($sql)->execute([$_SESSION['ID'], 1, $waittime]);

                        $sql = "INSERT INTO bunker_log (BUNK_acc_id, BUNK_in, BUNK_city) VALUES (?,?,?)";
                        $pdo->prepare($sql)->execute([$_SESSION['ID'], time(), AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                        if ($row_buncho) {
                            update_bedrift_inntekt($_SESSION['ID'], 5, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $price_for_bunker_use, $pdo);

                            if ($price_for_bunker_use != 500000) {
                                $sql = "UPDATE bunker_owner SET BUNOWN_bank = BUNOWN_bank + ? WHERE BUNOWN_city = ? ";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$price_for_bunker_use, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);
                            }
                        }

                        take_money($_SESSION['ID'], $price_for_bunker_use, $pdo);

                        user_log($_SESSION['ID'], 'bunker', "Gikk inn i bunker", $pdo);
                        header("Location: ?side=drap&p=bunker&bunker_status=in");
                    } else {
                        user_log($_SESSION['ID'], 'bunker', "Ikke nok penger for bunker", $pdo);
                        header("Location: ?side=drap&p=bunker&bunker_status=notEnoughMoney");
                    }
                }
            }

            if (isset($_GET['velkommen'])) {
                echo feedback($useLang->index->registered, 'success');
            }

            if (!user_exist(username_plain($_SESSION['ID'], $pdo), $pdo) || AS_session_row($_SESSION['ID'], 'AS_health', $pdo) == 0 || ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) == 4 || ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) == 5) {
                include 'loggut.php';

                header("Location: login.php");
            }

            if (ACC_status($_SESSION['ID'], $pdo) == 0) { ?>
                <div class="col-5 single">
                    <div class="content">
                        <?php echo $useLang->index->notVerified; ?>
                        <br><br>
                        <?php echo feedback($useLang->index->emailInJunk, 'blue'); ?>
                    </div>
                </div>
            <?php

            } elseif (ACC_status($_SESSION['ID'], $pdo) == 100) { ?>
                <div class="col-5 single">
                    <div class="content">
                        Brukeren din er midlertidig låst. Dette kan skyldes mistanke om bruk av tredjepartsprogrammer som autoclicker, eller misbrukelse av bug.
                    </div>
                </div>
            <?php

            } else {
                $legal_sites = array('flyplass', 'banken', 'lager', 'diamantHeist', 'admin', 'online', 'nyheter', 'statistikk', 'poeng', 'konk', 'support', 'forum_oversikt', 'forum', 'siste_handlinger', 'varsel', 'innboks', '');
                if (AS_session_row($_SESSION['ID'], 'AS_city', $pdo) == 5 && !in_array($_GET['side'], $legal_sites)) {
                    if (!in_array($_GET['side'], $legal_sites)) {
                        echo feedback('Du har kun tilgang til banken, lageret, diamantgruve og flyplassen i Abu Dhabi', 'blue');
                    }
                    include 'app/gruve/gruve.php';
                } else {

                    if (isset($_GET['side'])) {
                        $filename = "app/" . $_GET['side'] . "/" . $_GET['side'] . ".php";
                        if (file_exists($filename)) {
                            include "app/" . $_GET['side'] . "/" . $_GET['side'] . ".php";
                        } else {
                            echo feedback($useLang->error->pageNotFound, 'blue');
                        }
                    } else {
                        include 'app/hjem/hjem.php';
                    }
                }
            }
            ?>
            <?php include 'footer.php'; ?>

        </div>
        <?php include 'constructions/right.php';
        ?>
    </div>
    <?php } ?>
</body>

</html>