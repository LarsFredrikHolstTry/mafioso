<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    if (get_my_familyrole($_SESSION['ID'], $pdo) == 3) {
        echo feedback("Medlemmer har ikke tilgang til forsvarssiden.", "fail");
    } else {

        $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);
        $my_family_money = get_my_familybank($my_family_id, $pdo);

        $price = 5000;
        $price_pr_for = $price;

        if (isset($_GET['p']) && $_GET['p'] == 'forsvar') {
            if (isset($_GET['forsvar_antall'])) {
                echo feedback(number($_GET['forsvar_antall']) . " forsvarspoeng ble kjøpt", "success");
            } elseif (isset($_GET['not_enough'])) {
                echo feedback("Familien har ikke råd til " . number($_GET['not_enough']) . " forsvar", "error");
            }
        }

        if (isset($_POST['post'])) {
            if (isset($_POST['amount'])) {
                $amount = remove_space($_POST['amount']);
                if (is_numeric($amount) && $amount > 0) {
                    if ($price_pr_for * $amount <= $my_family_money) {
                        $total_price = $price_pr_for * $amount;

                        take_my_family_money($my_family_id, $total_price, $pdo);
                        update_donationlist($my_family_id, $_SESSION['ID'], 2, $amount, $pdo);
                        give_family_defence($my_family_id, $amount, $pdo);

                        update_donationlist($my_family_id, $_SESSION['ID'], 4, $amount, $pdo);

                        header("Location: ?side=familie&p=forsvar&forsvar_antall=" . $amount . "");
                    } else {
                        header("Location: ?side=familie&p=forsvar&not_enough=" . $amount . "");
                    }
                } else {
                    echo feedback("Ugyldig input", "error");
                }
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
        <div class="col-12 single">
            <div class="col-7">
                <div class="content">
                    <h4>Familieforsvar</h4>
                    <p style="margin-bottom: 0; padding-bottom: 0;">Antall forsvar: <?php echo number(get_family_defence($my_family_id, $pdo)); ?></p>
                    <p style="margin-bottom: 0; padding-bottom: 0;">Penger i familiekassen: <?php echo number($my_family_money); ?> kr</p>
                    <p style="margin-bottom: 0; padding-bottom: 0;">Pris pr forsvarspoeng: <?php echo number($price_pr_for); ?> kr</p>
                    <br>
                    <p class="description">Forsvar for familien gjør dere trygge i et potensielt angrep.</p>
                    <div class="col-12">
                        <form method="post">
                            <p>Kjøp forsvar</p>
                            <input type="text" style="width: 78%;" name="amount" id="number_forsvar" placeholder="Antall...">
                            <input type="submit" name="post" value="Kjøp">
                        </form>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <div class="col-5">
                <div class="content">
                    <h4>Prisliste</h4>
                    <p>Pris pr forsvarspoeng er satt til <?php echo number($price); ?> kr</p>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
<?php }
} ?>
<script>
    number_space("#number_forsvar");
</script>