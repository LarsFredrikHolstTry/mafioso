<?php

include 'env.php';

$sthandler = $pdo->prepare("SELECT * FROM poeng_betingelse WHERE PBET_acc_id = :id");
$sthandler->bindParam(':id', $_SESSION['ID']);
$sthandler->execute();

if ($sthandler->rowCount() > 0) {
    if (isset($_GET['terms_accept'])) {
        echo feedback("Betingelsene er akseptert og du har nå mulighet til å kjøpe poeng.", "success");
    }

    /* poeng */
    $points[0] = 25;
    $points[1] = 60;
    $points[2] = 120;
    $points[3] = 250;
    $points[4] = 500;

    $points_price[0] = 30;
    $points_price[1] = 50;
    $points_price[2] = 80;
    $points_price[3] = 150;
    $points_price[4] = 280;

    error_reporting(1);

    $stripe_public_key = $stripe_public;
    $stripe_private_key = $stripe_private;

    require __DIR__ . "/lib/init.php";
    \Stripe\Stripe::setApiKey($stripe_private_key);

    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        $legal = array('success', 'cancel');
        if (in_array($status, $legal)) {
            if ($status == 'success') {
                echo feedback("Kjøpet av poeng ble vellykket", "success");
            } elseif ($status == 'cancel') {
                echo feedback("Kjøpet av poeng ble kansellert", "error");
            }
        } else {
            echo feedback("Ugyldig input", "error");
        }
    }

    $point_icons[0] = "img/poeng/0.png";
    $point_icons[1] = "img/poeng/1.png";
    $point_icons[2] = "img/poeng/2.png";
    $point_icons[3] = "img/poeng/3.png";
    $point_icons[4] = "img/poeng/4.png";
    $point_icons[5] = "img/poeng/5.png";

