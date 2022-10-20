<?php

include 'functions/blackjack.php';

// Kategorier
$category[0] = "Biler";
$category[1] = "Ting";
$category[2] = "Poeng";
$category[3] = "Kuler";
$category[4] = "Profil-ikon";
$category[5] = "Firma";
$category[6] = "Cannabis";

// typer firma
$firma_type[0] = "Flyplass";
$firma_type[1] = "Kulefabrikk";
$firma_type[2] = "Fengsel";
$firma_type[3] = "Streetrace";
$firma_type[4] = "Blackjack";
$firma_type[5] = "Bunker";

// feedback
if (isset($_GET['feedback'])) {
    if ($_GET['feedback'] == 'remove') {
        echo feedback('Du fjernet salget fra marked', "success");
    }
    if ($_GET['feedback'] == 'list') {
        echo feedback("Du la ut objekt til salgs på marked", "success");
    }
    if ($_GET['feedback'] == 'buy') {
        echo feedback("Du kjøpte fra marked", "success");
    }
    if ($_GET['feedback'] == 'toomany') {
        echo feedback("Du eier for mange firma", "error");
    }
}

if (isset($_GET['notEnoghSpaceCar'])) {
    echo feedback('Du har ikke plass i garasjen til antall biler', 'error');
}

if (isset($_GET['notEnoghSpaceThings'])) {
    echo feedback('Du har ikke plass i garasjen til antall biler', 'error');
}

