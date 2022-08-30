<?php

ob_start();
include '../db_cred/db_cred.php';
include 'functions/feedbacks.php';
include 'functions/functions.php';

function is_temp_mail($mail)
{
    $mail_domains_ko = array('ttirv.net', 'awdrt.net', '0815.ru0clickemail.com', '0wnd.net', '0wnd.org', '10minutemail.com', '20minutemail.com', '2prong.com', '3d-painting.com', '4warding.com', '4warding.net', '4warding.org', '9ox.net', 'a-bc.net', 'amilegit.com', 'anonbox.net', 'anonymbox.com', 'antichef.com', 'antichef.net', 'antispam.de', 'baxomale.ht.cx', 'beefmilk.com', 'binkmail.com', 'bio-muesli.net', 'bobmail.info', 'bodhi.lawlita.com', 'bofthew.com', 'brefmail.com', 'bsnow.net', 'bugmenot.com', 'bumpymail.com', 'casualdx.com', 'chogmail.com', 'cool.fr.nf', 'correo.blogos.net', 'cosmorph.com', 'courriel.fr.nf', 'courrieltemporaire.com', 'curryworld.de', 'cust.in', 'dacoolest.com', 'dandikmail.com', 'deadaddress.com', 'despam.it', 'devnullmail.com', 'dfgh.net', 'digitalsanctuary.com', 'discardmail.com', 'discardmail.de', 'disposableaddress.com', 'disposemail.com', 'dispostable.com', 'dm.w3internet.co.uk', 'example.com', 'dodgeit.com', 'dodgit.com', 'dodgit.org', 'dontreg.com', 'dontsendmespam.de', 'dump-email.info', 'dumpyemail.com', 'e4ward.com', 'email60.com', 'emailias.com', 'emailinfive.com', 'emailmiser.com', 'emailtemporario.com.br', 'emailwarden.com', 'ephemail.net', 'explodemail.com', 'fakeinbox.com', 'fakeinformation.com', 'fastacura.com', 'filzmail.com', 'fizmail.com', 'frapmail.com', 'garliclife.com', 'get1mail.com', 'getonemail.com', 'getonemail.net', 'girlsundertheinfluence.com', 'gishpuppy.com', 'great-host.in', 'gsrv.co.uk', 'guerillamail.biz', 'guerillamail.com', 'guerillamail.net', 'guerillamail.org', 'guerrillamail.com', 'guerrillamailblock.com', 'haltospam.com', 'hotpop.com', 'ieatspam.eu', 'ieatspam.info', 'ihateyoualot.info', 'imails.info', 'inboxclean.com', 'inboxclean.org', 'incognitomail.com', 'incognitomail.net', 'ipoo.org', 'irish2me.com', 'jetable.com', 'jetable.fr.nf', 'jetable.net', 'jetable.org', 'junk1e.com', 'kaspop.com', 'kulturbetrieb.info', 'kurzepost.de', 'lifebyfood.com', 'link2mail.net', 'litedrop.com', 'lookugly.com', 'lopl.co.cc', 'lr78.com', 'maboard.com', 'mail.by', 'mail.mezimages.net', 'mail4trash.com', 'mailbidon.com', 'mailcatch.com', 'maileater.com', 'mailexpire.com', 'mailin8r.com', 'mailinator.com', 'mailinator.net', 'mailinator2.com', 'mailincubator.com', 'mailme.lv', 'mailnator.com', 'mailnull.com', 'mailzilla.org', 'mbx.cc', 'mega.zik.dj', 'meltmail.com', 'mierdamail.com', 'mintemail.com', 'moncourrier.fr.nf', 'monemail.fr.nf', 'monmail.fr.nf', 'mt2009.com', 'mx0.wwwnew.eu', 'mycleaninbox.net', 'mytrashmail.com', 'neverbox.com', 'nobulk.com', 'noclickemail.com', 'nogmailspam.info', 'nomail.xl.cx', 'nomail2me.com', 'no-spam.ws', 'nospam.ze.tc', 'nospam4.us', 'nospamfor.us', 'nowmymail.com', 'objectmail.com', 'obobbo.com', 'onewaymail.com', 'ordinaryamerican.net', 'owlpic.com', 'pookmail.com', 'proxymail.eu', 'punkass.com', 'putthisinyourspamdatabase.com', 'quickinbox.com', 'rcpt.at', 'recode.me', 'recursor.net', 'regbypass.comsafe-mail.net', 'safetymail.info', 'sandelf.de', 'saynotospams.com', 'selfdestructingmail.com', 'sendspamhere.com', 'shiftmail.com', '****mail.me', 'skeefmail.com', 'slopsbox.com', 'smellfear.com', 'snakemail.com', 'sneakemail.com', 'sofort-mail.de', 'sogetthis.com', 'soodonims.com', 'spam.la', 'spamavert.com', 'spambob.net', 'spambob.org', 'spambog.com', 'spambog.de', 'spambog.ru', 'spambox.info', 'spambox.us', 'spamcannon.com', 'spamcannon.net', 'spamcero.com', 'spamcorptastic.com', 'spamcowboy.com', 'spamcowboy.net', 'spamcowboy.org', 'spamday.com', 'spamex.com', 'spamfree24.com', 'spamfree24.de', 'spamfree24.eu', 'spamfree24.info', 'spamfree24.net', 'spamfree24.org', 'spamgourmet.com', 'spamgourmet.net', 'spamgourmet.org', 'spamherelots.com', 'spamhereplease.com', 'spamhole.com', 'spamify.com', 'spaminator.de', 'spamkill.info', 'spaml.com', 'spaml.de', 'spammotel.com', 'spamobox.com', 'spamspot.com', 'spamthis.co.uk', 'spamthisplease.com', 'speed.1s.fr', 'suremail.info', 'tempalias.com', 'tempemail.biz', 'tempemail.com', 'tempe-mail.com', 'tempemail.net', 'tempinbox.co.uk', 'tempinbox.com', 'tempomail.fr', 'temporaryemail.net', 'temporaryinbox.com', 'thankyou2010.com', 'thisisnotmyrealemail.com', 'throwawayemailaddress.com', 'tilien.com', 'tmailinator.com', 'tradermail.info', 'trash2009.com', 'trash-amil.com', 'trashmail.at', 'trash-mail.at', 'trashmail.com', 'trash-mail.com', 'trash-mail.de', 'trashmail.me', 'trashmail.net', 'trashymail.com', 'trashymail.net', 'tyldd.com', 'uggsrock.com', 'wegwerfmail.de', 'wegwerfmail.net', 'wegwerfmail.org', 'wh4f.org', 'whyspam.me', 'willselfdestruct.com', 'winemaven.info', 'wronghead.com', 'wuzupmail.net', 'xoxy.net', 'yogamaven.com', 'yopmail.com', 'yopmail.fr', 'yopmail.net', 'yuurok.com', 'zippymail.info', 'jnxjn.com', 'trashmailer.com', 'klzlk.com', 'nospamforus', 'kurzepost.de', 'objectmail.com', 'proxymail.eu', 'rcpt.at', 'trash-mail.at', 'trashmail.at', 'trashmail.me', 'trashmail.net', 'wegwerfmail.de', 'wegwerfmail.net', 'wegwerfmail.org', 'jetable', 'link2mail', 'meltmail', 'anonymbox', 'courrieltemporaire', 'sofimail', '0-mail.com', 'moburl.com', 'get2mail', 'yopmail', '10minutemail', 'mailinator', 'dispostable', 'spambog', 'mail-temporaire', 'filzmail', 'sharklasers.com', 'guerrillamailblock.com', 'guerrillamail.com', 'guerrillamail.net', 'guerrillamail.biz', 'guerrillamail.org', 'guerrillamail.de', 'mailmetrash.com', 'thankyou2010.com', 'trash2009.com', 'mt2009.com', 'trashymail.com', 'mytrashmail.com', 'mailcatch.com', 'trillianpro.com', 'junk.', 'joliekemulder', 'lifebeginsatconception', 'beerolympics', 'smaakt.naar.gravel', 'q00.', 'dispostable', 'spamavert', 'mintemail', 'tempemail', 'spamfree24', 'spammotel', 'spam', 'mailnull', 'e4ward', 'spamgourmet', 'mytempemail', 'incognitomail', 'spamobox', 'mailinator.com', 'trashymail.com', 'mailexpire.com', 'temporaryinbox.com', 'MailEater.com', 'spambox.us', 'spamhole.com', 'spamhole.com', 'jetable.org', 'guerrillamail.com', 'uggsrock.com', '10minutemail.com', 'dontreg.com', 'tempomail.fr', 'TempEMail.net', 'spamfree24.org', 'spamfree24.de', 'spamfree24.info', 'spamfree24.com', 'spamfree.eu', 'kasmail.com', 'spammotel.com', 'greensloth.com', 'spamspot.com', 'spam.la', 'mjukglass.nu', 'slushmail.com', 'trash2009.com', 'mytrashmail.com', 'mailnull.com', 'jetable.org', '10minutemail.com', '20minutemail.com', 'anonymbox.com', 'beefmilk.com', 'bsnow.net', 'bugmenot.com', 'deadaddress.com', 'despam.it', 'disposeamail.com', 'dodgeit.com', 'dodgit.com', 'dontreg.com', 'e4ward.com', 'emailias.com', 'emailwarden.com', 'enterto.com', 'gishpuppy.com', 'goemailgo.com', 'greensloth.com', 'guerrillamail.com', 'guerrillamailblock.com', 'hidzz.com', 'incognitomail.net ', 'jetable.org', 'kasmail.com', 'lifebyfood.com', 'lookugly.com', 'mailcatch.com', 'maileater.com', 'mailexpire.com', 'mailin8r.com', 'mailinator.com', 'mailinator.net', 'mailinator2.com', 'mailmoat.com', 'mailnull.com', 'meltmail.com', 'mintemail.com', 'mt2009.com', 'myspamless.com', 'mytempemail.com', 'mytrashmail.com', 'netmails.net', 'odaymail.com', 'pookmail.com', 'shieldedmail.com', 'smellfear.com', 'sneakemail.com', 'sogetthis.com', 'soodonims.com', 'spam.la', 'spamavert.com', 'spambox.us', 'spamcero.com', 'spamex.com', 'spamfree24.com', 'spamfree24.de', 'spamfree24.eu', 'spamfree24.info', 'spamfree24.net', 'spamfree24.org', 'spamgourmet.com', 'spamherelots.com', 'spamhole.com', 'spaml.com', 'spammotel.com', 'spamobox.com', 'spamspot.com', 'tempemail.net', 'tempinbox.com', 'tempomail.fr', 'temporaryinbox.com', 'tempymail.com', 'thisisnotmyrealemail.com', 'trash2009.com', 'trashmail.net', 'trashymail.com', 'tyldd.com', 'yopmail.com', 'zoemail.com', 'deadaddress', 'soodo', 'tempmail', 'uroid', 'spamevader', 'gishpuppy', 'privymail.de', 'trashmailer.com', 'fansworldwide.de', 'onewaymail.com', 'mobi.web.id', 'ag.us.to', 'gelitik.in', 'fixmail.tk', 'shitmail.org');
    foreach ($mail_domains_ko as $ko_mail) {
        list($mail_domain) = explode('@', $mail);
        if (strcasecmp($mail_domain, $ko_mail) == 0) {
            return true;
        }
    }
    return false;
}

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
    <title>Mafioso - Registrer</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon-32x32.png" />
