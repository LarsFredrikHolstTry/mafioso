<?php

if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $feedback_terr[0] = feedback("Du har gitt fra deg territoriumet.", "success");
    $feedback_terr[1] = feedback("Din familie eier ikke denne byen.", "error");
    $feedback_terr[2] = "";
    $feedback_terr[3] = "";
    $feedback_terr[4] = "";
    $feedback_terr[5] = feedback("Det er ikke nok penger i familien til å ta over byen.", "error");

    if (isset($_GET['f'])) {
        if (array_key_exists($_GET['f'], $feedback_terr)) {
            if ($_GET['f'] == 2) {
                echo feedback("Du feilet med å ta over " . city_name($_GET['city_name']) . " fra familien " . get_familyname($_GET['owner'], $pdo), "error");
            } elseif ($_GET['f'] == 3) {
                echo feedback("Du tok over " . city_name($_GET['city_name']) . " fra familien " . get_familyname($_GET['owner'], $pdo) . ". Det ble brukt " . number($_GET['bullets']) . " kuler", "success");
            } elseif ($_GET['f'] == 4) {
                echo feedback("Familien din kjøpte territoriumet i " . city_name($_GET['city_name']), "success");
            } else {
                echo $feedback_terr[$_GET['f']];
            }
        } else {
            echo feedback("Ugyldig feedback", "error");
        }
    }

    $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);
    $my_family_money = get_my_familybank($my_family_id, $pdo);

    $price_for_city = 50000000;

    if (get_my_familyrole($_SESSION['ID'], $pdo) == 0) {

        if (isset($_GET['city'])) {
            $city = $_GET['city'];
            if (is_numeric($city) && $city >= 0 || $city <= 4) {
                if (get_city_owner($city, $pdo) == $my_family_id) {
                    $sql = "DELETE FROM territorium WHERE TE_city = " . $city . " LIMIT 1";
                    $pdo->exec($sql);

                    header("location: ?side=familie&p=territorium&f=0");
                } else {
                    header("location: ?side=familie&p=territorium&f=1");
                }
            } else {
                echo feedback($useLang->error->voidID, 'error');
            }
        }

        $cooldown = 900 + time();

        if (territorium_ready($_SESSION['ID'], $pdo)) {
            $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
            $stmt->execute(array(
                ':cd_id' => $_SESSION['ID']
            ));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $time_left = $row['CD_territorium'] - time();

            $minutes = floor($time_left / 60);
            $seconds = $time_left - $minutes * 60;

            echo feedback('Du har nylig forsøkt å ta over eller tatt over en by. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan ta over en ny by eller prøve igjen.', 'cooldown');

?>
            <script>
                timeleft(<?php echo $time_left; ?>, "countdowntimer");
            </script>

        <?php

        } else {

            if (isset($_POST['take_city'])) {
                $city = $_POST['city'];

                if (!is_numeric($city) || $city < 0 || $city > 4) {
                    echo feedback("Ugyldig by ID", "error");
                } elseif (amount_of_city_owned($my_family_id, $pdo) >= 2) {
                    echo feedback("En familie kan maks eie 2 byer", "error");
                } elseif (get_city_owner($city, $pdo) == $my_family_id) {
                    echo feedback("Din familie eier allerede " . city_name($city), "error");
                } elseif (city_owner_exist($city, $pdo)) {
                    $owner_of_city = get_city_owner($city, $pdo);
                    $bullets_needed = get_family_defence($owner_of_city, $pdo) / 1000;

                    if ($bullets_needed > get_family_bullets($my_family_id, $pdo)) {
                        territorium_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                        $acc_id = get_family_leader($owner_of_city, $pdo);
                        $text = "Territoriumet i " . city_name($city) . " ble forsøkt angrepet.";
                        send_notification($acc_id, $text, $pdo);

                        header("location: ?side=familie&p=territorium&f=2&city_name=" . $city . "&owner=" . $owner_of_city . "");
                    } else {
                        $sql = "UPDATE family SET FAM_bullets = (FAM_bullets - $bullets_needed) WHERE FAM_id='" . $my_family_id . "'";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();

                        if ((get_family_defence($owner_of_city, $pdo) - ($bullets_needed * 1000)) < 0) {
                            $sql = "UPDATE family SET FAM_forsvar = 0 WHERE FAM_id='" . $owner_of_city . "'";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                        } else {
                            $sql = "UPDATE family SET FAM_forsvar = (FAM_forsvar - ($bullets_needed * 1000)) WHERE FAM_id='" . $owner_of_city . "'";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                        }

                        $sql = "UPDATE territorium SET TE_family_id = " . $my_family_id . " WHERE TE_city='" . $city . "'";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();

                        territorium_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                        $acc_id = get_family_leader($owner_of_city, $pdo);
                        $text = "Territoriumet i " . city_name($city) . " ble tatt fra din familie. Din familie mistet forsvar i angrepet.";
                        send_notification($acc_id, $text, $pdo);

                        header("location: ?side=familie&p=territorium&f=3&city_name=" . $city . "&owner=" . $owner_of_city . "&bullets=" . $bullets_needed . "");
                    }
                } else {
                    if (get_my_familybank($my_family_id, $pdo) >= $price_for_city) {
                        $sql = "INSERT INTO territorium (TE_family_id, TE_city) VALUES (?,?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$my_family_id, $city]);

                        territorium_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                        update_donationlist($my_family_id, $_SESSION['ID'], 3, $price_for_city, $pdo);
                        take_my_family_money($my_family_id, $price_for_city, $pdo);

                        header("location: ?side=familie&p=territorium&f=4&city_name=" . $city . "");
                    } else {
                        header("location: ?side=familie&p=territorium&f=5");
                    }
                }
            }

        ?>
            <div class="content">
                <h3>Territorium</h3>
                <p class="description">Ved å ta over en by bruker du et gitt antall kuler for å ta over byen. Dersom det allerede er en familie som eier en by må du bruke 1 kule pr 1000 forsvar som familien har. Dersom det ikke er noe eier av byen du ønsker å ta over koster dette <?php echo number($price_for_city); ?>kr. Man kan se oversikten på <a href="?side=statistikk">statistikk</a></p>
                <p style="text-align: center;">Hvilken by ønsker du å ta over?</p>
                <center>
                    <form method="post">
                        <label for="cars">Velg by: </label>
                        <select name="city" id="cars">
                            <?php for ($i = 0; $i < 5; $i++) { ?>
                                <option value="<?php echo $i; ?>"><?php echo city_name($i);
                                                                    if (city_owner_exist($i, $pdo)) {
                                                                        echo ' - eies av ' . get_familyname(get_city_owner($i, $pdo), $pdo);
                                                                    } else {
                                                                        echo ' - ingen eiere';
                                                                    } ?></option>
                            <?php } ?>
                        </select>
                        <input type="submit" name="take_city" value="Ta over byen">
                    </form>
                </center>
            </div>
        <?php

        }
    }

    if (get_my_familyrole($_SESSION['ID'], $pdo) >= 0) {

        $price = 10000;
        $price_pr_for = $price;

        ?>
        <div class="content">
            <span>Oversikt</span>
            <p style="text-align: center;">Under vil du se en oversikt over byer som din familie eier</p>

            <?php

            if (!family_owner_exist($my_family_id, $pdo)) {
                echo feedback("Din familie eier ingen byer. Det er kun ledere i familien som kan ta over byer.", "fail");
            } else {

            ?>

                <table>
                    <tr>
                        <th>By</th>
                        <th>Inntekt</th>
                        <th>Innbyggere</th>
                        <?php if (get_my_familyrole($_SESSION['ID'], $pdo) == 0) { ?>
                            <th></th>
                        <?php } ?>
                    </tr>
                    <?php

                    $query = $pdo->prepare('SELECT TE_city, TE_money FROM territorium WHERE TE_family_id=:family_id');
                    $query->execute(array(':family_id' => $my_family_id));
                    foreach ($query as $row) {

                    ?>
                        <tr>
                            <td><?php echo city_name($row['TE_city']); ?></td>
                            <td><?php echo number($row['TE_money']); ?> kr</td>
                            <td><?php echo innbyggere($row['TE_city'], $pdo); ?> stk</td>
                            <?php if (get_my_familyrole($_SESSION['ID'], $pdo) == 0) { ?> <td><a href="?side=familie&p=territorium&city=<?php echo $row['TE_city']; ?>">Gi fra deg territorium</a></td> <?php } ?>
                        </tr>
                    <?php } ?>
                </table>

            <?php } ?>
        </div>
<?php }
} ?>
<script>
    number_space("#number");
</script>
<script src="js/select_custom.js"></script>