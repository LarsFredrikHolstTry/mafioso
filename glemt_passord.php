<?php

ob_start();
include 'env.php';
include 'functions/feedbacks.php';
include 'functions/functions.php';

?>

<style>
    <?php include 'css/root_style_blue.css'; ?>
</style>

<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styling.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
</head>

<body>
    <div class="main_container">
        <div class="container single" style="float: none; margin: 0 auto;">
            <div class="col-6 single" style="margin-top: 20px;">
                <?php


                if (isset($_POST['new_pass'])) {
                    $username = $_POST['username'];

                    if (strpos($username, '@') !== false) {

                        $stmt = $pdo->prepare("SELECT ACC_username FROM accounts WHERE ACC_mail = ? AND ACC_type = 0");
                        $stmt->execute([$username]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($row) {
                            echo feedback("Ditt brukernavn er " . $row['ACC_username'] . ". Har du glemt ditt passord kan du søke om nytt under.", "blue");
                        } else {
                            echo feedback("Du har ingen levende brukere på din email", "error");
                        }
                    } else {
                        if (user_exist($username, $pdo)) {
                            $id = get_acc_id($username, $pdo);
                            $uniqueKey = generateRandomString(25);
                            $email = ACC_session_row($id, 'ACC_mail', $pdo);

                            $sql = "INSERT INTO forgot_password (FOPA_unique_key, FOPA_acc_id, FOPA_date, FOPA_mail) VALUES (?,?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$uniqueKey, $id, time(), $email]);

                            $to = $email;
                            $subject = 'Nytt passord Mafioso';
                            $message = '<h3 style="font-size: 21px;" >Mafioso</h3>
                                <p>Hei! Du har nylig bedt om nytt passord på Mafioso</p>
                                <p>Besøk denne lenken for å bytte passord: 
                                    <a href="https://mafioso.no/glemt_passord.php?unique= ' . $uniqueKey . '">
                                        https://mafioso.no/glemt_passord.php?unique= ' . $uniqueKey . '
                                    </a>
                                </p>
                                <p>Dersom du ikke har bedt om nytt passord kan du trygt ignorere denne mailen.</p>';
                            $headers = 'From: Mafioso <noreply@mafioso.no>\r\n';
                            $headers .= 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                            mail($to, $subject, $message, $headers);

                            echo feedback("Sjekk emailen som du registrerte deg med!", "success");
                        } else {
                            echo feedback("Brukeren eksisterer ikke", "error");
                        }
                    }
                }

                ?>
                <div class="content">
                    <form method="post">
                        <?php if (isset($_GET['unique'])) {

                            $query = $pdo->prepare("SELECT FOPA_acc_id FROM forgot_password WHERE FOPA_unique_key = ?");
                            $query->execute(array($_GET['unique']));
                            $row = $query->fetch(PDO::FETCH_ASSOC);

                            if ($row) {

                                if (isset($_POST['change_password'])) {
                                    $password = $_POST['new_password'];
                                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                                    $sql = "UPDATE accounts SET ACC_password=? WHERE ACC_id=?";
                                    $pdo->prepare($sql)->execute([$hashed_password, $row['FOPA_acc_id']]);

                                    echo feedback("Passordet ditt er oppdatert!", "success");
                                }

                        ?>

                                <p>Endre passord for: <?php echo username_plain($row['FOPA_acc_id'], $pdo); ?></p>
                                <input type="password" placeholder="Nytt passord" name="new_password" style="margin-top: 10px;  width: 100%;">
                                <input type="submit" name="change_password" style="margin-top: 20px; width: 100%;" value="Endre passord">

                            <?php

                            } else {
                                echo feedback($useLang->error->voidID, 'error');
                            }
                        } else { ?>
                            <h3>Glemt passord</h3>
                            <p>Dersom du har glemt passordet ditt kan du skrive inn brukernavnet under<br>Dersom du har glemt brukernavnet kan du skrive e-posten du registrerte deg med</p>
                            Brukernavn / E-post
                            <input type="text" placeholder="Brukernavn eller e-post" name="username" style="margin-top: 10px;  width: 100%;">
                            <input type="submit" name="new_pass" style="margin-top: 20px; width: 100%;" value="Send nytt passord">
                            <br><br>
                            <a href="login.php">Logg inn</a><span style="float: right;">Har du ikke bruker fra før? <a style="font-weight: bolder;" href="registrer.php">Registrer</a></span>
                            <div style="clear: both;"></div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>