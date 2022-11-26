<?php

ob_start();
include 'env.php';
include 'functions/feedbacks.php';
include 'functions/functions.php';

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
        <div class="main_container">
            <div class="container single" style="float: none; margin: 0 auto;">
                <div class="col-12 single" style="margin-top: 20px;">
                    <div class="col-6 single shadow">
                        <div class="content">
                            <?php

                            if (isset($_GET['ver'])) {
                                echo feedback("Brukeren er nå aktivert! Ha en hyggelig spill-opplevelse.", "success");
                            }

                            if (isset($_GET['act'])) {
                                if ($_GET['act'] == "loggut") {
                                    echo feedback("Du logget ut av Mafioso. Velkommen tilbake! 👋", "blue");
                                }
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
                                                        echo '<img class="action_image" src="img/action/actions/drept.png">';
                                                        echo '<p class="description">Du har blitt drept av en annen mafioso. Dersom du ønsker kan du lage en ny bruker med samme e-amil som du brukte på din tidligere bruker.';
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
                            <form method="post">
                                <h3>Logg inn</h3>
                                <div class="col-6" style="margin-top: 20px;">
                                    Brukernavn
                                    <input type="text" placeholder="Brukernavn" name="username" style="margin-top: 10px; width: 100%;">
                                </div>
                                <div class="col-6" style="margin-top: 20px;">
                                    Passord
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
                                    <!-- <a href="glemt_passord.php">Glemt passord</a> --><span style="float: right;">Har du ikke bruker fra før? <a style="font-weight: bolder;" href="registrer.php">Registrer</a></span>
                                </div>
                                <div style="clear: both;"></div>
                            </form>
                        </div>
                    </div>
                    <div class="col-6 single shadow" style="margin-top: 20px;">
                        <div class="content">
                            <center>Det er <?php echo number(players_online($pdo)); ?> mafiosoer pålogget</center>
                        </div>
                    </div>
                    <div class="col-6 single shadow" style="margin-top: 20px;">
                        <div class="content">
                            <div class="logo">
                                <img src="img/favicon-32x32.png"> <span style="color: white; font-size: 20px; margin-left: 10px; position: absolute; margin-top: 4px; font-weight: bolder;">Mafioso 2</span>
                                <p>Kampen om makten</p>
                            </div>
                            <br>
                            Mafioso er et tekstbasert mafiaspill som ble grunnlagt av Lars Fredrik Holst-Try (Skitzo) i 2020. Spillet er laget som ett fri-sted for unge og eldre. Ledelsen er aktiv og står kun for god stemning ✨
                            <br><br>
                            Vi i ledelsen håper du får en hyggelig spill-opplevelse!
                            <br><br>
                            <h3>Anbefalt aldersgrense: <span class="v2">15+</span></h3>
                            <br>
                            Ved å registrere deg godtar du også våre vilkår. Som kan leses <b><a href="#">her</a></b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 single shadow" id="cookies">Vi bruker informasjonskapsler for å sikre at vi gir deg den beste opplevelsen på nettstedet vårt. <a style="text-decoration: underline" href="cookies.php">Les mer</a>
            <form method="post">
                <input style="margin: 0; float: right; margin-right: 50px;" type="submit" name="ok" value="ok">
            </form>
        </div>
       
    </body>

    </html>

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