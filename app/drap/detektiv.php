<?php
function detektiv_dont_exist($acc_id, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM detektiv WHERE DETK_acc_id = ?");
    $stmt->execute([$acc_id]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        return true;
    } else {
        return false;
    }
}

$price_forsvar = 100000000;

function detektiv_waittime($detektiv_lvl)
{
    $detektiv_waittime[0] = 900;
    $detektiv_waittime[1] = 840;
    $detektiv_waittime[2] = 780;
    $detektiv_waittime[3] = 720;
    $detektiv_waittime[4] = 660;
    $detektiv_waittime[5] = 600;
    $detektiv_waittime[6] = 540;
    $detektiv_waittime[7] = 480;
    $detektiv_waittime[8] = 420;
    $detektiv_waittime[9] = 380;
    $detektiv_waittime[10] = 340;
    $detektiv_waittime[11] = 300;
    $detektiv_waittime[12] = 260;
    $detektiv_waittime[13] = 220;
    $detektiv_waittime[14] = 180;
    $detektiv_waittime[15] = 120;

    return $detektiv_waittime[$detektiv_lvl];
}

function detektiv_prices($detektiv_lvl)
{
    $detektiv_price[0] = 5000000;
    $detektiv_price[1] = 4500000;
    $detektiv_price[2] = 4000000;
    $detektiv_price[3] = 3500000;
    $detektiv_price[4] = 3000000;
    $detektiv_price[5] = 2500000;
    $detektiv_price[6] = 2000000;
    $detektiv_price[7] = 1500000;
    $detektiv_price[8] = 1000000;
    $detektiv_price[9] = 1000000;
    $detektiv_price[10] = 1000000;
    $detektiv_price[11] = 1000000;
    $detektiv_price[12] = 1000000;
    $detektiv_price[13] = 1000000;
    $detektiv_price[14] = 1000000;
    $detektiv_price[15] = 1000000;

    return $detektiv_price[$detektiv_lvl];
}

function detektiv_forsvar_prices($detektiv_lvl)
{
    $detektiv_price[0] = 100000000;
    $detektiv_price[1] = 100000000;
    $detektiv_price[2] = 100000000;
    $detektiv_price[3] = 95000000;
    $detektiv_price[4] = 95000000;
    $detektiv_price[5] = 90000000;
    $detektiv_price[6] = 90000000;
    $detektiv_price[7] = 85000000;
    $detektiv_price[8] = 85000000;
    $detektiv_price[9] = 80000000;
    $detektiv_price[10] = 80000000;
    $detektiv_price[11] = 75000000;
    $detektiv_price[12] = 75000000;
    $detektiv_price[13] = 70000000;
    $detektiv_price[14] = 70000000;
    $detektiv_price[15] = 65000000;

    return $detektiv_price[$detektiv_lvl];
}

function detektiv_bunker_prices($detektiv_lvl)
{
    $detektiv_price[0] = 50000000;
    $detektiv_price[1] = 50000000;
    $detektiv_price[2] = 50000000;
    $detektiv_price[3] = 45000000;
    $detektiv_price[4] = 45000000;
    $detektiv_price[5] = 40000000;
    $detektiv_price[6] = 40000000;
    $detektiv_price[7] = 35000000;
    $detektiv_price[8] = 35000000;
    $detektiv_price[9] = 30000000;
    $detektiv_price[10] = 30000000;
    $detektiv_price[11] = 25000000;
    $detektiv_price[12] = 25000000;
    $detektiv_price[13] = 20000000;
    $detektiv_price[14] = 20000000;
    $detektiv_price[15] = 15000000;

    return $detektiv_price[$detektiv_lvl];
}

function detektiv_upgrade_prices($detektiv_lvl)
{
    $detektiv_upgrade_prices[0] = 250000;
    $detektiv_upgrade_prices[1] = 300000;
    $detektiv_upgrade_prices[2] = 350000;
    $detektiv_upgrade_prices[3] = 400000;
    $detektiv_upgrade_prices[4] = 450000;
    $detektiv_upgrade_prices[5] = 500000;
    $detektiv_upgrade_prices[6] = 550000;
    $detektiv_upgrade_prices[7] = 650000;
    $detektiv_upgrade_prices[8] = 800000;
    $detektiv_upgrade_prices[9] = 1000000;
    $detektiv_upgrade_prices[10] = 10000000;
    $detektiv_upgrade_prices[11] = 100000000;
    $detektiv_upgrade_prices[12] = 250000000;
    $detektiv_upgrade_prices[13] = 500000000;
    $detektiv_upgrade_prices[14] = 750000000;
    $detektiv_upgrade_prices[15] = 1000000000;

    return $detektiv_upgrade_prices[$detektiv_lvl];
}