</head>

<body>
    <div class="main_container">
        <div class="container single" style="float: none; margin: 0 auto;">
            <div class="col-6 single" style="margin-top: 20px;">
                <div class="content">
                    <?php

                    if (isset($_REQUEST['register'])) {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                            $username = strip_tags($_REQUEST['username']);
                            $email  = strip_tags($_REQUEST['email']);
                            $password = strip_tags($_REQUEST['password']);
                            $password_repeat = strip_tags($_REQUEST['password_repeat']);

                            if (is_temp_mail($email)) {
                                echo feedback("Ugyldig email-type", "error");
                            } else {

                                $time = time();

                                if (isRussian($username)) {
                                    echo feedback("У вас не может быть русского имени пользователя.", "error");
                                } elseif (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username)) {
                                    echo feedback("Ulovlige tegn i brukernavn.", "error");
                                } elseif (empty($username)) {
                                    echo feedback("Vennligst legg inn brukernavn", "error");
                                } else if (empty($email)) {
                                    echo feedback("Vennligst legg inn email", "error");
                                } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    echo feedback("Vennligst legg inn gyldig email", "error");
                                } else if (empty($password)) {
                                    echo feedback("Vennligst legg inn et passord", "error");
                                } else if (empty($password_repeat)) {
                                    echo feedback("Vennligst gjenta passordet", "error");
                                } else if (strlen($password) < 6) {
                                    echo feedback("Passord må være minst 6 karakterer langt", "error");
                                } else if (strlen($username) < 3 && strlen($username) > 12) {
                                    echo feedback("Brukernavnet må være mellom 3 og 12 karakterer", "error");
                                } else if ($password != $password_repeat) {
                                    echo feedback("Passordene må være like", "error");
                                } else {
                                    try {

                                        $user_exist = user_exist($username, $pdo);

                                        $zero = 0;
                                        $banned = 5;
                                        $sthandler_mail = $pdo->prepare("SELECT ACC_mail FROM accounts WHERE ACC_mail = :mail AND (ACC_type = :acc_type OR ACC_type = :acc_type_banned)");
                                        $sthandler_mail->bindParam(':mail', $email);
                                        $sthandler_mail->bindParam(':acc_type', $zero);
                                        $sthandler_mail->bindParam(':acc_type_banned', $zero);

                                        $sthandler_mail->execute();

                                        if ($user_exist) {
                                            echo feedback("Brukernavnet er opptatt", "error");
                                        } else if ($sthandler_mail->rowCount() > 0) {
                                            echo feedback("Emailen er allerede i bruk.", "error");
                                        } else if (!isset($erorr_msg)) {

                                            $ip_register = getUserIpAddr();
                                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                                            $insert_stmt = $pdo->prepare("
                    INSERT INTO
                    accounts
                    (ACC_username, ACC_mail, ACC_password, ACC_register_date, ACC_ip_register)
                    VALUES
                    (:uname,:uemail,:upassword,:uregister,:ip_register)");

                                            if ($insert_stmt->execute(array(
                                                ':uname' => $username,
                                                ':uemail' => $email,
                                                ':upassword' => $hashed_password,
                                                ':uregister' => $time,
                                                'ip_register' => $ip_register
                                            ))) {
                                                $stmt = $pdo->query("SELECT LAST_INSERT_ID() FROM accounts");
                                                $lastId = $stmt->fetchColumn();
                                                $random_string = generateRandomString(25);

                                                $sql = "INSERT INTO verification (VER_acc_id, VER_hash)
                        VALUES ('$lastId', '$random_string')";
                                                $pdo->exec($sql);

                                                $sql = "INSERT INTO accounts_stat (AS_id, AS_health, AS_money, AS_city)
                        VALUES ('$lastId', '100', '1000', '" . mt_rand(0, 4) . "')";
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

                                                if (isset($_GET['ver'])) {
                                                    $id_ver = $_GET['ver'];

                                                    $sql = "INSERT INTO verv (VERV_acc_id, VERV_by, VERV_date)
                            VALUES (" . $lastId . ", " . $id_ver . ", " . time() . ")";
                                                    $pdo->exec($sql);
                                                }

                                                $to = $email;
                                                $subject = 'Velkommen til Mafioso V2 🚀';
                                                $message = '<h3 style="font-size: 21px;" >Mafioso <span style="border-radius: 1px; font-size: 14px; background-color: #80EAFF; color: #3377FF; padding: 0px 8px;">V2</span></h3>
                        <p>Dersom du er en ny bruker trenger du å aktivere din email med å gå inn på denne linken: <a href="https://mafioso.no/verifikasjon.php?v=' . $random_string . '">https://mafioso.no/verifikasjon.php?v=' . $random_string . '</a></p>
                        <p>Om du har spørsmål så send disse til support@mafioso.no</p>
                        <p>Spørsmål som blir svart fra denne mailen blir ikke besvart</p>';
                                                $headers = 'From: Mafioso <noreply@mafioso.no>\r\n';
                                                $headers .= 'MIME-Version: 1.0' . "\r\n";
                                                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                                                mail($to, $subject, $message, $headers);

                                                $_SESSION['ID'] = $lastId;
                                                header("Location: index.php?velkommen");
                                            } else {
                                                echo feedback("Feilmelding 1", "error");
                                            }
                                        }
                                    } catch (PDOException $e) {
                                        echo $e->getMessage();
                                    }
                                }
                            }
                        }
                    }

                    ?>
                    <form method="post">
                        <h3>Registrer</h3>
                        <br>

                        <?php

                        if (isset($_GET['ver'])) {
                            if (username_plain($_GET['ver'], $pdo)) {
                                echo feedback('Du bruker en vervelink fra ' . username_plain($_GET['ver'], $pdo) . ' som vil gi deg og ververen goder når du ranker opp', 'blue');
                            }
                        }

                        ?>

                        <span>Så gøy at du ønsker å opprette bruker på Mafioso!<br>Fyll inn informasjonen under så skal jeg guide deg i begynnelsen 🚀</span>
                        <div class="col-6" style="margin-top: 20px;">
                            Brukernavn <span style="color: grey;">(minst 3 tegn)</span>
                            <input type="text" name="username" placeholder="Brukernavn" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="col-6" style="margin-top: 20px;">
                            E-post
                            <input type="email" name="email" placeholder="E-post" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="col-6">
                            Passord <span style="color: grey;">(minst 6 tegn)</span>
                            <input type="password" name="password" placeholder="Passord" style="margin-top: 10px; width: 100%;">
                        </div>
                        <div class="col-6">
                            Gjenta passord
                            <input type="password" name="password_repeat" placeholder="Gjenta passord" style="margin-top: 10px;  width: 100%;">
                        </div>
                        <div class="col-12">
                            <input type="submit" name="register" style="width: 100%;" value="Registrer">
                            <br><br>
                            <!-- <a href="glemt_passord.php">Glemt passord</a> --><span style="float: right;">Har du bruker fra før? <a style="font-weight: bolder;" href="login.php">Logg inn</a></span>
                        </div>
                        <div style="clear: both;"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>