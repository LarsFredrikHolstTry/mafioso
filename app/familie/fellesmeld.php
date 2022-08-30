<?php

if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    if (get_my_familyrole($_SESSION['ID'], $pdo) == 3) {
        echo feedback("Medlemmer har ikke tilgang til fellesmelding til familien.", "fail");
    } else {

        $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);
        $family_name = get_my_familyname($_SESSION['ID'], $pdo);

        if (isset($_GET['success'])) {
            echo feedback("Meldinger ble sendt!", "success");
        }

        if (isset($_POST['send'])) {
            if (isset($_POST['text']) && isset($_POST['title'])) {
                $text = htmlspecialchars($_POST['text']);
                $title = htmlspecialchars($_POST['title']);

                $i = 0;

                $sql = "SELECT * FROM family_member WHERE FAMMEM_fam_id = " . $my_family_id . "";
                $stmt = $pdo->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $i++;

                    $pm_ID = uniqid();
                    $time = time();

                    send_pm_main($pm_ID, $_SESSION['ID'], $row['FAMMEM_acc_id'], $title, $pdo);
                    send_pm_text($pm_ID, $_SESSION['ID'], $text, $pdo);
                    set_pm_new($pm_ID, $_SESSION['ID'], $row['FAMMEM_acc_id'], $pdo);
                }

                header("Location: ?side=familie&p=fellesmeld&success");
            } else {
                echo feedback("Ugyldig input", "fail");
            }
        }

?>
        <div class="content">
            <h4 style="margin-bottom: 10px;">Fellesmelding</h4>
            <form method="post">
                <?php echo feedback("Du er i ferd med å sende fellesmelding til alle i din familie", "blue"); ?>
                <div class="col-12">
                    <p>Tittel</p>
                    <input type="text" name="title">
                </div>
                <div class="col-12">
                    <p>Fellesmelding</p>
                    <textarea name="text" rows="10" placeholder="Melding..." required></textarea>
                    <br><br>
                    <input type="submit" name="send" value="Send fellesmelding">
                </div>
                <div style="clear: both;"></div>
            </form>
        </div>
<?php

    }
}

?>