if (isset($_GET['buy'])) {
    if (is_numeric($_GET['buy']) && marked_exist($_GET['buy'], $pdo)) {
        $query = $pdo->prepare("SELECT * FROM marked WHERE MARKED_id=?");
        $query->execute(array($_GET['buy']));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        $marked_seller = $row['MARKED_seller'];
        $marked_cat = $row['MARKED_cat'];
        $marked_private = $row['MARKED_private'];
        $marked_info = $row['MARKED_info'];
        $marked_price = $row['MARKED_price'];
        $marked_date = $row['MARKED_date'];

        if ($marked_cat == 0) {
            $obj = json_decode($marked_info);
            $amount = $obj->car_amount;

            if (($amount + total_cars($_SESSION['ID'], $pdo)) > $max_garage) {
                header("Location: ?side=marked&notEnoghSpaceCar");
            }
        } elseif ($marked_cat == 1) {
            $obj = json_decode($marked_info);
            $amount = $obj->amount;

            if (($amount + total_things($_SESSION['ID'], $pdo)) > $max_storage) {
                header("Location: ?side=marked&notEnoghSpaceThings");
            }
        }


        if ($marked_private != null && $marked_private != $_SESSION['ID']) {
            echo feedback("Salget er ikke for deg", "error");
        } elseif ($marked_seller == $_SESSION['ID']) {
            echo feedback("Du kan ikke kjøpe dine egne ting", "error");
        } elseif ($marked_date < time()) {
            echo feedback("Tiden er utgått", "error");
        } elseif ($marked_price > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
            echo feedback("Du har ikke nok penger til å kjøpe", "error");
        } else {
            if ($marked_cat == 0) {
                $jsonobj = $marked_info;

                $obj = json_decode($jsonobj);

                $car_id = $obj->car_id;
                $city = $obj->car_city;
                $amount = $obj->car_amount;

                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['buy'] . "";
                $pdo->exec($sql);

                for ($i = 0; $i < $amount; $i++) {
                    update_garage($_SESSION['ID'], $car_id, $city, $pdo);
                }

                send_notification($marked_seller, "Du solgte en bil på marked og fikk " . number($marked_price) . " kr", $pdo);
            } elseif ($marked_cat == 1) {
                $jsonobj = $marked_info;

                $obj = json_decode($jsonobj);

                $thing_type = $obj->thing_type;
                $amount = $obj->amount;

                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['buy'] . "";
                $pdo->exec($sql);

                for ($i = 0; $i < $amount; $i++) {
                    update_things($_SESSION['ID'], $thing_type, $pdo);
                }

                send_notification($marked_seller, "Du solgte ting på marked og fikk " . number($marked_price) . " kr", $pdo);
            } elseif ($marked_cat == 2) {
                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['buy'] . "";
                $pdo->exec($sql);
                give_poeng($_SESSION['ID'], $marked_info, $pdo);

                send_notification($marked_seller, "Du solgte poeng på marked og fikk " . number($marked_price) . " kr", $pdo);
            } elseif ($marked_cat == 3) {
                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['buy'] . "";
                $pdo->exec($sql);
                give_bullets($_SESSION['ID'], $marked_info, $pdo);

                send_notification($marked_seller, "Du solgte kuler på marked og fikk " . number($marked_price) . " kr", $pdo);
            } elseif ($marked_cat == 4) {
                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['buy'] . "";
                $pdo->exec($sql);

                $sql = "INSERT INTO charm (CH_acc_id, CH_charm) VALUES (?,?)";
                $pdo->prepare($sql)->execute([$_SESSION['ID'], $marked_info]);

                send_notification($marked_seller, "Du solgte et profil-ikon på marked og fikk " . number($marked_price) . " kr", $pdo);
            } elseif ($marked_cat == 5) {
                if (
                    !max_eiendeler($_SESSION['ID'], $pdo)
                    ||
                    $firma_type == 0 && airport_owner_already($_SESSION['ID'], $pdo)
                    ||
                    $firma_type == 1 && kf_owner($_SESSION['ID'], $pdo)
                    ||
                    $firma_type == 2 && jail_owner_already($_SESSION['ID'], $pdo)
                    ||
                    $firma_type == 3 && race_club_owner_already($_SESSION['ID'], $pdo)
                    ||
                    $firma_type == 4 && blackjack_owner_amount($_SESSION['ID'], $pdo)
                    ||
                    $firma_type == 5 && bunker_owner_already($_SESSION['ID'], $pdo)
                ) {

                    $jsonobj = $marked_info;

                    $obj = json_decode($jsonobj);

                    $firma_type = $obj->type;
                    $city = $obj->city;

                    if ($firma_type == 0) {
                        $sql = "INSERT INTO flyplass (FP_city, FP_owner) VALUES (?,?)";
                        $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                    }
                    if ($firma_type == 1) {
                        $sql = "INSERT INTO kf_owner (KFOW_city, KFOW_acc_id) VALUES (?,?)";
                        $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                    }
                    if ($firma_type == 2) {
                        $sql = "INSERT INTO fengsel_direktor (FENGDI_city, FENGDI_acc_id) VALUES (?,?)";
                        $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                    }
                    if ($firma_type == 3) {
                        $sql = "INSERT INTO rc_owner (RCOWN_city, RCOWN_acc_id) VALUES (?,?)";
                        $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                    }
                    if ($firma_type == 4) {
                        $sql = "INSERT INTO blackjack_owner (BJO_city, BJO_owner) VALUES (?,?)";
                        $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                    }
                    if ($firma_type == 5) {
                        $sql = "INSERT INTO bunker_owner (BUNOWN_city, BUNOWN_acc_id) VALUES (?,?)";
                        $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                    }

                    send_notification($marked_seller, "Du solgte et firma på marked og fikk " . number($marked_price) . " kr", $pdo);

                    $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['buy'] . "";
                    $pdo->exec($sql);
                } else {
                    header("Location: ?side=marked&id=" . $marked_cat . "&feedback=toomany");
                }
            } elseif ($marked_cat == 6) {
                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['buy'] . "";
                $pdo->exec($sql);
                give_weed($_SESSION['ID'], $marked_info, $pdo);

                send_notification($marked_seller, "Du solgte cannabis på marked og fikk " . number($marked_price) . " kr", $pdo);
            }

            if (AS_session_row($marked_seller, 'AS_mission', $pdo) == 24) {
                mission_update(AS_session_row($marked_seller, 'AS_mission_count', $pdo) + $marked_price, AS_session_row($marked_seller, 'AS_mission', $pdo), mission_criteria(AS_session_row($marked_seller, 'AS_mission', $pdo)), $marked_seller, $pdo);
            }

            give_money($marked_seller, $marked_price * 0.9, $pdo);
            take_money($_SESSION['ID'], $marked_price, $pdo);

            if (isset($_GET['id'])) {
                header("Location: ?side=marked&id=" . $marked_cat . "&feedback=buy");
            } else {
                header("Location: ?side=marked&feedback=buy");
            }
        }
    } else {
        echo feedback($useLang->error->voidID, 'error');
    }
}