function active_detektiv($id, $pdo)
{
    $query = $pdo->prepare("SELECT DETSOK_acc_id FROM detektiv_sok WHERE DETSOK_acc_id = ? AND DETSOK_active = ?");
    $query->execute(array($id, 0));
    $DETK_row = $query->fetch(PDO::FETCH_ASSOC);

    if (!$DETK_row || $DETK_row['DETSOK_acc_id'] == 0) {
        return false;
    } else {
        return true;
    }
}

if (detektiv_dont_exist($_SESSION['ID'], $pdo)) {
    $detektiv_lvl = 0;
} else {
    $detektiv_lvl = get_detektiv_lvl($_SESSION['ID'], $pdo);
}

if (isset($_POST['oppgrader'])) {
    if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) < detektiv_upgrade_prices($detektiv_lvl)) {
        echo feedback("Du har ikke nok penger til å oppgradere din detektiv", "fail");
    } elseif ($detektiv_lvl >= 15) {
        echo feedback("Du har den beste detektiven", "fail");
    } else {

        if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 5) {
            mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
        }

        if (detektiv_dont_exist($_SESSION['ID'], $pdo)) {
            $sql = "INSERT INTO detektiv (DETK_acc_id, DETK_lvl) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$_SESSION['ID'], 1]);
            take_money($_SESSION['ID'], detektiv_upgrade_prices($detektiv_lvl), $pdo);

            header("Location: ?side=drap&p=detektiv&upgrade");
        } else {
            take_money($_SESSION['ID'], detektiv_upgrade_prices($detektiv_lvl), $pdo);

            $sql = "UPDATE detektiv SET DETK_lvl=? WHERE DETK_acc_id=?";
            $pdo->prepare($sql)->execute([($detektiv_lvl + 1), $_SESSION['ID']]);

            header("Location: ?side=drap&p=detektiv&upgrade");
        }
    }
}

if (isset($_GET['upgrade'])) {
    echo feedback("Du har oppgradert din detektiv", "success");
}

$vito = "Vito";

if (isset($_POST['search'])) {
    if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= detektiv_prices($detektiv_lvl)) {
        if (isset($_POST['username'])) {
            if (strcasecmp($vito, $_POST['username']) == 0) {
                echo feedback("Hei! Vito skal være i Las Vegas etter hva jeg har hørt fra mine kontakter. Du skal få dette søket gratis, men husk at Vito er berryktet og trenger 50 kuler. Lykke til.", "success");
            } else {
                if (user_exist($_POST['username'], $pdo)) {
                    $acc_id = get_acc_id($_POST['username'], $pdo);
                    $city = get_city($acc_id, $pdo);
                    $date = time() + detektiv_waittime($detektiv_lvl);

                    take_money($_SESSION['ID'], detektiv_prices($detektiv_lvl), $pdo);

                    $sql = "INSERT INTO detektiv_sok (DETSOK_acc_id, DETSOK_user, DETSOK_city, DETSOK_date) VALUES (?,?,?,?)";
                    $pdo->prepare($sql)->execute([$_SESSION['ID'], $acc_id, $city, $date]);

                    header("Location: ?side=drap&p=detektiv&sokaktiv");
                } else {
                    echo feedback("Brukeren finnes ikke", "fail");
                }
            }
        } else {
            echo feedback("Skriv inn ønsket brukernavn", "fail");
        }
    } else {
        echo feedback("Ikke nok penger", "fail");
    }
}

if (isset($_POST['search_forsvar'])) {
    if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= detektiv_forsvar_prices($detektiv_lvl)) {
        if (isset($_POST['username_forsvar'])) {
            if (user_exist($_POST['username_forsvar'], $pdo)) {
                $acc_id = get_acc_id($_POST['username_forsvar'], $pdo);
                $forsvar = AS_session_row($acc_id, 'AS_def', $pdo);
                $date = time() + detektiv_waittime($detektiv_lvl);
                
if (family_member_exist($acc_id, $pdo)) {
    $my_family_id = get_my_familyID($acc_id, $pdo);

    $addon = get_forsvar_from_family(get_family_defence($my_family_id, $pdo), get_my_familyrole($acc_id, $pdo), total_family_members($my_family_id, $pdo));
} else {
    $addon = 0;
}

                take_money($_SESSION['ID'], detektiv_forsvar_prices($detektiv_lvl), $pdo);

                $city = 'def:' . ($forsvar + $addon);

                $sql = "INSERT INTO detektiv_sok (DETSOK_acc_id, DETSOK_user, DETSOK_city, DETSOK_date) VALUES (?,?,?,?)";
                $pdo->prepare($sql)->execute([$_SESSION['ID'], $acc_id, $city, $date]);

                header("Location: ?side=drap&p=detektiv&sokaktiv");
            } else {
                echo feedback("Brukeren finnes ikke", "fail");
            }
        } else {
            echo feedback("Skriv inn ønsket brukernavn", "fail");
        }
    } else {
        echo feedback("Ikke nok penger", "fail");
    }
}