?>

    <div class="col-11 single">
        <div class="content">
            <h3>Poeng</h3>
            <h4 style="text-align: center;"><br>Du har: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_points', $pdo)); ?> poeng</h4>
            <br>

            <div class="points_container">
                <?php

                $i = 0;
                $sql = "SELECT * FROM $stripe_db";

                $result = $pdo->query($sql);
                if ($result) {
                    foreach ($result as $row) {
                ?>

                        <div class="points_box" onclick="expressCheckout('<?php echo $row["PRO_stripe_id"]; ?>', '<?php $param = $row["PRO_name"] . ":" . $_SESSION['ID'];
                                                                                                                    echo $param; ?>')">
                            <img style="padding: 15px; width: 40px; height: auto;" src="<?php echo $point_icons[$i]; ?>">
                            <p style="margin: 0px;"><?php echo number($row["PRO_name"]); ?> poeng</p>
                            <p style="margin: 0px 0px 10px 0px;"><?php echo $row["PRO_price"]; ?> NOK</p>
                        </div>

                <?php $i++;
                    }
                } ?>
            </div>

            <div class="col-12 single">
                <?php

                /* poeng */
                $boost[0] = "Ransbeskyttelse";
                $boost[1] = "Kjøp 10 plasser i garasjen";
                $boost[2] = "Kjøp 10 plasser i lageret";
                $boost[3] = "Ransoffer";
                $boost[4] = "Sett byskatt til 0%";
                $boost[5] = "10 000 forsvarspoeng";
                $boost[6] = "Lyddemper";
                $boost[7] = "Energidrikk";
                $boost[8] = "Hemmelig kiste";
                $boost[9] = "Smugleboost";
                $boost[10] = "Energidrikk for familie";
                $boost[11] = "7 dager i bunker";
                $boost[12] = "14 dager i bunker";
                $boost[13] = "1 time happy hour";
                $boost[14] = "3 time happy hour";
                $boost[15] = "6 time happy hour";
                $boost[16] = "12 time happy hour";
                $boost[17] = "24 time happy hour";

                $boost_price[0] = 6;
                $boost_price[1] = 8;
                $boost_price[2] = 8;
                $boost_price[3] = 14;
                $boost_price[4] = 22;
                $boost_price[5] = 77;
                $boost_price[6] = 399;
                $boost_price[7] = 22;
                $boost_price[8] = 38;
                $boost_price[9] = 250;
                $boost_price[10] = 68;
                $boost_price[11] = 50;
                $boost_price[12] = 75;
                $boost_price[13] = 60;
                $boost_price[14] = 150;
                $boost_price[15] = 250;
                $boost_price[16] = 400;
                $boost_price[17] = 700;

                $boost_info[0] = "Unngår at spillere raner biler, ting eller penger fra deg. Går ut ved midnatt.";
                $boost_info[1] = "Utvider plasse i garasjen med 10 plasser";
                $boost_info[2] = "Utvider plasse i ditt lager med 10 plasser";
                $boost_info[3] = "Finner tilfeldig ransoffer til deg som har penger på hånden og ikke ransbeskyttelse.";
                $boost_info[4] = "Tar byskatten i den byen du er i ned til 0% til neste time";
                $boost_info[5] = "Gir deg 10 000 forsvarspoeng.";
                $boost_info[6] = "Lyddemper sørger for at den ikke viser hvem du har drept.";
                $boost_info[7] = "En energidrikk gir dobbel exp i 3 timer.";
                $boost_info[8] = "En hemmelig kiste kan åpnes for å få en tilfeldig premie.";
                $boost_info[9] = "Boost på smugling gir deg x2 inntekt ved midnatt";
                $boost_info[10] = "Gir 3 timer med dobbel exp til alle i familien din";
                $boost_info[11] = "Gå 7 dager i bunker. Eieren av bunkeren i byen du er i får 25 000 000 kr";
                $boost_info[12] = "Gå 14 dager i bunker. Eieren av bunkeren i byen du er i får 35 000 000 kr";
                $boost_info[13] = "Kjøp 1 time happy hour";
                $boost_info[14] = "Kjøp 3 time happy hour";
                $boost_info[15] = "Kjøp 6 time happy hour";
                $boost_info[16] = "Kjøp 12 time happy hour";
                $boost_info[17] = "Kjøp 24 time happy hour";

                $point_icons[0] = "img/poeng/type_0.png";
                $point_icons[1] = "img/poeng/type_1.png";
                $point_icons[2] = "img/poeng/type_2.png";
                $point_icons[3] = "img/poeng/type_3.png";
                $point_icons[4] = "img/poeng/type_4.png";
                $point_icons[5] = "img/poeng/type_5.png";
                $point_icons[6] = "img/poeng/type_6.png";
                $point_icons[7] = "img/poeng/type_7.png";
                $point_icons[8] = "img/poeng/type_8.png";
                $point_icons[9] = "img/poeng/type_9.png";
                $point_icons[10] = "img/poeng/type_10.png";
                $point_icons[11] = "img/poeng/type_11.png";
                $point_icons[12] = "img/poeng/type_12.png";
                $point_icons[13] = "img/poeng/type_13.png";
                $point_icons[14] = "img/poeng/type_14.png";
                $point_icons[15] = "img/poeng/type_15.png";
                $point_icons[16] = "img/poeng/type_16.png";
                $point_icons[17] = "img/poeng/type_17.png";

                if (isset($_GET['bonus'])) {
                    $legal = array(0, 1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 14, 15, 16, 17);
                    if (in_array($_GET['bonus'], $legal)) {
                        if (AS_session_row($_SESSION['ID'], 'AS_lyddemper', $pdo) != 0 && $_GET['bonus'] == 6) {
                            echo feedback("Du har allerede lyddemper", "fail");
                        } elseif (AS_session_row($_SESSION['ID'], 'AS_boost', $pdo) == 1 && $_GET['bonus'] == 9) {
                            echo feedback("Du har allerede x2 boost på smugling", "fail");
                        } elseif ($boost_price[$_GET['bonus']] > AS_session_row($_SESSION['ID'], 'AS_points', $pdo)) {
                            echo feedback("Du har ikke nok poeng for denne handelen.", "fail");
                        } else {
                            if ($_GET['bonus'] == 0) {
                                $new_protection = AS_session_row($_SESSION['ID'], 'AS_protection', $pdo) + 1;
                                $sql = "UPDATE accounts_stat SET AS_protection = ? WHERE AS_id = ?";
                                $pdo->prepare($sql)->execute([$new_protection, $_SESSION['ID']]);
                            } elseif ($_GET['bonus'] == 1) {
                                $new_garage = 10;
                                upgrade_garage($_SESSION['ID'], $new_garage, $pdo);
                            } elseif ($_GET['bonus'] == 2) {
                                $new_garage = 10;
                                upgrade_thing($_SESSION['ID'], $new_garage, $pdo);
                            } elseif ($_GET['bonus'] == 3) {
                                if (get_ransoffer($_SESSION['ID'], $pdo)) {
                                    $rob_offer_id = get_ransoffer($_SESSION['ID'], $pdo);
                                    $rob_offer = username_plain($rob_offer_id, $pdo);

                                    $text = "Jeg fant ett ransoffer til deg. " . $rob_offer . " skal ikke ha beskyttelse.";

                                    send_notification($_SESSION['ID'], $text, $pdo);
                                } else {
                                    $text = "Jeg fant dessverre ingen ransoffer til deg..";

                                    send_notification($_SESSION['ID'], $text, $pdo);
                                }
                            } elseif ($_GET['bonus'] == 4) {
                                $sql = "UPDATE city_tax SET CTAX_tax = 0 WHERE CTAX_city = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);
                            } elseif ($_GET['bonus'] == 5) {
                                $amount = 10000;

                                give_fp($amount, $_SESSION['ID'], $pdo);
                            } elseif ($_GET['bonus'] == 6) {
                                $sql = "UPDATE accounts_stat SET AS_lyddemper = 1 WHERE AS_id = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$_SESSION['ID']]);
                            } elseif ($_GET['bonus'] == 7) {
                                $energidrikk = 29;
                                update_things($_SESSION['ID'], $energidrikk, $pdo);
                            } elseif ($_GET['bonus'] == 8) {
                                echo feedback("Ugyldig bonus", "blue");
                            } elseif ($_GET['bonus'] == 9) {
                                $sql = "UPDATE accounts_stat SET AS_boost = 1 WHERE AS_id = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$_SESSION['ID']]);
                            } elseif ($_GET['bonus'] == 10) {
                                $energidrikk = 32;
                                update_things($_SESSION['ID'], $energidrikk, $pdo);
                            } elseif ($_GET['bonus'] == 11 || $_GET['bonus'] == 12) {
                                if ($_GET['bonus'] == 11) {
                                    $money_for_owner = 25000000;
                                    $waittime = 604800 + time();
                                } elseif ($_GET['bonus'] == 12) {
                                    $money_for_owner = 35000000;
                                    $waittime = 1209600 + time();
                                }

                                $query = $pdo->prepare("SELECT BUNCHO_acc_id, BUNCHO_bunkerID FROM bunker_chosen WHERE BUNCHO_city = ? AND BUNCHO_acc_id = ?");
                                $query->execute(array(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $_SESSION['ID']));
                                $row_buncho = $query->fetch(PDO::FETCH_ASSOC);

                                $sql = "INSERT INTO bunker_active (BUNACT_acc_id, BUNACT_status, BUNACT_cooldown) VALUES (?,?,?)";
                                $pdo->prepare($sql)->execute([$_SESSION['ID'], 1, $waittime]);

                                $sql = "INSERT INTO bunker_log (BUNK_acc_id, BUNK_in, BUNK_city) VALUES (?,?,?)";
                                $pdo->prepare($sql)->execute([$_SESSION['ID'], time(), AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                                if ($row_buncho) {
                                    update_bedrift_inntekt($_SESSION['ID'], 5, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $money_for_owner, $pdo);

                                    if ($money_for_owner != 500000) {
                                        $sql = "UPDATE bunker_owner SET BUNOWN_bank = BUNOWN_bank + ? WHERE BUNOWN_city = ? ";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([$money_for_owner, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);
                                    }
                                }

                                header("Location: ?side=drap&p=bunker&bunker_status=in");
                            } elseif ($_GET['bonus'] == 13) {
                                $happyhour = 34;
                                update_things($_SESSION['ID'], $happyhour, $pdo);
                            } elseif ($_GET['bonus'] == 14) {
                                $happyhour = 35;
                                update_things($_SESSION['ID'], $happyhour, $pdo);
                            } elseif ($_GET['bonus'] == 15) {
                                $happyhour = 36;
                                update_things($_SESSION['ID'], $happyhour, $pdo);
                            } elseif ($_GET['bonus'] == 16) {
                                $happyhour = 37;
                                update_things($_SESSION['ID'], $happyhour, $pdo);
                            } elseif ($_GET['bonus'] == 17) {
                                $happyhour = 38;
                                update_things($_SESSION['ID'], $happyhour, $pdo);
                            } else {
                                echo feedback('ugyldig ID', 'fail');
                            }

                            take_poeng($_SESSION['ID'], $boost_price[$_GET['bonus']], $pdo);

                            header("Location: ?side=poeng&bonus_bought=" . $_GET['bonus'] . "");
                        }
                    } else {
                        echo feedback("Ugyldig handling", "fail");
                    }
                }

                if (isset($_GET['bonus_bought'])) {
                    $legal = array(0, 1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 14, 15, 16, 17);

                    $boost[6] = "Lyddemper";

                    if (in_array($_GET['bonus_bought'], $legal)) {
                        if ($_GET['bonus_bought'] == 4) {
                            echo feedback("Du satt ned byskatten til 0% i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)), "success");
                        } else {
                            echo feedback("Du kjøpte " . $boost[$_GET['bonus_bought']] . "", "success");
                        }
                    } else {
                        echo feedback("Ugyldig handling", "fail");
                    }
                }

                ?>
                <h3 style="margin: 10px 0px;">Poenghandel</h3>
                <table>
                    <tr>
                        <th>Fordel</th>
                        <th style="width: 20%;">Pris</th>
                    </tr>
                    <?php for ($p = 0; $p < count($boost); $p++) {

                        if ($p == 6 && AS_session_row($_SESSION['ID'], 'AS_lyddemper', $pdo) == 1 || $p == 9 && AS_session_row($_SESSION['ID'], 'AS_boost', $pdo) == 1 || $p == 8) {
                        } else { ?>
                            <tr class="cursor_hover clickable-row" data-href="?side=poeng&bonus=<?= $p; ?>">
                                <td><span style="color: white;"><b><?php echo $boost[$p]; ?></b></span>
                                    <p class="description"><?php echo $boost_info[$p]; ?></p>
                                </td>
                                <td style="color: white;"><?php echo $boost_price[$p]; ?> poeng</td>
                            </tr>
                    <?php }
                    } ?>
                </table>
            </div>
        </div>
    </div>
<?php
} else {
    if (isset($_GET['betingelser'])) {
        include_once 'betingelser.php';
    }

    if (isset($_POST['formSubmit'])) {
        if ($_POST['formAcceptTerms'] == "Yes") {

            $sql = "INSERT INTO poeng_betingelse (PBET_acc_id, PBET_date) VALUES (?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_SESSION['ID'], time()]);

            header("Location: ?side=poeng&terms_accept");
        }
    }
?>
    <div class="col-8 single">
        <div class="content">
            <div class="header">
                <h4>Poenghandel</h4>
            </div>
            <p>
                Poeng er en egen valuta du kan bruke på mafioso for å kjøpe diverse goder. Poeng kan kjøpes av andre spillere eller med bankkort. Vi anbefaler kun de med eget bankkort å kjøpe poeng på Mafioso. Om du av spesielle grunner ikke har mulighet til å kjøpe poeng med bankkort, ta konkakt på support så finner vi en løsning for deg.<br><br>
                Før du kan kjøpe poeng på Mafioso må du godkjenne betingelsene her:
            </p>
            <a href="?side=poeng&betingelser"><img class="header_icon" src="img/icons/page_link.png"> Betingelser</a>
            <form method="post">
                <br>

                <input type="checkbox" name="formAcceptTerms" value="Yes" required />
                Jeg godkjenner å ha lest gjennom betingelsene og er forstått med det som betingelsen informerer om.
                <br>
                <input type="submit" name="formSubmit" value="Aksepter" />
            </form>
        </div>
    </div>
<?php

}

?>

<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });

    $('#card_number').on('keypress change', function() {
        $(this).val(function(index, value) {
            return value.replace(/\W/gi, '').replace(/(.{4})/g, '$1 ');
        });
    });

    // logged in user id or cart id = 123456
    function expressCheckout(PRICE_ID, PRO_NAME) {
        var stripe = Stripe('<?php echo $stripe_public_key; ?>');
        stripe.redirectToCheckout({
            lineItems: [
                // Replace with the ID of your price
                {
                    price: PRICE_ID,
                    quantity: 1
                }
            ],
            mode: 'payment',
            successUrl: 'https://mafioso.no/index.php?side=poeng&status=success',
            cancelUrl: 'https://mafioso.no/index.php?side=poeng&status=cancel',
            clientReferenceId: PRO_NAME,
        }).then(function(result) {
            console.log(result.error.message);
            // If `redirectToCheckout` fails due to a browser or network
            // error, display the localized error message to your customer
            // using `result.error.message`.
        });
    }
</script>
<style>
    .hover_click:hover {
        cursor: pointer;
    }

    .pad_10 h3 {
        text-align: center;
    }

    .points_box:hover {
        cursor: pointer;
        background-color: var(--ready-color);
        color: black;
    }

    .points_container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        gap: 1rem;
    }

    .points_box {
        color: white;
        padding: 1rem;
        margin: 4;
        padding: 2 5;
        border-radius: 5px;
        background-color: var(--main-bg-color);
        text-align: center;
    }

    @media (min-width: 600px) {
        .points_container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 900px) {
        .points_container {
            grid-template-columns: repeat(6, 1fr);
        }
    }
</style>