if (isset($_GET['remove'])) {
    if (is_numeric($_GET['remove']) && marked_exist($_GET['remove'], $pdo)) {
        $query = $pdo->prepare("SELECT * FROM marked WHERE MARKED_id=?");
        $query->execute(array($_GET['remove']));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        $marked_seller = $row['MARKED_seller'];
        $marked_cat = $row['MARKED_cat'];
        $marked_private = $row['MARKED_private'];
        $marked_info = $row['MARKED_info'];
        $marked_price = $row['MARKED_price'];
        $marked_date = $row['MARKED_date'];

        if ($marked_seller != $_SESSION['ID']) {
            echo feedback("Du kan ikke fjerne andres salg", "error");
        } else {
            if ($marked_cat == 0) {
                $jsonobj = $marked_info;

                $obj = json_decode($jsonobj);

                $car_id = $obj->car_id;
                $city = $obj->car_city;
                $amount = $obj->car_amount;

                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['remove'] . "";
                $pdo->exec($sql);

                for ($i = 0; $i < $amount; $i++) {
                    update_garage($marked_seller, $car_id, $city, $pdo);
                }
            } elseif ($marked_cat == 1) {
                $jsonobj = $marked_info;

                $obj = json_decode($jsonobj);

                $thing_type = $obj->thing_type;
                $amount = $obj->amount;

                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['remove'] . "";
                $pdo->exec($sql);

                for ($i = 0; $i < $amount; $i++) {
                    update_things($marked_seller, $thing_type, $pdo);
                }
            } elseif ($marked_cat == 2) {
                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['remove'] . "";
                $pdo->exec($sql);
                give_poeng($marked_seller, $marked_info, $pdo);
            } elseif ($marked_cat == 3) {
                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['remove'] . "";
                $pdo->exec($sql);
                give_bullets($marked_seller, $marked_info, $pdo);
            } elseif ($marked_cat == 4) {
                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['remove'] . "";
                $pdo->exec($sql);

                $sql = "INSERT INTO charm (CH_acc_id, CH_charm) VALUES (?,?)";
                $pdo->prepare($sql)->execute([$marked_seller, $marked_info]);
            } elseif ($marked_cat == 5) {
                $jsonobj = $marked_info;

                $obj = json_decode($jsonobj);

                $firma_type = $obj->type;
                $city = $obj->city;

                if ($firma_type == 0) {
                    $sql = "INSERT INTO flyplass (FP_city, FP_owner) VALUES (?,?)";
                    $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                }
                if ($firma_type == 1) {
                    $sql = "INSERT INTO kf_owner (KFOW_city, KFOW_acc_id) VALUES (?,?)";
                    $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                }
                if ($firma_type == 2) {
                    $sql = "INSERT INTO fengsel_direktor (FENGDI_city, FENGDI_acc_id) VALUES (?,?)";
                    $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                }
                if ($firma_type == 3) {
                    $sql = "INSERT INTO rc_owner (RCOWN_city, RCOWN_acc_id) VALUES (?,?)";
                    $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                }
                if ($firma_type == 4) {
                    $sql = "INSERT INTO blackjack_owner (BJO_city, BJO_owner) VALUES (?,?)";
                    $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                }
                if ($firma_type == 5) {
                    $sql = "INSERT INTO bunker_owner (BUNOWN_city, BUNOWN_acc_id) VALUES (?,?)";
                    $pdo->prepare($sql)->execute([$city, $_SESSION['ID']]);
                }

                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['remove'] . "";
                $pdo->exec($sql);
            } elseif ($marked_cat == 6) {
                $sql = "DELETE FROM marked WHERE MARKED_id = " . $_GET['remove'] . "";
                $pdo->exec($sql);
                give_weed($marked_seller, $marked_info, $pdo);
            }

            if (isset($_GET['id'])) {
                header("Location: ?side=marked&id=" . $marked_cat . "&feedback=remove");
            } else {
                header("Location: ?side=marked&feedback=remove");
            }
        }
    } else {
        echo feedback($useLang->error->voidID, 'error');
    }
}

