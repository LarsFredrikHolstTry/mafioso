<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);
    $my_family_money = get_my_familybank($my_family_id, $pdo);

    $price = 10000;
    $price_pr_for = $price;

    if (isset($_GET['p']) && $_GET['p'] == 'kulelager') {
        if (isset($_GET['bullets'])) {
            echo feedback("Du satt inn " . number($_GET['bullets']) . " kuler i din familie", "success");
        }

        if (isset($_GET['bullets_out'])) {
            echo feedback("Du tok ut " . number($_GET['bullets_out']) . " kuler fra din familie", "success");
        }
    }

    if (isset($_POST['post_in'])) {
        if (isset($_POST['bullets']) && is_numeric($_POST['bullets']) && $_POST['bullets'] > 0) {
            $bullet_amt = remove_space($_POST['bullets']);

            if ($bullet_amt <= AS_session_row($_SESSION['ID'], 'AS_bullets', $pdo)) {
                take_bullets($_SESSION['ID'], $bullet_amt, $pdo);

                $sql = "UPDATE family SET FAM_bullets = (FAM_bullets + $bullet_amt) WHERE FAM_id='" . $my_family_id . "'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                header("Location: ?side=familie&p=kulelager&bullets=" . $bullet_amt . "");
            } else {
                echo feedback("Du har ikke så mange kuler", "error");
            }
        } else {
            echo feedback("Ugyldig input", "error");
        }
    }

    if (isset($_POST['post_out'])) {
        if (get_my_familyrole($_SESSION['ID'], $pdo) == 0) {
            if (isset($_POST['bullets_out']) && is_numeric($_POST['bullets_out']) && $_POST['bullets_out'] > 0) {
                $bullet_amt = remove_space($_POST['bullets_out']);

                if ($bullet_amt <= get_family_bullets($my_family_id, $pdo)) {
                    give_bullets($_SESSION['ID'], $bullet_amt, $pdo);

                    $sql = "UPDATE family SET FAM_bullets = (FAM_bullets - $bullet_amt) WHERE FAM_id='" . $my_family_id . "'";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    header("Location: ?side=familie&p=kulelager&bullets_out=" . $bullet_amt . "");
                } else {
                    echo feedback("Familien har ikke så mange kuler", "error");
                }
            } else {
                echo feedback("Ugyldig input", "error");
            }
        } else {
            echo feedback("Ugyldig handling", "error");
        }
    }


    if (isset($_GET['kjop'])) {
        if (is_numeric($_GET['kjop'])) {
            echo feedback("Du har kjøpt " . number($_GET['kjop']), "success");
        } else {
            echo feedback("Ugyldig input", "error");
        }
    }


?>
    <div class="col-6 single">
        <div class="content">
            <h4>Familiens kulelager</h4>
            <p class="description">Kuler kan brukes i angrep mot andre familier for å ta over territorium</p>
            <form method="post">
                <div class="col-12" style="padding: 0 0 1rem 0">
                    <p>Antall kuler på hånden: <span style="color: #c5c5c5;"><?php echo number(AS_session_row($_SESSION['ID'], 'AS_bullets', $pdo)); ?></span></p>
                    <input type="text" name="bullets" id="number_bullet" placeholder="Antall kuler">
                    <input type="submit" name="post_in" value="Sett inn">
                </div>
                <?php if (get_my_familyrole($_SESSION['ID'], $pdo) == 0) { ?>
                    <div class="col-12" style="padding: 0">
                    <p>Antall kuler i familien: <span style="color: #c5c5c5;"><?php echo number(get_family_bullets($my_family_id, $pdo)); ?></span></p>
                        <input type="text" name="bullets_out" id="number_bullet2" placeholder="Antall kuler">
                        <input type="submit" name="post_out" value="Ta ut">
                    </div>
                <?php } ?>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>
<?php } ?>
<script>
    number_space("#number_bullet");
    number_space("#number_bullet2");
</script>