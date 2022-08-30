<?php

$staten_pris = 500000;
$price_for_build = 100000000;
$start_price = 10000;

if (isset($_GET['bunker_status'])) {
    if ($_GET['bunker_status'] == 'in') {

        $query = $pdo->prepare("SELECT BUNCHO_bunkerID FROM bunker_chosen WHERE BUNCHO_city = ? AND BUNCHO_acc_id = ?");
        $query->execute(array(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $_SESSION['ID']));
        $private_bunker_chosen = $query->fetch(PDO::FETCH_ASSOC);

        if ($private_bunker_chosen) {
            $query = $pdo->prepare("SELECT BUNOWN_acc_id FROM bunker_owner WHERE BUNOWN_id = ?");
            $query->execute(array($private_bunker_chosen['BUNCHO_bunkerID']));
            $private_bunker_owner = $query->fetch(PDO::FETCH_ASSOC);

            $owner_string = "<br><b>Akkurat nå</b> kan " . ACC_username($private_bunker_owner['BUNOWN_acc_id'], $pdo) . " se at du gikk inn i bunker i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . "";
        } else {
            $owner_string = '';
        }

        echo feedback("<b>Du gikk inn i bunker</b> <br>Du blir værende i bunker frem til du går ut eller om 24 timer." . $owner_string, "blue");
    } elseif ($_GET['bunker_status'] == 'out') {
        echo feedback("<b>Du gikk ut av bunkeren</b> <br>Ingen så at du gikk ut av bunkeren, men det er nå lettere å bli drept av andre spillere.", "blue");
    } elseif ($_GET['bunker_status'] == 'notEnoughMoney') {
        echo feedback("Du har ikke nok penger til å sjekke inn i bunker!", "fail");
    }
}