if (!isset($_GET['id'])) {

?>
    <div class="col-10 single">
        <div class="content">
            <h4>Marked</h4>
            <p class="description">På marked kan du kjøpe og selge ting fra andre mafiosoer</p>

            <?php for ($i = 0; $i < count($category); $i++) { ?>
                <div class="col-4">
                    <a href="?side=marked&id=<?php echo $i; ?>"><img style="margin-bottom: 2px;" class="action_image" src="img/marked/<?php echo $i; ?>.png"></a>
                    <center class="description" <?php if (marked_listings($i, $pdo) > 0) {
                                                    echo 'style="color: var(--ready-color);"';
                                                } ?>><?php echo number(marked_listings($i, $pdo)); ?> salg</center>
                </div>
            <?php } ?>
            <div style="clear: both;"></div>
        </div>

        <?php // include 'app/marked/onsket_kjopt.php'; 
        ?>

        <div class="content">
            <h4>Oversikt</h4>
            <p class="description">Under vil du se dine salg, kjøpstilbud for deg og private salg som du har lagt ut for andre.</p>
            <?php include 'app/marked/privat.php'; ?>
        </div>
    </div>

    <?php } else {
    if (array_key_exists($_GET['id'], $category)) {
        $id = $_GET['id'];
    ?>
        <div class="col-8 single" style="margin-bottom: 500px;">
            <div class="content">
                <h4 style="margin-bottom: 10px;"><?php echo $category[$_GET['id']]; ?></h4>
                <table id="table_id" class="display" style="padding-top: 10px;">
                    <thead>
                        <tr>
                            <th>Beskrivelse</th>
                            <th>Pris</th>
                            <th>Selger</th>
                            <th style="width: 15%;">Handling</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $query = $pdo->prepare('SELECT * FROM marked WHERE MARKED_cat=:cat AND MARKED_private = :null');
                        $query->execute(array(':cat' => $_GET['id'], 'null' => ' '));
                        foreach ($query as $row) {

                            $info = null;

                            if ($row['MARKED_cat'] == 0) { ?>
                                <tr>
                                    <td>
                                        <?php
                                        $jsonobj = $row['MARKED_info'];

                                        $obj = json_decode($jsonobj);

                                        echo number($obj->car_amount);
                                        echo ' stk ';
                                        echo car($obj->car_id);
                                        echo ' i ';
                                        echo city_name($obj->car_city);
                                        ?>
                                    </td>
                                    <td><?php echo number($row['MARKED_price']); ?> kr
                                    </td>
                                    <td><?php echo ACC_username($row['MARKED_seller'], $pdo); ?></td>
                                    <td>
                                        <?php

                                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                                        } else {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                                        }

                                        ?>
                                    </td>
                                </tr>
                            <?php } elseif ($row['MARKED_cat'] == 1) { ?>
                                <tr>
                                    <td>
                                        <?php
                                        $jsonobj = $row['MARKED_info'];

                                        $obj = json_decode($jsonobj);

                                        echo thing($obj->thing_type) . ' ';
                                        echo number($obj->amount);
                                        echo ' stk ';
                                        ?>
                                    </td>
                                    <td><?php echo number($row['MARKED_price']); ?> kr</td>
                                    <td><?php echo ACC_username($row['MARKED_seller'], $pdo); ?></td>
                                    <td>
                                        <?php

                                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                                        } else {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                                        }

                                        ?>
                                    </td>
                                </tr>
                            <?php } elseif ($row['MARKED_cat'] == 2) { ?>
                                <tr>
                                    <td><?php echo number($row['MARKED_info']); ?> poeng</td>
                                    <td><?php echo number($row['MARKED_price']); ?> kr
                                        <br>
                                        <p class="description" style="margin: 0;">Pris pr poeng: <?php echo number(($row['MARKED_price'] / $row['MARKED_info'])); ?> kr</p>
                                    </td>
                                    <td><?php echo ACC_username($row['MARKED_seller'], $pdo); ?></td>
                                    <td>
                                        <?php

                                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                                        } else {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                                        }

                                        ?>
                                    </td>
                                </tr>
                            <?php } elseif ($row['MARKED_cat'] == 3) { ?>
                                <tr>
                                    <td><?php echo number($row['MARKED_info']); ?> kuler</td>
                                    <td><?php echo number($row['MARKED_price']); ?> kr
                                        <br>
                                        <p class="description" style="margin: 0;">Pris pr kule: <?php echo number(($row['MARKED_price'] / $row['MARKED_info'])); ?> kr</p>
                                    </td>
                                    <td><?php echo ACC_username($row['MARKED_seller'], $pdo); ?></td>
                                    <td>
                                        <?php

                                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                                        } else {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                                        }

                                        ?>
                                    </td>
                                </tr>
                            <?php } elseif ($row['MARKED_cat'] == 4) { ?>
                                <tr>
                                    <td><img style="height: 30px;" src="<?php echo charm($row['MARKED_info']); ?>"></td>
                                    <td><?php echo number($row['MARKED_price']); ?> kr
                                    </td>
                                    <td><?php echo ACC_username($row['MARKED_seller'], $pdo); ?></td>
                                    <td>
                                        <?php

                                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                                        } else {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                                        }

                                        ?>
                                    </td>
                                </tr>
                            <?php } elseif ($row['MARKED_cat'] == 5) { ?>
                                <tr>
                                    <td>
                                        <?php

                                        $jsonobj = $row['MARKED_info'];

                                        $obj = json_decode($jsonobj);

                                        echo $firma_type[($obj->type)];
                                        echo ' i ';
                                        echo city_name($obj->city);
                                        echo '<br>';
                                        echo '<a class="description" href="?side=bedrift_oversikt&city=' . $obj->city . '&type=' . $obj->type . '">Sjekk inntekt</a>';
                                        ?>
                                    </td>
                                    <td><?php echo number($row['MARKED_price']); ?> kr
                                    </td>
                                    <td><?php echo ACC_username($row['MARKED_seller'], $pdo); ?></td>
                                    <td>
                                        <?php

                                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                                        } else {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                                        }

                                        ?>
                                    </td>
                                </tr>
                            <?php } elseif ($row['MARKED_cat'] == 6) {

                            ?>
                                <tr>
                                    <td><?php echo number($row['MARKED_info']); ?> g cannabis</td>
                                    <td><?php echo number($row['MARKED_price']); ?> kr
                                    </td>
                                    <td><?php echo ACC_username($row['MARKED_seller'], $pdo); ?></td>
                                    <td>
                                        <?php

                                        if ($row['MARKED_seller'] == $_SESSION['ID']) {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&remove=' . $row['MARKED_id'] . '">Fjern</a>';
                                        } else {
                                            echo '<a class="a_as_button" href="?side=marked&id=' . $row['MARKED_cat'] . '&buy=' . $row['MARKED_id'] . '">Kjøp</a>';
                                        }

                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } ?>
                    </tbody>
                </table>
                <?php
                $max_price = 1000000000000; // 1 000 000 000 000

                if (isset($_POST['sell'])) {
                    if (total_marked_listings($_SESSION['ID'], $pdo) < 10) {
                        $price = remove_space($_POST['price']);
                        $private = $_POST['username'];

                        if (is_numeric($price) && $price > 0 && $price < $max_price) {

                            if (!empty($private)) {
                                if (user_exist($private, $pdo)) {
                                    $private = get_acc_id($private, $pdo);
                                } else {
                                    $private = '';
                                }
                            }

                            if ($id == 0) {
                                $car_id = $_POST['car_id'];
                                $antall = $_POST['amount'];

                                $car_id = explode(":", $car_id);

                                $id_car = $car_id[0];
                                $city = $car_id[1];

                                $stmt = $pdo->prepare("SELECT * FROM garage WHERE GA_acc_id = ? AND GA_car_id = ? AND GA_car_city = ?");
                                $stmt->execute([$_SESSION['ID'], $id_car, $city]);
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($row) {
                                    if (is_numeric($antall) && $antall > 0 && $antall <= amount_of_car($_SESSION['ID'], $row['GA_car_city'], $row['GA_car_id'], $pdo)) {
                                        if ($row['GA_acc_id'] == $_SESSION['ID']) {
                                            $car_info = array('car_id' => $row['GA_car_id'], 'car_city' => $row['GA_car_city'], 'car_amount' => $antall);

                                            for ($i = 0; $i < $antall; $i++) {
                                                $sql = "DELETE FROM garage WHERE GA_acc_id = " . $_SESSION['ID'] . " AND GA_car_id = " . $row['GA_car_id'] . " AND GA_car_city = " . $row['GA_car_city'] . " LIMIT 1";
                                                $pdo->exec($sql);
                                            }

                                            market_list($_SESSION['ID'], $id, $private, json_encode($car_info), $price, $pdo);

                                            header("Location: ?side=marked&id=" . $id . "&feedback=list");
                                        } else {
                                            echo feedback("Du er ikke eieren av bilen", "error");
                                        }
                                    } else {
                                        echo feedback("Ugyldig antall", "error");
                                    }
                                } else {
                                    echo feedback($useLang->error->voidID, 'error');
                                }
                            } elseif ($id == 1) {
                                $thing_type = $_POST['thing_id'];
                                $amount = $_POST['amount'];

                                if (!is_numeric($amount) || $amount < 1 || $amount > amount_of_things($_SESSION['ID'], $thing_type, $pdo)) {
                                    echo feedback("Ugyldig antall", "error");
                                } else {
                                    $thing_info = array('thing_type' => $thing_type, 'amount' => $amount);

                                    for ($i = 0; $i < $amount; $i++) {
                                        $sql = "DELETE FROM things WHERE TH_type = " . $thing_type . " AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                                        $pdo->exec($sql);
                                    }

                                    market_list($_SESSION['ID'], $id, $private, json_encode($thing_info), $price, $pdo);

                                    header("Location: ?side=marked&id=" . $id . "&feedback=list");
                                }
                            } elseif ($id == 2) {
                                $points = remove_space($_POST['points']);

                                if (!is_numeric($points) || $points < 1) {
                                    echo feedback("Ugyldig antall", "error");
                                } else {
                                    if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) >= $points) {
                                        market_list($_SESSION['ID'], $id, $private, $points, $price, $pdo);
                                        take_poeng($_SESSION['ID'], $points, $pdo);

                                        header("Location: ?side=marked&id=" . $id . "&feedback=list");
                                    } else {
                                        echo feedback("Du kan ikke selge flere poeng enn du har", "error");
                                    }
                                }
                            } elseif ($id == 3) {
                                $bullets = remove_space($_POST['bullets']);

                                if (!is_numeric($bullets) || $bullets < 1) {
                                    echo feedback("Ugyldig antall", "error");
                                } else {
                                    if (AS_session_row($_SESSION['ID'], 'AS_bullets', $pdo) >= $bullets) {
                                        market_list($_SESSION['ID'], $id, $private, $bullets, $price, $pdo);
                                        take_bullets($_SESSION['ID'], $bullets, $pdo);

                                        header("Location: ?side=marked&id=" . $id . "&feedback=list");
                                    } else {
                                        echo feedback("Du kan ikke selge flere kuler enn du har", "error");
                                    }
                                }
                            } elseif ($id == 4) {
                                $charm_id = $_POST['charm'];

                                $stmt = $pdo->prepare("SELECT * FROM charm WHERE CH_id = ?");
                                $stmt->execute([$charm_id]);
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($row) {
                                    if ($row['CH_acc_id'] == $_SESSION['ID']) {
                                        $sql = "DELETE FROM charm WHERE CH_id = " . $charm_id . "";
                                        $pdo->exec($sql);

                                        market_list($_SESSION['ID'], $id, $private, $row['CH_charm'], $price, $pdo);

                                        header("Location: ?side=marked&id=" . $id . "&feedback=list");
                                    } else {
                                        echo feedback("Du er ikke eieren av profil-ikonet", "error");
                                    }
                                } else {
                                    echo feedback($useLang->error->voidID, 'error');
                                }
                            } elseif ($id == 5) {
                                $firma = $_POST['firma'];

                                $exploded = explode(":", $firma);

                                $firma_type = $exploded[0];
                                $city = $exploded[1];

                                if (
                                    $firma_type == 0 && (airport_owner($city, $pdo) == $_SESSION['ID'])
                                    ||
                                    $firma_type == 1 && (get_kf_owner($city, $pdo) == $_SESSION['ID'])
                                    ||
                                    $firma_type == 2 && (get_fengselsdirektor($city, $pdo) == $_SESSION['ID'])
                                    ||
                                    $firma_type == 3 && (race_club_owner($city, $pdo) == $_SESSION['ID'])
                                    ||
                                    $firma_type == 4 && (blackjack_owner_incity($city, $pdo) == $_SESSION['ID'])
                                    ||
                                    $firma_type == 5 && (bunker_owner($city, $pdo) == $_SESSION['ID'])
                                ) {
                                    $firma_info = array('type' => $firma_type, 'city' => $city);
                                    market_list($_SESSION['ID'], $id, $private, json_encode($firma_info), $price, $pdo);

                                    if ($firma_type == 0) {
                                        $sql = "DELETE FROM flyplass WHERE FP_owner = " . $_SESSION['ID'] . " AND FP_city = " . $city . "";
                                        $pdo->exec($sql);
                                    }
                                    if ($firma_type == 1) {
                                        $sql = "DELETE FROM kf_owner WHERE KFOW_acc_id = " . $_SESSION['ID'] . " AND KFOW_city = " . $city . "";
                                        $pdo->exec($sql);
                                    }
                                    if ($firma_type == 2) {
                                        $sql = "DELETE FROM fengsel_direktor WHERE FENGDI_acc_id = " . $_SESSION['ID'] . " AND FENGDI_city = " . $city . "";
                                        $pdo->exec($sql);
                                    }
                                    if ($firma_type == 3) {
                                        $sql = "DELETE FROM rc_owner WHERE RCOWN_acc_id = " . $_SESSION['ID'] . " AND RCOWN_city = " . $city . "";
                                        $pdo->exec($sql);
                                    }
                                    if ($firma_type == 4) {
                                        $sql = "DELETE FROM blackjack_owner WHERE BJO_owner = " . $_SESSION['ID'] . " AND BJO_city = " . $city . "";
                                        $pdo->exec($sql);
                                    }
                                    if ($firma_type == 5) {
                                        shut_down_bunker($_SESSION['ID'], $pdo);
                                    }

                                    header("Location: ?side=marked&id=" . $id . "&feedback=list");
                                } else {
                                    echo feedback("Ugyldig firma", "error");
                                }
                            } elseif ($id == 6) {
                                $cannabis = $_POST['cannabis'];

                                if (!is_numeric($cannabis) || $cannabis < 1) {
                                    echo feedback("Ugyldig antall", "error");
                                } else {
                                    if (AS_session_row($_SESSION['ID'], 'AS_weed', $pdo) >= $cannabis) {
                                        market_list($_SESSION['ID'], $id, $private, $cannabis, $price, $pdo);
                                        take_weed($_SESSION['ID'], $cannabis, $pdo);

                                        header("Location: ?side=marked&id=" . $id . "&feedback=list");
                                    } else {
                                        echo feedback("Du kan ikke selge flere gram cannabis enn du har", "error");
                                    }
                                }
                            }
                        } else {
                            echo feedback("Ugyldig pris", "error");
                        }
                    } else {
                        echo feedback("Du kan maks ha 10 salg ute på likt", "error");
                    }
                }


                ?>
                <form method="post">
                    <h4 style="margin-top: 10px;">Legg ut for salg</h4>
                    <div class="col-6">
                        <?php if ($id == 0) { ?>
                            <p>Velg bil</p>
                            <div class="custom-select" style="width:100%;">
                                <select style="width: 100%;" name="car_id" required>
                                    <option value="null">Velg bil:</option>
                                    <?php

                                    $sql = "SELECT GA_id, GA_car_id, GA_car_city FROM garage WHERE GA_acc_id = " . $_SESSION['ID'] . " GROUP BY GA_car_id, GA_car_city ORDER BY GA_car_id";
                                    $stmt = $pdo->query($sql);
                                    while ($row_garage = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                        $id_ =          $row_garage['GA_id'];
                                        $car_id =       $row_garage['GA_car_id'];
                                        $city_car =     $row_garage['GA_car_city'];

                                    ?>
                                        <option value="<?php echo $car_id; ?> : <?php echo $city_car; ?>">
                                            <?php echo amount_of_car($_SESSION['ID'], $city_car, $car_id, $pdo) . " stk " . car($car_id) . " i " . city_name($city_car); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <p>Antall</p>
                            <input type="text" name="amount" placeholder="Antall">
                        <?php } elseif ($id == 1) { ?>
                            <p>Velg ting</p>
                            <div class="custom-select" style="width:100%;">
                                <select style="width: 100%;" name="thing_id" required>
                                    <option value="null">Velg ting:</option>
                                    <?php

                                    $sql = "SELECT DISTINCT TH_type FROM things WHERE TH_acc_id = " . $_SESSION['ID'] . " ORDER BY TH_type";
                                    $stmt = $pdo->query($sql);
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                        $TH_type =          $row['TH_type'];

                                    ?>
                                        <option value="<?php echo $TH_type; ?>">
                                            <?php echo amount_of_things($_SESSION['ID'], $TH_type, $pdo);
                                            echo " stk ";
                                            echo thing($TH_type); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <p>Antall</p>
                            <input type="text" name="amount" placeholder="Antall">
                        <?php } elseif ($id == 2) { ?>
                            <p>Antall poeng <span class="description">Du har: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_points', $pdo)); ?> poeng</span></p>
                            <input type="text" name="points" placeholder="Antall poeng">
                        <?php } elseif ($id == 3) { ?>
                            <p>Antall kuler <span class="description">Du har: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_bullets', $pdo)); ?> kuler</span></p>
                            <input type="text" name="bullets" placeholder="Antall kuler">
                        <?php } elseif ($id == 4) { ?>
                            <p>Velg profil-ikon</p>
                            <div class="custom-select" style="width:100%;">
                                <select style="width: 100%;" name="charm" required>
                                    <option value="null">Velg profil-ikon:</option>
                                    <?php

                                    $sql = "SELECT CH_id, CH_charm FROM charm WHERE CH_acc_id = " . $_SESSION['ID'] . "";
                                    $stmt = $pdo->query($sql);
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                        $CH_id =        $row['CH_id'];
                                        $CH_charm =     $row['CH_charm'];

                                    ?>
                                        <option value="<?php echo $CH_id; ?>">
                                            <?php echo charm_desc($CH_charm); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } elseif ($id == 5) {

                            // setter alle firma til false
                            $fp_owner = false;
                            $kf_owner = false;
                            $jail_owner = false;
                            $rc_owner = false;
                            $bj_owner = false;

                            // sjekker eierskap for session ID
                            for ($i = 0; $i < 5; $i++) {
                                if (airport_owner($i, $pdo) == $_SESSION['ID']) {
                                    $fp_owner = true;
                                    $fp_city = $i;
                                }
                                if (get_kf_owner($i, $pdo) == $_SESSION['ID']) {
                                    $kf_owner = true;
                                    $kf_city = $i;
                                }
                                if (get_fengselsdirektor($i, $pdo) == $_SESSION['ID']) {
                                    $jail_owner = true;
                                    $jail_city = $i;
                                }
                                if (race_club_owner($i, $pdo) == $_SESSION['ID']) {
                                    $rc_owner = true;
                                    $rc_city = $i;
                                }
                                if (blackjack_owner_incity($i, $pdo) == $_SESSION['ID']) {
                                    $bj_owner = true;
                                    $bj_city = $i;
                                }
                                if (bunker_owner($i, $pdo) == $_SESSION['ID']) {
                                    $bunker_owner = true;
                                    $bunker_city = $i;
                                }
                            }

                        ?>
                            <?php echo feedback('Dersom du legger ut bunker vil de som har valgt deg som din bunker bli satt som staten sin', "blue"); ?>

                            <p>Velg firma</p>
                            <div class="custom-select" style="width:100%;">
                                <select style="width: 100%;" name="firma" required>
                                    <option value="null">Velg firma:</option>
                                    <?php if ($fp_owner) { ?>
                                        <option value="0:<?php echo $fp_city ?>">
                                            Flyplassen i <?php echo city_name($fp_city); ?>
                                        </option>
                                    <?php }

                                    if ($kf_owner) { ?>
                                        <option value="1:<?php echo $kf_city ?>">
                                            Kulefabrikken i <?php echo city_name($kf_city); ?>
                                        </option>
                                    <?php }

                                    if ($jail_owner) { ?>
                                        <option value="2:<?php echo $jail_city ?>">
                                            Fengsel i <?php echo city_name($jail_city); ?>
                                        </option>
                                    <?php }

                                    if ($rc_owner) { ?>
                                        <option value="3:<?php echo $rc_city ?>">
                                            Privat kjørebane i <?php echo city_name($rc_city); ?>
                                        </option>
                                    <?php } ?>

                                    <?php if ($bj_owner) { ?>
                                        <option value="4:<?php echo $bj_city ?>">
                                            Blackjack i <?php echo city_name($bj_city); ?>
                                        </option>
                                    <?php } ?>

                                    <?php if ($bunker_owner) { ?>
                                        <option value="5:<?php echo $bunker_city ?>">
                                            Bunker i <?php echo city_name($bunker_city); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                        <?php } elseif ($id == 6) {
                            echo feedback("Cannabis som ligger ute blir fjernet kl 00:00", "blue")

                        ?>
                            <p>Antall cannabis <span class="description">Du har: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_weed', $pdo)); ?> g cannabis</span></p>
                            <input type="text" name="cannabis" placeholder="Antall cannabis">

                        <?php } ?>
                        <label style="margin-top: 15px;" class="label_container">Privat salg
                            <input type="radio" id="radio_button" name="radio" value="private">
                            <span class="checkmark"></span>
                        </label>
                        <div class="username" style="display: none;">
                            <p>Hvem er tilbudet for?</p>
                            <input type="text" name="username" placeholder="Brukernavn">
                        </div>
                    </div>
                    <div class="col-6">
                        <p>Pris</p>
                        <input style="width: 100%;" type="text" name="price" id="number" placeholder="Pris..">
                        <input style="margin-top: 10px;" type="submit" name="sell" value="Legg ut for salg">
                    </div>
                </form>
                <div style="clear: both;"></div>
            </div>
        </div>
<?php } else {
        echo feedback($useLang->error->voidID, 'error');
    }
} ?>

<script>
    number_space("#number");

    $(document).ready(function() {
        $(document).find("input:checked[type='radio']").addClass('bounce');
        $("input[type='radio']").click(function() {
            $(this).prop('checked', false);
            $(this).toggleClass('bounce');
            $(".username").hide();

            if ($(this).hasClass('bounce')) {
                $(this).prop('checked', true);
                $(document).find("input:not(:checked)[type='radio']").removeClass('bounce');
                $(".username").show();
            }
        });
    });
</script>
<script src="js/select_custom.js"></script>