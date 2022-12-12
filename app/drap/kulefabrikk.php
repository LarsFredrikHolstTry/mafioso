<?php

if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    /* comment here 
    */
    function bullet_pris($city, $pdo)
    {
        $city_price[0] = 6750000;
        $city_price[1] = 6750000;
        $city_price[2] = 6750000;
        $city_price[3] = 6750000;
        $city_price[4] = 6750000;

        return $city_price[$city];
    }

    $kf_price = 500000;
    $price_pr_bullet = bullet_pris(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);

    if (isset($_GET['sold'])) {
        echo feedback("Kulefabrikken ble solgt til staten for 10 000 000kr", "success");
    }

    if (isset($_POST['money_out'])) {
        if (kf_owner_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) && get_kf_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {
            $amount = get_kf_money(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
            if ($amount > 0) {
                give_money($_SESSION['ID'], $amount, $pdo);
                take_kf_money(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $amount, $pdo);

                echo feedback("Du tok ut " . number($amount) . " kr", "success");
            } else {
                echo feedback("Du har ingen penger i kulefabrikken", "fail");
            }
        } else {
            echo feedback("Ugyldig handling", "fail");
        }
    }

    if (isset($_POST['buy_kf'])) {
        if (max_eiendeler($_SESSION['ID'], $pdo)) {
            echo feedback("Du har allerede 2 forskjellige eiendeler", "fail");
        } elseif (kf_owner_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
            echo feedback("Kulefabrikken har allerede eier", "fail");
        } elseif (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) < $kf_price) {
            echo feedback("Du har ikke nok penger til å kjøpe kulefabrikken", "fail");
        } elseif (kf_owner($_SESSION['ID'], $pdo)) {
            echo feedback("Du er allerede eier av en annen kulefabrikk", "fail");
        } else {
            take_money($_SESSION['ID'], $kf_price, $pdo);

            $sql = "INSERT INTO kf_owner (KFOW_city, KFOW_acc_id) VALUES (?,?)";
            $pdo->prepare($sql)->execute([AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $_SESSION['ID']]);

            echo feedback("Gratulerer som eier av kulefabrikken i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)), "success");
        }
    }

    if (isset($_POST['buy_bullets'])) {
        $amount = round(remove_space($_POST['bullet_amount']));
        if (isset($amount) && is_numeric($amount) && $amount > 0) {
            $amount = $amount;
            $total_price = $price_pr_bullet * $amount;

            if ($total_price > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
                echo feedback("Du har ikke nok penger for " . number($amount) . " kuler", "fail");
            } else {
                $twenty_percentage = $total_price * 0.2;

                give_kf_money(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $twenty_percentage, $pdo);
                take_money($_SESSION['ID'], $total_price, $pdo);
                give_bullets($_SESSION['ID'], $amount, $pdo);
                update_bedrift_inntekt($_SESSION['ID'], 1, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $twenty_percentage, $pdo);

                header("Location: ?side=drap&p=kf&kjop=$amount");
            }
        } else {
            echo feedback("Ugyldig antall", "fail");
        }
    }

    if (isset($_GET['kjop'])) {
        if (is_numeric($_GET['kjop'])) {
            $total_price = $_GET['kjop'] * $price_pr_bullet;
            if ($_GET['kjop'] > 1) {
                echo feedback("Du kjøpte " . number($_GET['kjop']) . " kuler for " . number($total_price) . " kr", "success");
            } else {
                echo feedback("Du kjøpte " . number($_GET['kjop']) . " kule for " . number($total_price) . " kr", "success");
            }
        }
    }

?>
    <div class="col-7 single">
        <div class="content">
            <img class="action_image" src="img/action/actions/kf.png">

            <h3>Kulefabrikk i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?></h3>
            <?php
            if (kf_owner_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
                echo '<span class="description">Eieren av denne kulefabrikken er ';
                echo ACC_username(get_kf_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo), $pdo);
                echo ' og får 20% av kulekjøpet</span>';
            } else {
                if (!firma_on_marked(1, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
            ?>
                    <p class="description">Kulefabrikken i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> har ingen eier og kan dermed kjøpes for <?php echo number($kf_price); ?> kr</p>
                    <form method="post">
                        <input type="submit" name="buy_kf" value="Kjøp kulefabrikk">
                    </form>
                <?php } else { ?>
                    <p class="description" style="margin: 0px;">Kulefabrikken i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> ligger ute på <a href="?side=marked&id=5">marked</a></p>
            <?php }
            }

            ?>
            <center>
                <p>Du har <?php

                            $amount_bullets = AS_session_row($_SESSION['ID'], 'AS_bullets', $pdo);

                            if (number($amount_bullets) == 0 || number($amount_bullets) > 1) {
                                echo number($amount_bullets) . ' kuler';
                            } elseif (number($amount_bullets) == 1) {
                                echo number($amount_bullets) . ' kule';
                            }

                            ?></p>


                <form method="post">
                    <p>Pris pr kule: <?php echo number($price_pr_bullet); ?> kr</p>
                    <input type="text" id="number" name="bullet_amount" style="width: 40%;" placeholder="Antall">
                    <input type="submit" name="buy_bullets" value="Kjøp">
                </form>
            </center>
        </div>
    </div>
    <?php


    if (kf_owner_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) && get_kf_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {

        $amount_sell = 100000;

        if (isset($_POST['sell_kf'])) {
            if (kf_owner_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) && get_kf_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {
                $sql = "DELETE FROM kf_owner WHERE KFOW_acc_id = " . $_SESSION['ID'] . "";
                $pdo->exec($sql);

                give_money($_SESSION['ID'], $amount_sell, $pdo);

                header("Location:?side=drap&p=kf&sold");
            } else {
                echo feedback("Ugyldig handling", "error");
            }
        }

    ?>
        <div class="col-8 single" style="margin-top: 10px;">
            <div class="content">
                <h4>Kulefabrikk-panel</h4>
                <p class="description">Som eier av kulefabrikk får du 20% av kulekjøpet som foregår på din kulefabrikk.</p>
                <p>Antall penger i fabrikk-banken: <?php echo number(get_kf_money(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?> kr</p>
                <form method="post">
                    <input type="submit" value="Ta ut alt" name="money_out">
                    <input type="submit" onclick="return confirm('Er du sikker på at du ønsker å selge kulefabrikken for <?php echo number($amount_sell); ?>?')" value="Selg kulefabrikken til staten" name="sell_kf">
                </form>
            </div>
        </div>
    <?php } ?>

    <script>
        number_space("#number");
    </script>
<?php } ?>