if (isset($_POST['search_bunker'])) {
    if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= detektiv_bunker_prices($detektiv_lvl)) {
        if (isset($_POST['username_bunker'])) {
            if (user_exist($_POST['username_bunker'], $pdo)) {
                $acc_id = get_acc_id($_POST['username_bunker'], $pdo);

                $bunker = 'false';
                if (player_in_bunker($acc_id, $pdo)) {
                    $bunker = 'true';
                }

                $date = time() + detektiv_waittime($detektiv_lvl);

                take_money($_SESSION['ID'], detektiv_bunker_prices($detektiv_lvl), $pdo);

                $city = 'bunker:' . $bunker;

                $sql = "INSERT INTO detektiv_sok (DETSOK_acc_id, DETSOK_user, DETSOK_city, DETSOK_date) VALUES (?,?,?,?)";
                $pdo->prepare($sql)->execute([$_SESSION['ID'], $acc_id, $city, $date]);

                header("Location: ?side=drap&p=detektiv&sokaktiv");
            } else {
                echo feedback("Brukeren finnes ikke", "fail");
            }
        } else {
            echo feedback("Skriv inn ønsket brukernavn", "fail");
        }
    } else {
        echo feedback("Ikke nok penger", "fail");
    }
}

if (isset($_GET['sokaktiv'])) {
    echo feedback("Søket er i gang.", "success");
}

?>

<div class="col-7 single">
    <div class="content">
        <img class="action_image" src="img/action/actions/detektiv.png">
        <p class="description">
            Detektiv brukes for å finne informasjon om en annen spiller. Desto høyere lvl din detektiv har, desto mindre ventetid og billigere er søkene.
        </p>
        <center>
            <p>
                Din detektiv er i level <?php echo $detektiv_lvl; ?><br>
            </p>
        </center>

        <?php if ($detektiv_lvl < 15) { ?>
            <div class="col-12">
                <center>
                    <h4>Oppgrader detektiv level</h4>
                    <form method="post">
                        <p class="description">Pris: <?php echo number(detektiv_upgrade_prices($detektiv_lvl)); ?> kr<br></p>
                        <input type="submit" name="oppgrader" value="Oppgrader detektiv">
                    </form>
                </center>
            </div>
        <?php } ?>

        <div class="col-12">
            <h4>Utfør søk</h4>
            <?php if (!active_detektiv($_SESSION['ID'], $pdo)) { ?>
                <div class="col-4" style="padding: 10px 0;">
                    <h4>Finn by</h4>
                    <span class="description">
                        Søk etter bruker og få vite hvor brukeren er etter
                        <br><br>
                        <b>ventetid:</b> <?php echo number(detektiv_waittime($detektiv_lvl)); ?> sekunder <br>
                        <b>Pris:</b> <?php echo number(detektiv_prices($detektiv_lvl)); ?> kr
                    </span>
                    <form method="post" style="margin-top: 10px;">
                        <input type="text" name="username" style="width: 60%; float: left;" placeholder="Brukernavn...">
                        <input type="submit" name="search" style="width: 30%; float: left; margin-top: 3px; margin-left: 10px;" value="Søk">
                    </form>
                </div>
                <div class="col-4">
                    <h4>Forsvar</h4>
                    <span class="description">
                        Søk etter bruker og få vite hvor mye forsvar brukeren har.
                        <br><br>
                        <b>ventetid:</b> <?php echo number(detektiv_waittime($detektiv_lvl)); ?> sekunder <br>
                        <b>Pris:</b> <?php echo number(detektiv_forsvar_prices($detektiv_lvl)); ?> kr
                    </span>
                    <form method="post" style="margin-top: 10px;">
                        <input type="text" name="username_forsvar" style="width: 60%; float: left;" placeholder="Brukernavn...">
                        <input type="submit" name="search_forsvar" style="width: 30%; float: left; margin-top: 3px; margin-left: 10px;" value="Søk">
                    </form>
                </div>
                <div class="col-4">
                    <h4>Bunkerstatus</h4>
                    <span class="description">
                        Søk etter bruker og få vite om spilleren er i bunker.
                        <br><br>
                        <b>ventetid:</b> <?php echo number(detektiv_waittime($detektiv_lvl)); ?> sekunder <br>
                        <b>Pris:</b> <?php echo number(detektiv_bunker_prices($detektiv_lvl)); ?> kr
                    </span>
                    <form method="post" style="margin-top: 10px;">
                        <input type="text" name="username_bunker" style="width: 60%; float: left;" placeholder="Brukernavn...">
                        <input type="submit" name="search_bunker" style="width: 30%; float: left; margin-top: 3px; margin-left: 10px;" value="Søk">
                    </form>
                </div>
                <div style="clear:both;"></div>
            <?php } else {
                echo feedback("Du har et aktivt søk.", "blue");
            } ?>
        </div>
        <div style="clear:both;"></div>

    </div>
</div>