if (isset($_POST['change_bunker'])) {
    user_log($_SESSION['ID'], $_GET['side'], "Endre bunker", $pdo);
    $chosen = $_POST['bunker_id'];

    if ($chosen == 'null') {
        echo feedback("Ugyldig valg", "error");
    } elseif ($chosen == '0') {
        $sql = "DELETE FROM bunker_chosen WHERE BUNCHO_acc_id = " . $_SESSION['ID'] . " AND BUNCHO_city = " . AS_session_row($_SESSION['ID'], 'AS_city', $pdo) . "";
        $pdo->exec($sql);

        user_log($_SESSION['ID'], $_GET['side'], "Endret bunker til staten", $pdo);
        echo feedback("Du byttet bunker til staten sitt.", "success");
    } else {
        $query = $pdo->prepare("SELECT BUNOWN_id, BUNOWN_acc_id FROM bunker_owner WHERE BUNOWN_id = ?");
        $query->execute(array($chosen));
        $bunker_row = $query->fetch(PDO::FETCH_ASSOC);

        if ($bunker_row) {
            $sql = "DELETE FROM bunker_chosen WHERE BUNCHO_acc_id = " . $_SESSION['ID'] . " AND BUNCHO_city = " . AS_session_row($_SESSION['ID'], 'AS_city', $pdo) . "";
            $pdo->exec($sql);

            $sql = "INSERT INTO bunker_chosen (BUNCHO_acc_id, BUNCHO_bunkerID, BUNCHO_city) VALUES (?,?,?)";
            $pdo->prepare($sql)->execute([$_SESSION['ID'], $bunker_row['BUNOWN_id'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

            user_log($_SESSION['ID'], $_GET['side'], 'Endre bunker til ' . ACC_username($bunker_row['BUNOWN_acc_id'], $pdo) . ' sin bunker', $pdo);
            echo feedback('Du byttet bunker til ' . ACC_username($bunker_row['BUNOWN_acc_id'], $pdo) . ' sin bunker', 'success');
        } else {
            echo feedback($useLang->error->voidID, 'error');
        }
    }
}

if (isset($_POST['create_bunker'])) {
    user_log($_SESSION['ID'], $_GET['side'], 'Opprette bunker', $pdo);

    $query = $pdo->prepare("SELECT * FROM bunker_owner WHERE BUNOWN_city = ?");
    $query->execute(array(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if (bunker_owner_already($_SESSION['ID'], $pdo)) {
        user_log($_SESSION['ID'], $_GET['side'], 'Eier allerede bunker', $pdo);
        echo feedback("Du eier allerede en bunker. Man kan kune eie 1 bunker per person", "error");
    } else {
        if (!$row) {
            if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= $price_for_build) {
                if (!max_eiendeler($_SESSION['ID'], $pdo)) {
                    $sql = "INSERT INTO bunker_owner (BUNOWN_acc_id, BUNOWN_price, BUNOWN_city) VALUES (?,?,?)";
                    $pdo->prepare($sql)->execute([$_SESSION['ID'], $start_price, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                    take_money($_SESSION['ID'], $price_for_build, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], 'Opprettet bunker', $pdo);
                    echo feedback("Du har opprettet bunker!", "success");
                } else {
                    user_log($_SESSION['ID'], $_GET['side'], 'Allerede maks antall eiendeler', $pdo);
                    echo feedback("Du har allerede maks antall eiendeler.", "error");
                }
            } else {
                user_log($_SESSION['ID'], $_GET['side'], 'Ikke nok penger', $pdo);
                echo feedback("Du har ikke nok penger til å opprette bunker.", "error");
            }
        } else {
            user_log($_SESSION['ID'], $_GET['side'], 'Finnes allerede bunker i denne byen', $pdo);
            echo feedback("Det finnes allerede en bunker i denne byen.", "error");
        }
    }
}

?>

<div class="col-7 single">
    <div class="content">
        <img class="action_image" src="img/action/actions/bunker.png">
        <p class="description">Her kan du velge hvilken bunker du skal sjekke inn i. Når du sjekker inn vil bunkereieren se at du har sjekket inn i bunkeren dems.</p>
        <?php

        $query = $pdo->prepare("SELECT BUNCHO_acc_id, BUNCHO_bunkerID FROM bunker_chosen WHERE BUNCHO_city = ? AND BUNCHO_acc_id = ?");
        $query->execute(array(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $_SESSION['ID']));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo 'Du bruker staten sin bunker som koster ' . number($staten_pris) . ' kr per innsjekk <br>
            <i class="fi fi-rr-plus-small" style="color: green"></i> Fordelen med å bruke staten sin bunker er at ingen kan se når du sjekket inn. <br>
            <i class="fi fi-rr-minus-small" style="color: orange"></i> Ulempen er at prisen er høyere enn spiller-eide bunkere';
        } else {
            $query = $pdo->prepare("SELECT BUNOWN_acc_id, BUNOWN_price FROM bunker_owner WHERE BUNOWN_id = ?");
            $query->execute(array($row['BUNCHO_bunkerID']));
            $row_bunker_owner = $query->fetch(PDO::FETCH_ASSOC);

            echo '<p>Du bruker ' . ACC_username($row_bunker_owner['BUNOWN_acc_id'], $pdo) . ' sin bunker som koster ' . number($row_bunker_owner['BUNOWN_price']) . ' kr per innsjekk<br>
            <i class="fi fi-rr-plus-small" style="color: green"></i> Fordelen med å bruke en spiller-eid sin bunker er at prisen er lavere enn staten sin.<br>
            <i class="fi fi-rr-minus-small" style="color: orange"></i> Ulempen med å bruke en spiller-eid bunker er at de kan se når du sjekker inn i bunkeren dems.</p>';
        }

        ?>
        <form method="post">
            <div class="custom-select" style="width:100%; margin-top: 20px;">
                <select style="width: 100%;" name="bunker_id" required>
                    <option value="null">Velg bunker:</option>
                    <option value="0">
                        Statens bunker i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?>: <?php echo number($price_for_bunker_use); ?>kr
                    </option>
                    <?php

                    $sql = "SELECT * FROM bunker_owner WHERE BUNOWN_city = " . AS_session_row($_SESSION['ID'], 'AS_city', $pdo) . "";
                    $stmt = $pdo->query($sql);
                    while ($row_bunk = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $id =           $row_bunk['BUNOWN_id'];
                        $owner =        $row_bunk['BUNOWN_acc_id'];
                        $price =        $row_bunk['BUNOWN_price'];

                    ?>
                        <option value="<?php echo $id; ?>">
                            <?php echo username_plain($owner, $pdo); ?>'s bunker: <?php echo number($price); ?>kr
                        </option>
                    <?php } ?>
                </select>
            </div>
            <input style="margin-top:10px;" type="submit" name="change_bunker" value="Endre fast bunker">
        </form>
    </div>
</div>


<div class="col-7 single" style="margin-top: 10px;">
    <div class="content">
        <img class="action_image" src="img/action/actions/bygg_bunker.png">
        <?php

            $query = $pdo->prepare("SELECT * FROM bunker_owner WHERE BUNOWN_city = ?");
            $query->execute(array(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)));
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if (firma_on_marked(5, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
                echo feedback("Bunkeren er ute til salgs på marked", "blue");
            } else if (!$row) { ?>
                <form method="post">
                    <p class="description">
                        Det finnes ingen bunker i denne byen og
                        det kan derfor opprettes en bunker for
                        <?php echo number($price_for_build); ?> kr.
                        Fordelen med å eie drive en bunker er at du selv
                        velger prisen på bruk av bunkeren, men det kan ikke
                        overstige 100 000 kr.
                        Når noen bruker din bunker kan du se når de gikk inn i bunkeren.
                    </p>
                    <input type="submit" name="create_bunker" value="Bygg bunker for <?php echo number($price_for_build); ?> kr">
                </form>
            <?php } else if ($row['BUNOWN_acc_id'] == $_SESSION['ID']) { ?>
                <?php

                $max_price = 100000;
                $min_price = 0;

                if (isset($_GET['new_pris'])) {
                    echo feedback("Prisen ble oppdatert", "success");
                }

                if (isset($_POST['update_price'])) {
                    if (isset($_POST['price'])) {
                        $price = remove_space($_POST['price']);
                        if ($price <= $max_price && $price >= $min_price && is_numeric($price)) {
                            $sql = "UPDATE bunker_owner SET BUNOWN_price = ? WHERE BUNOWN_city = ? ";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$price, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                            header("location: ?side=drap&p=bunker&new_pris");
                        } else {
                            echo feedback("Ugyldig pris. Prisen kan ikke være mindre enn " . $min_price . " eller mer enn " . $max_price . "", "error");
                        }
                    }
                }

                if (isset($_GET['money_out_val'])) {
                    echo feedback("Du tok ut " . number($_GET['money_out_val']) . " kr fra bunker banken", "success");
                }

                if (isset($_POST['money_out_bunker'])) {
                    if (isset($_POST['money_bunker'])) {
                        $money = remove_space($_POST['money_bunker']);
                        if ($money <= $row['BUNOWN_bank'] && is_numeric($money) && $money > 0) {
                            give_money($_SESSION['ID'], $money, $pdo);

                            $sql = "UPDATE bunker_owner SET BUNOWN_bank = BUNOWN_bank - ? WHERE BUNOWN_city = ? ";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$money, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                            header("location: ?side=drap&p=bunker&money_out_val=" . $money . "");
                        } else {
                            echo feedback("Ugyldig sum", "error");
                        }
                    }
                }

                if (isset($_POST['remove_bunker'])) {
                    if(shut_down_bunker($_SESSION['ID'], $pdo) >= 1) { // En bunker ble lagt ned
                        echo feedback("Du ga fra deg bunkeren", "success");
                    }
                    else { // Ingen bunker ble lagt ned
                        echo feedback("Du eier ikke en bunker", "error");
                    }
                }

                ?>
                <h3>Bunker kontrollpanel</h3>
                <p class="description">
                    Som eier av bunkeren i
                    <?php city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?>
                    velger du prisen per bruk er. Du har også mulighet for å se
                    hvem som bruker din bunker.
                </p>
                <div class="col-6">
                    <form method="post">
                        <p>Pris for bruk</p>
                        <input type="text" id="number" style="max-width: 90px;" name="price" value="<?php echo number($row['BUNOWN_price']); ?>"> kr
                        <input style="margin-left: 10px;" type="submit" name="update_price" value="Oppdater pris">
                    </form>
                </div>
                <div class="col-6">
                    <form method="post">
                        <p>Bunker bank: <?php echo number($row['BUNOWN_bank']); ?>kr</p>
                        <input type="text" id="number_bank" name="money_bunker" value="<?php echo number($row['BUNOWN_bank']); ?>"> kr
                        <input style="margin-left: 10px;" type="submit" name="money_out_bunker" value="Ta ut penger">
                    </form>
                </div>
                <form method="post">
                    <p class="description">Dersom du ikke lengre ønsker å ha bunkeren kan du gi fra deg plassen til noen andre.
                        <br>Det er også mulig å selge bunkeren på marked.
                    </p>
                    <input style="margin-left: 10px;" type="submit" onclick="return confirm('Er du sikker på at du ønsker å gi fra deg bunkeren?')" name="remove_bunker" value="Gi fra deg bunkeren">
                </form>
                <div style="clear: both;"></div>
            <?php } else { ?>
                <span class="description">
                    <?php echo ACC_username($row['BUNOWN_acc_id'], $pdo); ?>
                    er eieren av bunkeren i
                    <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) ?>
                    og prisen er satt til
                    <?php echo number($row['BUNOWN_price']); ?> kr per bruk.
                    <br>
                    Du kan velge selv om du vil bruke <?php echo ACC_username($row['BUNOWN_acc_id'], $pdo); ?>
                    sin bunker eller staten sin bunker.
                </span>
            <?php } ?>
    </div>
</div>
<script src="js/select_custom.js"></script>
<script>
    number_space("#number");
    number_space("#number_bank");
</script>