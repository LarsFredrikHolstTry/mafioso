<?php

ob_start();
include '../db_cred/db_cred.php';
include 'functions/feedbacks.php';
include 'functions/functions.php';
include 'functions/admin.php';

$cookie_name_remember = "remember_forever";
if (!isset($_COOKIE[$cookie_name_remember])) {

?>
    <style>
        <?php include 'css/root_style_blue.css'; ?>
    </style>

    <html>

    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="stylesheet" href="css/styling.css?<?php echo time(); ?>">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <title>Mafioso - Logg inn</title>
        <link rel="shortcut icon" type="image/jpg" href="img/favicon-32x32.png" />
        <style>
            html,
            body {
                background-image: url("img/login_bg.png");
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
        </style>
    </head>

    <body>

        <?php

        if (isset($_GET['username_exist'])) {
            echo feedback("Brukernavnet eksisterer allerede. Logg inn på nytt og velg et nytt brukernavn", "error");
        }

        if (isset($_GET['ver'])) {
            echo feedback("Brukeren er nå aktivert! Ha en hyggelig spill-opplevelse.", "success");
        }

        if (isset($_GET['act'])) {
            if ($_GET['act'] == "loggut") {
                echo feedback("Du logget ut av Mafioso. Velkommen tilbake! 👋", "blue");
            }
        }

        if (isset($_GET['new_user'])) {
            $select_stmt = $pdo->prepare("SELECT * FROM accounts WHERE ACC_id = :id");
            $select_stmt->execute(array(':id' => $_GET['new_user']));
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

            $select_stmt = $pdo->prepare("SELECT BOACC_acc_id FROM boost_account WHERE BOACC_acc_id = :id");
            $select_stmt->execute(array(':id' => $_GET['new_user']));
            $boosted = $select_stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['ACC_type'] != 4) {
                echo feedback("Boost kan ikke brukes på levende brukere", "error");
            } elseif ($boosted) {
                echo feedback("Det er allerede registrert ny bruker på denne brukeren.", "error");
            } else {

                if (isset($_POST['register_new'])) {
                    $new_username = $_POST['new_username'];
                    if (user_exist($new_username, $pdo)) {
                        echo feedback("Brukernavnet eksistrer allerede", "error");
                    } else {
                        $ip_register = getUserIpAddr();
                        $exp_from_old_user = floor(AS_session_row($_GET['new_user'], 'AS_exp', $pdo));
                        $new_rank = get_rank_from_exp(floor($exp_from_old_user * .1));

                        $insert_stmt = $pdo->prepare("
                    INSERT INTO
                    accounts
                    (ACC_username, ACC_mail, ACC_password, ACC_register_date, ACC_ip_register)
                    VALUES
                    (:uname,:uemail,:upassword,:uregister,:ip_register)");

                        if ($insert_stmt->execute(array(
                            ':uname' => $new_username,
                            ':uemail' => $row['ACC_mail'],
                            ':upassword' => $row['ACC_password'],
                            ':uregister' => time(),
                            'ip_register' => $ip_register
                        ))) {
                            $stmt = $pdo->query("SELECT LAST_INSERT_ID() FROM accounts");
                            $lastId = $stmt->fetchColumn();
                            $random_string = generateRandomString(25);

                            $sql = "INSERT INTO verification (VER_acc_id, VER_hash)
                            VALUES ('$lastId', '$random_string')";
                            $pdo->exec($sql);

                            $sql = "INSERT INTO accounts_stat (AS_id, AS_exp, AS_rank, AS_health, AS_money, AS_points, AS_city, AS_weapon)
                        VALUES ('$lastId', '" . floor($exp_from_old_user * .1) . "', '" . $new_rank . "', '100', '250000000', '50', '" . mt_rand(0, 4) . "', '11')";
                            $pdo->exec($sql);

                            $sql = "INSERT INTO cooldown (CD_acc_id)
                        VALUES ('$lastId')";
                            $pdo->exec($sql);

                            $sql = "INSERT INTO user_statistics (US_acc_id)
                        VALUES ('$lastId')";
                            $pdo->exec($sql);

                            $sql = "INSERT INTO race_club (RC_acc_id)
                        VALUES ('$lastId')";
                            $pdo->exec($sql);

                            $sql = "INSERT INTO boost_account (BOACC_acc_id)
                        VALUES ('" . $_GET['new_user'] . "')";
                            $pdo->exec($sql);

                            for ($i = 0; $i < 5; $i++) {
                                $thing_id = 29;
                                update_things($lastId, $thing_id, $pdo);
                            }

                            update_things($lastId, 30, $pdo);

                            $to = $row['ACC_mail'];
                            $subject = 'Velkommen til Mafioso V2 🚀';
                            $message = '<h3 style="font-size: 21px;" >Mafioso <span style="border-radius: 1px; font-size: 14px; background-color: #80EAFF; color: #3377FF; padding: 0px 8px;">V2</span></h3>
    <p>Dersom du er en ny bruker trenger du å aktivere din email med å gå inn på denne linken: <a href="https://mafioso.no/verifikasjon.php?v=' . $random_string . '">https://mafioso.no/verifikasjon.php?v=' . $random_string . '</a></p>
    <p>Om du har spørsmål så send disse til support@mafioso.no</p>
    <p>Spørsmål som blir svart fra denne mailen blir ikke besvart</p>';
                            $headers = 'From: Mafioso <noreply@mafioso.no>\r\n';
                            $headers .= 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                            mail($to, $subject, $message, $headers);

                            // Logg inn
                            $_SESSION['ID'] = $lastId;
                            header("Location: index.php");
                        }
                    }
                }
        ?>
                <div class="col-5 single login_container" style="margin-top: -10px;">
                    <div class=" login_content">
                        <img class="action_image" src="img/action/actions/drept.png">
                        <p class="description">
                            Du har blitt drept av en annen mafioso.
                            Dersom du ønsker kan du lage en ny bruker og
                            passordet blir det samme som din forrige bruker.
                            <?php if (AS_session_row($row['ACC_id'], 'AS_rank', $pdo) >= 5) { ?>
                                Du vil få dette i boost dersom du registrer deg:
                        <ul class="description" style="margin-bottom: 20px;">
                            <li><?php echo number(AS_session_row($row['ACC_id'], 'AS_exp', $pdo) * 0.1) ?> EXP</li>
                            <li><?php echo number(250000000) ?> kroner</li>
                            <li>50 poeng</li>
                            <li>5 energidrikker</li>
                            <li>1 hemmelig kiste</li>
                            <li>M4A4 som startvåpen</li>
                        </ul>
                    <?php } ?>

                    <form method="post">
                        <input type="text" name="new_username" placeholder="Nytt brukernavn">
                        <input type="submit" name="register_new" value="registrer">
                    </form>
                    </div>
                </div>
        <?php }
        }

        if (isset($_POST['login'])) {
            $username = strip_tags($_POST["username"]);
            $password = strip_tags($_POST["password"]);
            $username = strtolower($username);

            if ((empty($password)) && (empty($username))) {
                echo feedback("Vennligst legg inn brukernavn og passord", "error");
            } else if (empty($username)) {
                echo feedback("Vennligst legg inn brukernavn", "error");
            } else if (empty($password)) {
                echo feedback("Vennligst legg inn passord", "error");
            } else {
                try {
                    $select_stmt = $pdo->prepare("SELECT * FROM accounts WHERE LOWER(ACC_username) = LOWER(:uname)");
                    $select_stmt->execute(array(':uname' => $username));
                    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

                    if ($select_stmt->rowCount() > 0) {
                        if (password_verify($password, $row["ACC_password"])) {
                            if ($row['ACC_type'] == 4 || $row['ACC_type'] == 5) {
                                if ($row['ACC_type'] == 4) {
                                    header("Location: login.php?new_user=" . $row['ACC_id']);
                                } else {
                                    echo feedback("Du er blitt deaktivert. Begrunnelse: " . get_banned_reason($row['ACC_id'], $pdo), "error");
                                }
                            } else {

                                if (isset($_POST['radio']) && $_POST['radio'] == 'log_in_forever') {
                                    $random_string = generateRandomString(50);

                                    $sql = "INSERT INTO keep_me_login (KMLI_hash, KMLI_acc_id) VALUES (?,?)";
                                    $pdo->prepare($sql)->execute([$random_string, $row["ACC_id"]]);

                                    setcookie(
                                        "remember_forever",
                                        $random_string,
                                        time() + (10 * 365 * 24 * 60 * 60)
                                    );
                                }

                                $_SESSION["ID"] = $row["ACC_id"];
                                header("Location: index.php");
                            }
                        } else {
                            echo feedback("Feil passord", "error");
                        }
                    } else {
                        echo feedback("Brukernavnet eksisterer ikke", "error");
                    }
                } catch (PDOException $e) {
                    $e->getMessage();
                }
            }
        }

        ?>

        <div class="col-5 single login_container">
            <div class="login_content">
                <div class="col-6">
                    <img src="img/favicon-32x32.png">
                    <br><br>
                    <h4 style="color: #087FEF">Har du det som trengs?</h4>
                    <p>For å bli en mektig mafia er det viktig at du tar smarte valg og ikke lar deg påvirke av andre mafiosoer.</p>
                    <p>Dersom du mener du har det som trengs kan du registrere deg på høyre og ta utfordringen.</p>
                    <br>
                    <p>Anbefalt aldersgrense: <span style="font-weight: bolder; padding: 2px; background-color: black;">15+</span></p>
                </div>
                <div class="col-6">
                    <form method="post">
                        <h4>Logg inn</h4>
                        <div class="col-6">
                            <p style="margin-bottom: 0px;">Brukernavn</p>
                            <input type="text" placeholder="Brukernavn" name="username" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="col-6">
                            <p style="margin-bottom: 0px;">Passord</p>
                            <input type="password" placeholder="Passord" name="password" style="margin-top: 10px;  width: 100%;">
                        </div>
                        <div class="col-12">
                            <label class="label_container">Hold meg innlogget på denne enheten
                                <input type="radio" name="radio" value="log_in_forever">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-12">
                            <input type="submit" name="login" style="width: 100%;" value="Logg inn">
                            <br><br>
                            <a style="font-size: 12px;" href="glemt_passord.php">Glemt passord</a><span style="font-size: 12px; float: right;">Har du ikke bruker fra før? <a style="font-weight: bolder;" href="registrer.php">Registrer</a></span>
                        </div>
                    </form>
                </div>
                <div class="col-12">
                    <div style="font-size: 11px; width: 40.3%; float: left;">Spillere pålogget: <span style="color:#087FEF"><?php echo players_online($pdo); ?></span></div>
                    <div style="font-size: 11px; width: 23.3%; float: left;">Kriminelle handlinger i dag: <span style="color:#087FEF"><?php echo number(total_crimes_today($pdo) + total_brekk_today($pdo) + total_gta_today($pdo)); ?></span></div>
                    <div style="font-size: 11px; float: right;">Penger i spillet: <span style="color:#087FEF"><?php echo number(admin_total_money_out($pdo) + admin_total_money_bank($pdo) + family_money($pdo)); ?> kr</span></div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="login_content" style="margin-top: 20px;">
                <div class="col-12">
                    <h4 style="color: #087FEF">Skjermbilder</h4>

                    <img class="col-4" src="https://i.gyazo.com/55fe7925c9e41196d36b05ae3a5ce696.png">
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>


    </body>

    </html>

    <style>
        .login_content {
            font-family: sans-serif;
            color: white;
        }

        .login_content {
            -webkit-box-sizing: border-box;
            /* Safari/Chrome, other WebKit */
            -moz-box-sizing: border-box;
            /* Firefox, other Gecko */
            box-sizing: border-box;
            /* Opera/IE 8+ */
            padding: 20px;
            background: rgba(0, 0, 0, .9);
            width: 100%;
            height: auto;
        }

        .login_container {
            padding-top: 50px;
            margin: 0 auto;
            height: auto;
        }
    </style>

    <script>
        $(document).ready(function() {
            $(document).find("input:checked[type='radio']").addClass('bounce');
            $("input[type='radio']").click(function() {
                $(this).prop('checked', false);
                $(this).toggleClass('bounce');

                if ($(this).hasClass('bounce')) {
                    $(this).prop('checked', true);
                    $(document).find("input:not(:checked)[type='radio']").removeClass('bounce');
                }
            });
        });
    </script>
<?php

} else {
    $cookie_name_remember = "remember_forever";

    $stmt = $pdo->prepare("SELECT * FROM keep_me_login WHERE KMLI_hash = ?");
    $stmt->execute([$_COOKIE[$cookie_name_remember]]);
    $count = $stmt->fetchColumn();

    if ($count <= 0) {
        unset($_COOKIE[$cookie_name_remember]);
        setcookie($cookie_name_remember, '', time() - 3600);

        header("Location: login.php");
    } else {
        $query = $pdo->prepare("SELECT * FROM keep_me_login WHERE KMLI_hash=?");
        $query->execute(array($_COOKIE[$cookie_name_remember]));
        $KMLI_row = $query->fetch(PDO::FETCH_ASSOC);

        $username = username_plain($KMLI_row['KMLI_acc_id'], $pdo);
        $user_exist = user_exist($username, $pdo);

        if ($user_exist) {
            $_SESSION['ID'] = $KMLI_row['KMLI_acc_id'];
            header("Location: index.php");
        } else {
            header("Location: login.php");
        }
    }
}


?>