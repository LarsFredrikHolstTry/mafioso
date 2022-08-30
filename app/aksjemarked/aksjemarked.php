<?php

if (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    $stock_name[0] = "Mafioso Eiendom C";
    $stock_name[1] = "Den Sveitsiske bank";
    $stock_name[2] = "Det Danske Dampskibselskap";
    $stock_name[3] = "Potet Solutions Inc.";
    $stock_name[4] = "Fart & Bart AS";

    $stmt = $pdo->prepare("SELECT count(*) FROM stock_behold WHERE STB_acc_id = ?");
    $stmt->execute([$_SESSION['ID']]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $stb[0] = 0;
        $stb[1] = 0;
        $stb[2] = 0;
        $stb[3] = 0;
        $stb[4] = 0;
    } else {
        $query = $pdo->prepare('SELECT * FROM stock_behold WHERE STB_acc_id = :acc_id');
        $query->execute(array(':acc_id' => $_SESSION['ID']));
        foreach ($query as $row) {
            $stb[0] = $row['STB_0'];
            $stb[1] = $row['STB_1'];
            $stb[2] = $row['STB_2'];
            $stb[3] = $row['STB_3'];
            $stb[4] = $row['STB_4'];
        }
    }

    // finne prisdifferansen
    for ($k = 0; $k < count($stock_name); $k++) {
        $query = $pdo->prepare("SELECT SLI_price FROM stock_list WHERE SLI_type = ? AND SLI_date = ( SELECT MAX(SLI_date) FROM stock_list WHERE SLI_date < ( SELECT MAX(SLI_date) FROM stock_list ))");
        $query->execute(array($k));
        $row_old = $query->fetch(PDO::FETCH_ASSOC);

        $old_price[$k] = $row_old['SLI_price'];

        $query = $pdo->prepare("SELECT SLI_price FROM stock_list WHERE SLI_type = ? ORDER BY SLI_date DESC");
        $query->execute(array($k));
        $row_new = $query->fetch(PDO::FETCH_ASSOC);

        $new_price[$k] = $row_new['SLI_price'];

        if ($old_price[$k] > $new_price[$k]) {
            $price_differance[$k] = "lower";
        } else {
            $price_differance[$k] = "higher";
        }
    }

    // finne prisen for de forskjellige aksjene
    for ($i = 0; $i < count($stock_name); $i++) {
        $query = $pdo->prepare("SELECT * FROM stock_list WHERE SLI_type = ? ORDER BY SLI_date DESC");
        $query->execute(array($i));
        $row_stock = $query->fetch(PDO::FETCH_ASSOC);

        $stock_price[$i] = $row_stock['SLI_price'];
    }


    if (isset($_POST['sell'])) {
        $total_price = 0;
        $stocks = [];

        for ($b = 0; $b < count($stock_name); $b++) {
            if (isset($_POST[$b])) {
                $_POST[$b] = remove_space($_POST[$b]);
                if ($_POST[$b] > 0) {
                    $amount = $_POST[$b];
                    $total_price = $total_price + ($amount * $stock_price[$b]);
                    array_push($stocks, $amount);
                } else {
                    $amount = 0;
                    $total_price = $total_price + ($amount * $stock_price[$b]);
                    array_push($stocks, $amount);
                }
            }
        }

        $stmt = $pdo->prepare("SELECT count(*) FROM stock_behold WHERE STB_acc_id = ?");
        $stmt->execute([$_SESSION['ID']]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $sql = "INSERT INTO stock_behold (STB_acc_id) VALUES (?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_SESSION['ID']]);
        }

        for ($t = 0; $t < count($stock_name); $t++) {
            if ($_POST[$t] > $stb[$t]) {
                echo feedback("Du har ikke nok aksjer for å utføre dette salget", "error");
            } elseif ($stocks[$t] != 0) {
                echo feedback("Du solgte " . number($stocks[$t]) . " aksjer " . $stock_name[$t] . " for " . number($stocks[$t] * $stock_price[$t]) . " kr", "success");

                $sql = "UPDATE stock_behold SET STB_$t = STB_$t - ? WHERE STB_acc_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$stocks[$t], $_SESSION['ID']]);

                $sql = "INSERT INTO stocks_logg (STLG_acc_id, STLG_type, STLG_date, STLG_action, STLG_amount, STLG_price) VALUES (?,?,?,?,?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['ID'], $t, time(), 1, $stocks[$t], ($_POST[$t] * $stock_price[$t])]);

                give_money($_SESSION['ID'], ($_POST[$t] * $stock_price[$t]), $pdo);
            }
        }
    }

    if (isset($_POST['buy'])) {
        $total_price = 0;
        $stocks = [];

        for ($b = 0; $b < count($stock_name); $b++) {
            if (isset($_POST[$b])) {
                $_POST[$b] = remove_space($_POST[$b]);
                if ($_POST[$b] > 0) {
                    $amount = $_POST[$b];
                    $total_price = $total_price + ($amount * $stock_price[$b]);
                    array_push($stocks, $amount);
                } else {
                    $amount = 0;
                    $total_price = $total_price + ($amount * $stock_price[$b]);
                    array_push($stocks, $amount);
                }
            }
        }

        if ($total_price > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
            echo feedback("Du har ikke nok penger for å utføre dette kjøpet", "error");
        } else {
            $stmt = $pdo->prepare("SELECT count(*) FROM stock_behold WHERE STB_acc_id = ?");
            $stmt->execute([$_SESSION['ID']]);
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                $sql = "INSERT INTO stock_behold (STB_acc_id) VALUES (?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['ID']]);
            }

            for ($t = 0; $t < count($stock_name); $t++) {
                if ($stocks[$t] != 0) {
                    // echo feedback("Du kjøpte ". number($stocks[$t]) ." aksjer " . $stock_name[$t]." for ". number($stocks[$t] * $stock_price[$t]). " kr", "success");

                    $sql = "UPDATE stock_behold SET STB_$t = STB_$t + ? WHERE STB_acc_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$stocks[$t], $_SESSION['ID']]);

                    $sql = "INSERT INTO stocks_logg (STLG_acc_id, STLG_type, STLG_date, STLG_action, STLG_amount, STLG_price) VALUES (?,?,?,?,?,?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_SESSION['ID'], $t, time(), 0, $stocks[$t], ($_POST[$t] * $stock_price[$t])]);

                    take_money($_SESSION['ID'], ($_POST[$t] * $stock_price[$t]), $pdo);
                }
            }

            header('Location: ?side=aksjemarked&kjop');
        }
    }

    if (isset($_GET['kjop'])) {
        echo feedback("Du kjøpte valgte aksjer", "success");
    }

    if (isset($_GET['aksjegraf'])) {
?>
        <a href="?side=aksjemarked">Lukk graf</a>
        <?php


        $query = $pdo->prepare('SELECT SLI_price as y, SLI_date as label FROM stock_list WHERE SLI_type = :type ORDER BY SLI_date ASC');
        $query->execute(array(':type' => $_GET['aksjegraf']));
        foreach ($query as $row) {
            $dataPoints[] = ["y" => $row['y'], "label" => date('H:i', $row['label'])];
        }

        ?>
        <script>
            window.onload = function() {

                var chart = new CanvasJS.Chart("chartContainer", {
                    title: {
                        text: "<?php echo $stock_name[$_GET['aksjegraf']] ?>"
                    },
                    axisY: {
                        title: "Pris"
                    },
                    data: [{
                        indexLabelLineColor: "red",
                        color: "#009fe3",
                        type: "line",
                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart.render();

            }
        </script>
        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <?php } ?>
    <div class="col-9 single" style="margin-top: -10px;">

        <div class="col-6">
            <div class="content">
                <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            </div>
        </div>
        <div class="col-6">
            <div class="content">
                <h4>Statistikk</h4>
                <ul>
                    <li>Totalt kjøpt for: <span style="float: right;"><?php echo number(total_bought_stocks($_SESSION['ID'], $pdo)); ?> kr</span></li>
                    <li>Totalt solgt for: <span style="float: right;"><?php echo number(total_sold_stocks($_SESSION['ID'], $pdo)); ?> kr</span></li>
                    <li>Profitt: <span style="float: right; "><?php

                                                                $profit = total_sold_stocks($_SESSION['ID'], $pdo) - total_bought_stocks($_SESSION['ID'], $pdo);

                                                                if ($profit == null) {
                                                                    echo '0 kr';
                                                                } elseif ($profit < 0) {
                                                                    echo '<span style="color: red;">';
                                                                    echo number($profit);
                                                                    echo ' kr </span>';
                                                                } elseif ($profit > 0) {
                                                                    echo '<span style="color: green;">';
                                                                    echo number($profit);
                                                                    echo ' kr </span>';
                                                                }

                                                                ?> </span></li>
                </ul>
            </div>
        </div>
        <div class="col-12">
            <div class="content">
                <h3>Aksjemarked</h3>
                <form method="post">
                    <p class="description">På aksjemarkedet kan du spille med penger om aksjen vil øke eller synke. Prisene oppdateres hvert 10. minutt. Dersom du eier en aksje i ett firma som går i 0 vil du miste alle dine aksjer i dette firmaet.</p>
                    <table>
                        <tr>
                            <th>Aksje</th>
                            <th>Pris</th>
                            <th style="width: 30%;">Beholdning</th>
                        </tr>
                        <?php for ($i = 0; $i < count($stock_name); $i++) { ?>
                            <tr style="height: 56px;">
                                <td>
                                    <a href="?side=aksjemarked&aksjegraf=<?php echo $i ?>"><?php echo $stock_name[$i]; ?></a>
                                </td>
                                <td>
                                    <?php

                                    if ($stock_price[$i] <= 0) {
                                        echo '<span style="color: red;">KONKURS</span>';
                                    } else {
                                        echo '<h4 style="font-weight: normal">' . number($stock_price[$i]) . ' kr </h4>';

                                        if ($price_differance[$i] == "higher") {
                                            echo '<span style="padding-right: 20px; color: green"><i class="fi fi-rr-caret-up"></i> ' . number($old_price[$i]) . ' kr</span>';
                                        } else {
                                            echo '<span style="padding-right: 20px; color: red"><i class="fi fi-rr-caret-down"></i> ' . number($old_price[$i]) . ' kr</span>';
                                        }
                                    }

                                    ?>
                                </td>
                                <td>
                                    <?php if ($stock_price[$i] > 0) { ?>

                                        <input type="text" id="number_<?php echo $i; ?>" style="width: 69%; margin: 0;" name="<?php echo $i; ?>" placeholder="<?php echo number($stb[$i]); ?>">
                                        <button style="width: 20%;" type="button" onclick="addText(<?php echo $i; ?>, <?php echo floor(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) / $stock_price[$i]); ?>)">Maks</button>

                                        <script>
                                            function numberWithSpaces(x) {
                                                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                                            }

                                            function addText(parameter, price) {
                                                var input = document.getElementById('number_' + parameter);
                                                input.value = numberWithSpaces(price);
                                            }
                                        </script>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>

                    </table>
                    <input type="submit" style="width: 49%;" name="sell" value="Selg">
                    <input type="submit" style="width: 49%;" name="buy" value="Kjøp">
                </form>
            </div>
        </div>

        <div class="col-12">
            <div class="content">
                <h4>Siste 20 handel</h4>
                <p class="description">Under vil du se en liste over 20 siste kjøpt og solge aksjer.</p>
                <table>
                    <tr>
                        <th>Aksje</th>
                        <th>Handling</th>
                        <th>Antall</th>
                        <th>Pris</th>
                        <th>Kurs</th>
                        <th>Dato / tid</th>
                    </tr>
                    <?php

                    $query = $pdo->prepare('SELECT * FROM stocks_logg WHERE STLG_acc_id=:id ORDER BY STLG_date DESC LIMIT 20');
                    $query->execute(array(':id' => $_SESSION['ID']));
                    foreach ($query as $row) {

                        $status[0] = "Kjøp";
                        $status[1] = "Salg";

                    ?>
                        <tr title="<?php echo $status[$row['STLG_action']]; ?>" <?php if ($row['STLG_action'] == 0) {
                                                                                    echo 'id="sell"';
                                                                                } else {
                                                                                    echo 'id="buy"';
                                                                                } ?>>
                            <td><?php echo $stock_name[$row['STLG_type']]; ?></td>
                            <td><?php echo $status[$row['STLG_action']]; ?></td>
                            <td><?php echo number($row['STLG_amount']); ?></td>
                            <td><?php echo number($row['STLG_price']); ?> kr</td>
                            <td><?php echo number($row['STLG_price'] / $row['STLG_amount']); ?> kr</td>
                            <td><?php echo date_to_text($row['STLG_date']); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <script>
        number_space("#number_0");
        number_space("#number_1");
        number_space("#number_2");
        number_space("#number_3");
        number_space("#number_4");
    </script>

    <style>
        #buy {
            background-color: rgba(0, 255, 0, .1);
        }

        #sell {
            background-color: rgba(255, 0, 0, .1);
        }
    </style>
<?php } ?>