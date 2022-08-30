<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    if (isset($_POST['send'])) {
        if (isset($_POST['username']) && isset($_POST['title']) && isset($_POST['text'])) {
            $username = $_POST['username'];
            $title = htmlspecialchars($_POST['title']);
            $text = htmlspecialchars($_POST['text']);

            if (user_exist($username, $pdo)) {
                if (blocked($_SESSION['ID'], get_acc_id($username, $pdo), $pdo)) {
                    echo feedback("Spilleren har enten blokkert deg, eller så har du blokkert spilleren.", "error");
                } else {

                    $pm_ID = uniqid();
                    $time = time();

                    if (strtolower($username) != "skitzo") {
                        send_pm_main($pm_ID, $_SESSION['ID'], get_acc_id($username, $pdo), $title, $pdo);
                        send_pm_text($pm_ID, $_SESSION['ID'], $text, $pdo);
                        set_pm_new($pm_ID, $_SESSION['ID'], get_acc_id($username, $pdo), $pdo);

                        echo feedback("Melding sendt!", "success");
                    } else {
                        echo feedback("Brukeren du prøver å sende melding til er administrator. Dersom du trenger hjelp med spillet, ta kontakt på <a href='?side=support'>support</a>.", "error");
                    }
                }
            } else {
                echo feedback("brukeren eksisterer ikke", "fail");
            }
        }
    }

    if (isset($_GET['to'])) {
        $query = $pdo->prepare("SELECT * FROM accounts WHERE ACC_id=?");
        $query->execute(array($_GET['to']));
        $ACC_pm_row = $query->fetch(PDO::FETCH_ASSOC);
    }

?>
    <div class="col-8" style="float: none; margin: 0 auto; padding: 0;">
        <div class="content">
            <h3 style="margin-bottom: 20px;">Ny melding</h3>
            <?php if (isset($_GET['to']) && $ACC_pm_row['ACC_type'] == 3) {
                echo feedback("Brukeren du prøver å sende melding til er administrator. Dersom du trenger hjelp med spillet, ta kontakt på <a href='?side=support'>support</a>.", "error");
            } else { ?>
                <form method="post">
                    <div class="col-6" style="padding: 0 5;">
                        <p class="description">Brukernavn</p>
                        <input style="width:100%;" type="text" name="username" <?php if (isset($_GET['to'])) { ?> value="<?php echo username_plain($_GET['to'], $pdo) ?>" <?php } ?> placeholder="Brukernavn" required>
                    </div>
                    <div class="col-6" style="padding: 0 5;">
                        <p class="description">Tittel</p>
                        <input style="width:100%;" type="text" name="title" placeholder="Tittel" required>
                    </div>
                    <div class="col-12">
                        <p class="description">Melding</p>
                        <?php include_once '././textarea_style.php'; ?>
                        <textarea style="margin-top: 0px; border-top: none; border-radius: 0px;" name="text" rows="10" placeholder="Melding" id="txtarea" required></textarea>
                        <input style="margin-top: 20px;" type="submit" value="Send melding" name="send">
                    </div>
                    <div style="clear: both;"></div>
                </form>
            <?php } ?>
        </div>
    </div>
<?php } ?>