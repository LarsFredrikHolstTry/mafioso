<?php

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    $price_pr_poeng = 3000000;

    if (isset($_POST['sell_poeng'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'Selger poeng til banken. Antall: ' . $poeng_amount . '', $pdo);
        $poeng_amount = $_POST['poeng_amount'];

        if ($poeng_amount > AS_session_row($_SESSION['ID'], 'AS_points', $pdo) || !is_numeric($poeng_amount) || $poeng_amount < 0) {
            user_log($_SESSION['ID'], $_GET['side'], 'Ugyldig input: ' . $poeng_amount . '', $pdo);
            echo feedback("Ugyldig input", "error");
        } else {
            take_poeng($_SESSION['ID'], $poeng_amount, $pdo);
            give_bank_money($_SESSION['ID'], ($poeng_amount * $price_pr_poeng), $pdo);

            user_log($_SESSION['ID'], $_GET['side'], 'Selger ' . $poeng_amount . ' poeng for ' . number($poeng_amount * $price_pr_poeng) . '', $pdo);
            header("Location:?side=banken&selg_poeng&sold=" . $poeng_amount . "");
            echo feedback("Du solgte " . $poeng_amount . " for " . number($poeng_amount * $price_pr_poeng) . " kr", "success");
        }
    }

    if (isset($_GET['sold'])) {
        echo feedback("Du solgte " . $_GET['sold'] . " for " . number($_GET['sold'] * $price_pr_poeng) . " kr", "success");
    }

?>

    <?php if (isset($_GET['selg_poeng'])) { ?>
        <div class="col-9 single" style="margin-bottom: 10px;">
            <div class="content">
                <a style="float: right; color: grey;" href="?side=banken">Lukk</a>
                <h4>Selg poeng til banken</h4>
                <p class="description">Som kunde i banken har du mulighet for å selge poeng til banken for <?php echo number($price_pr_poeng); ?> kr pr poeng</p>
                <form method="post">
                    <b>Antall poeng</b>
                    <input type="text" name="poeng_amount" placeholder="Antall poeng">
                    <input type="submit" name="sell_poeng" value="Selg poeng">


                </form>
            </div>
        </div>
    <?php } ?>

    <div id="error" class="feedback error" style="display: none; padding-left: 15px;">
    </div>

    <div id="success" class="feedback success" style="display: none; padding-left: 15px;">
    </div>

    <div class="col-10 single" style="margin-top: -10 !important;">
        <div class="col-5">
            <div class="content">
                <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
                <ul>
                    <li>Saldo: <span id="saldo" style="float: right;"><?php echo number(AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo)); ?> kr</span></li>
                    <li>Rente-beløp ved midnatt: <span style="float: right;"><?php echo number(money_by_midnight(AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo), $pdo)); ?> kr</span></li>
                    <li>Overførings-gebyr: <span style="float: right;">10%</span></li>
                    <li>Totalt mottatt: <span style="float: right;">
                            <?php

                            $query = $pdo->prepare("SELECT SUM(BT_money) as sum FROM bank_transfer WHERE BT_to=?");
                            $query->execute(array($_SESSION['ID']));
                            $row = $query->fetch(PDO::FETCH_ASSOC);

                            echo number($row['sum']);

                            ?>
                            kr</span></li>
                    <li>Totalt Overført: <span style="float: right;">
                            <?php

                            $query = $pdo->prepare("SELECT SUM(BT_money) as sum FROM bank_transfer WHERE BT_from=?");
                            $query->execute(array($_SESSION['ID']));
                            $row = $query->fetch(PDO::FETCH_ASSOC);

                            echo number($row['sum']);

                            ?>
                            kr</span></li>
                </ul>
            </div>
            <div class="col-12" style="margin: 0; padding: 0;">
                <div class="col-5" style="margin: 0; padding: 0 5px 0 0;">
                    <div class="content" style="margin-top: 20px;">
                        <a href="?side=swissbank">Swissbank</a>
                    </div>
                </div>
                <div class="col-7" style="margin: 0; padding: 0;">
                    <div class="content" style="margin-top: 20px;">
                        <a href="?side=banken&selg_poeng">Selg poeng til banken</a>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-7">
            <!-- <form method="post"> -->
            <div class="content">
                <h4>Sett inn / ta ut</h4>
                <div class="col-12 single">
                    <input style="margin: 10px 0px; width: 100%;" placeholder="Antall.." type="text" id="number_bank" name="number">
                </div>
                <div class="col-3 ">
                    <input style="width: 100%;" id="money_out" type="submit" name="money_out" value="Ta ut">
                </div>
                <div class="col-3 ">
                    <input style="width: 100%;" id="all_out" type="submit" name="money_out_all" value="Ta ut alt">
                </div>
                <div class="col-3 ">
                    <input style="width: 100%;" id="money_in" type="submit" name="money_in" value="Sett inn">
                </div>
                <div class="col-3 ">
                    <input style="width: 100%;" id="all_in" type="submit" name="money_in_all" value="Sett inn alt">
                </div>
                <div style="clear: both;"></div>
            </div>
            <!-- </form> -->
        </div>
        <div class="col-7">
            <form method="post">
                <div class="content">
                    <?php

                    if (isset($_GET['send']) && $_GET['receiver']) {
                        if (is_numeric($_GET['send'])) {
                            echo feedback("Du sendte " . number($_GET['send']) . " kr til " . $_GET['receiver'], "success");
                        } else {
                            echo feedback("Ugyldig beløp", "fail");
                        }
                    }

                    if (isset($_POST['send_money'])) {
                        user_log($_SESSION['ID'], $_GET['side'], 'Sender penger', $pdo);
                        if (isset($_POST['text']) && strlen($_POST['text']) <= 100) {
                            if (isset($_POST['number_send']) && isset($_POST['receiver'])) {
                                $number_send = remove_space($_POST['number_send']);
                                $receiver = $_POST['receiver'];
                                $user_exist = user_exist($receiver, $pdo);
                                $user_id_receiver = get_acc_id($receiver, $pdo);

                                if (strtolower($receiver) == strtolower(ACC_username($_SESSION['ID'], $pdo))) {
                                    user_log($_SESSION['ID'], $_GET['side'], 'Prøver å sende penger til seg selv', $pdo);
                                    echo feedback("Du kan ikke sende penger til deg selv", "error");
                                } elseif (!$user_exist) {
                                    user_log($_SESSION['ID'], $_GET['side'], 'Mottaker finnes ikke', $pdo);
                                    echo feedback($receiver . " finnes ikke", "error");
                                } elseif (!is_numeric($number_send) || $number_send < 0) {
                                    user_log($_SESSION['ID'], $_GET['side'], 'Ugyldig beløp: ' . $number_send, $pdo);
                                    echo feedback("Ugyldig beløp", "fail");
                                } elseif ($number_send > AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo)) {
                                    user_log($_SESSION['ID'], $_GET['side'], 'Ikke nok penger i banken: ' . $number_send, $pdo);
                                    echo feedback("Du har ikke så mye penger i banken", "error");
                                } else {
                                    $sql = "INSERT INTO bank_transfer (BT_from, BT_to, BT_money, BT_date, BT_text) VALUES (?,?,?,?,?)";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([$_SESSION['ID'], $user_id_receiver, ($number_send * 0.9), time(), $_POST['text']]);

                                    take_bank_money($_SESSION['ID'], $number_send, $pdo);
                                    give_bank_money($user_id_receiver, $number_send * 0.9, $pdo);

                                    if (notificationSettings('moneyReceived', $user_id_receiver, $pdo)) {
                                        $text = "Du har mottatt " . number($number_send * 0.9) . " kr av " . username_plain($_SESSION['ID'], $pdo) . "";
                                        send_notification($user_id_receiver, $text, $pdo);
                                    }

                                    user_log($_SESSION['ID'], $_GET['side'], 'Sender ' . ($number_send * 0.9) . ' til ' . $receiver . '', $pdo);
                                    header("Location: ?side=banken&send=" . ($number_send * 0.9) . "&receiver=$receiver");
                                }
                            } else {
                                echo feedback("Ugyldig beløp: null", "fail");
                            }
                        } else {
                            echo feedback('For mange tegn i teksten', 'error');
                        }
                    }


                    ?>
                    <h4>Send penger</h4>
                    <br>
                    <div class="col-6" style="padding: 0 15px 0px 0px;">
                        Mottaker
                        <input style="margin: 10px 0px; width: 100%;" placeholder="Mottaker.." type="text" id="receiver" name="receiver">
                    </div>
                    <div class="col-6" style="padding: 0;">
                        Sum
                        <input style="margin: 10px 0px; width: 100%;" placeholder="Sum.." type="text" id="number2" name="number_send">
                    </div>
                    <div class="col-12" style="padding: 0;">
                        Tekst (Maks 100 tegn)
                        <textarea style="margin: 10px 0px; width: 100%;" name="text" placeholder="Fritekst.."></textarea>
                    </div>
                    <div class="col-12 single">
                        <input style="width: 100%;" type="submit" name="send_money" value="Send penger">
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-10 single">

        <div class="col-12">
            <div class="content">
                <h4>20 siste overføringer</h4>
                <br>
                <table>
                    <tr>
                        <th style="width: 20%">Bruker</th>
                        <th style="width: 20%">Penger</th>
                        <th style="width: 40%">Tekst</th>
                        <th style="width: 20%">Dato</th>
                    </tr>
                    <?php

                    $statement = $pdo->prepare("SELECT * FROM bank_transfer WHERE BT_to = :id OR BT_from = :id ORDER BY BT_date DESC LIMIT 10");
                    $statement->execute(array(':id' => $_SESSION['ID']));
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

                        if ($row && $row['BT_from'] == $_SESSION['ID']) {
                            $status = 'from';
                        } elseif ($row && $row['BT_to'] == $_SESSION['ID']) {
                            $status = 'to';
                        }

                    ?>
                        <tr>
                            <td><?php

                                if ($status == 'to') {
                                    echo ACC_username($row['BT_from'], $pdo);
                                } else {
                                    echo ACC_username($row['BT_to'], $pdo);
                                }

                                ?></td>
                            <td><?php
                                if ($status == 'to') {
                                    echo '<span style="color:var(--ready-color)">+ ' . number($row['BT_money']);
                                } else {
                                    echo '<span style="color:var(--clr-rank-mafioso)">- ' . number($row['BT_money']);
                                }
                                ?> kr</span></td>
                            <td><?php echo $row['BT_text'] ? $row['BT_text'] : '<span style="color:var(--table-th-cl);">ingen melding</span>'; ?></td>
                            <td><?php echo date_to_text($row['BT_date']); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <script>
        number_space("#number_bank");
        number_space("#number2");

        function numberWithSpaces(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }

        function removeSpace(e) {
            let val = (e.target.value).trim();
            console.log(val);
        }

        $(document).ready(function() {
            $("#all_in").click(function() {
                $("#error").empty();
                $("#success").empty();

                $.post("app/banken/banken_sett_inn_alt.php", {

                    },
                    function(output, res, status) {
                        if (~output.indexOf("ingen")) {
                            $('<span>' + output + '</span>').appendTo('#error');
                            $("#error").show();
                            $("#success").hide(); // show error feedback
                        } else {
                            $('<span>' + output + '</span>').appendTo('#success');
                            $("#error").hide();
                            $("#success").show();
                            $('#saldo').text(numberWithSpaces(<?php echo AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo) + AS_session_row($_SESSION['ID'], 'AS_money', $pdo); ?>) + ' kr');
                            $('#saldo_top').text(numberWithSpaces(<?php echo AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo) + AS_session_row($_SESSION['ID'], 'AS_money', $pdo); ?>) + ' kr');
                            $('#saldo_left').text('0 kr');

                        }
                    }
                );
            });
        });

        $(document).ready(function() {
            $("#all_out").click(function() {
                $("#error").empty();
                $("#success").empty();

                $.post("app/banken/banken_ta_ut_alt.php", {

                    },
                    function(output, res, status) {
                        if (~output.indexOf("ingen")) {
                            $('<span>' + output + '</span>').appendTo('#error');
                            $("#error").show();
                            $("#success").hide(); // show error feedback
                        } else {
                            $('<span>' + output + '</span>').appendTo('#success');
                            $("#error").hide();
                            $("#success").show();
                            $('#saldo_left').text(numberWithSpaces(<?php echo AS_session_row($_SESSION['ID'], 'AS_money', $pdo) + AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo); ?>) + ' kr');
                            $('#saldo').text('0 kr');
                            $('#saldo_top').text('0 kr');

                        }
                    }
                );
            });
        });

        $(document).ready(function() {
            $("#money_in").click(function() {
                $("#error").empty(); // empty error div
                $("#success").empty(); // empty success div

                var money = $('#number_bank').val().replace(/\s/g, ''); // get value from input field

                $.post("app/banken/banken_sett_inn.php", {
                        money: money
                    },
                    function(output, res, status) {
                        if (~output.indexOf("Ugyldig")) { // if output contains 'ikke'
                            $('<span>' + output + '</span>').appendTo('#error'); // add text to error feedback
                            $("#error").show(); // show error feedback
                            $("#success").hide(); // show error feedback
                        } else {
                            var saldo = $('#saldo').text().replace(/[^0-9]/gi, '');
                            var penger = $('#saldo_left').text().replace(/[^0-9]/gi, '');

                            var money_in_bank = parseInt(saldo) + parseInt(money);
                            var money_on_hand = parseInt(penger) - parseInt(money);

                            $('<span>' + output + '</span>').appendTo('#success'); // add text to success feedback
                            $("#error").hide();
                            $("#success").show(); // show success feedback
                            $('#saldo').text(numberWithSpaces(money_in_bank) + ' kr'); // Edit numbers for bank
                            $('#saldo_top').text(numberWithSpaces(money_in_bank) + ' kr'); // Edit numbers for top container
                            $('#saldo_left').text(numberWithSpaces(money_on_hand) + ' kr'); // Edit numbers for left container

                            $("#number_bank").val(''); // Empty input field
                        }
                    }
                );
            });
        });

        $(document).ready(function() {
            $("#money_out").click(function() {
                $("#error").empty(); // empty error div
                $("#success").empty(); // empty success div

                var money = $('#number_bank').val().replace(/\s/g, ''); // get value from input field

                $.post("app/banken/banken_ta_ut.php", {
                        money: money
                    },
                    function(output, res, status) {
                        if (~output.indexOf("Ugyldig")) { // if output contains 'ikke'
                            $('<span>' + output + '</span>').appendTo('#error'); // add text to error feedback
                            $("#error").show(); // show error feedback
                            $("#success").hide(); // show error feedback
                        } else {
                            var saldo = $('#saldo').text().replace(/[^0-9]/gi, '');
                            var penger = $('#saldo_left').text().replace(/[^0-9]/gi, '');

                            var money_in_bank = parseInt(saldo) - parseInt(money);
                            var money_on_hand = parseInt(penger) + parseInt(money);

                            $('<span>' + output + '</span>').appendTo('#success'); // add text to success feedback
                            $("#error").hide();
                            $("#success").show(); // show success feedback
                            $('#saldo').text(numberWithSpaces(money_in_bank) + ' kr'); // Edit numbers for bank
                            $('#saldo_top').text(numberWithSpaces(money_in_bank) + ' kr'); // Edit numbers for top container
                            $('#saldo_left').text(numberWithSpaces(money_on_hand) + ' kr'); // Edit numbers for left container

                            $("#number_bank").val(''); // Empty input field
                        }
                    }
                );
            });
        });
    </script>
<?php } ?>