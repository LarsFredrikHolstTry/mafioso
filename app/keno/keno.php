<?php

$min_bet = 100000;

if (active_vip($_SESSION['ID'], $pdo)) {
    $max_bet = 2000000000;
} else {
    $max_bet = 1000000000;
}

$payout = array(0, .25, .5, 1, 2, 3, 5, 6, 8, 10, 15);

if (isset($_POST['numbers'])) {
    user_log($_SESSION['ID'], $side, "Starter spill", $pdo);
    $amount_of_numbers = 0;
    $arr_user = array();
    $arr_npc =  array();
    if (isset($_POST['bet'])) {
        $bet = remove_space($_POST['bet']);
    } else {
        $bet = null;
    }

    for ($i = 1; $i < 51; $i++) {
        if (isset($_POST[$i])) {
            $amount_of_numbers++;
            array_push($arr_user, $i);
        }
    }

    if (empty($bet) || $bet < 0 || !is_numeric($bet) || $bet > AS_session_row($_SESSION['ID'], 'AS_money', $pdo) || $bet > $max_bet || $bet < $min_bet) {
        user_log($_SESSION['ID'], $side, "Ugyldig innsats: " . $bet, $pdo);
        echo feedback("Ugyldig innsats", "error");
    } elseif ($amount_of_numbers < 10) {
        user_log($_SESSION['ID'], $side, "Velger mindre enn 10 tall", $pdo);
        echo feedback("Du må velge 10 tall!", "error");
    } elseif ($amount_of_numbers > 10) {
        user_log($_SESSION['ID'], $side, "Velger mer enn 10 tall", $pdo);
        echo feedback("Du kan ikke velge mer enn 10 tall!", "error");
    } else {
        do {
            $rand_num = mt_rand(1, 50);
            if (!in_array($rand_num, $arr_npc)) {
                array_push($arr_npc, $rand_num);
            }
        } while (count($arr_npc) < 10);

        $new_arr = array_intersect($arr_npc, $arr_user);
        $antall_rette = count($new_arr);



        if ($antall_rette >= 3) {
            echo '<div class="feedback success">
        <span>';
        } else {
            echo '<div class="feedback fail">
        <span>';
        }

        echo 'Du fikk ' . $antall_rette . ' rette ';

        if ($antall_rette == 0) {
            take_money($_SESSION['ID'], $bet, $pdo);
            update_gambling($_SESSION['ID'], -$bet, $pdo);

            user_log($_SESSION['ID'], $side, "Får " . $antall_rette . " rette og taper " . $bet . "kr", $pdo);
            echo ' og taper ' . number($bet) . ' kr';
        } elseif ($antall_rette == 1 || $antall_rette == 2) {
            take_money($_SESSION['ID'], $bet - $payout[$antall_rette] * $bet, $pdo);
            update_gambling($_SESSION['ID'], - ($bet - $payout[$antall_rette] * $bet), $pdo);

            user_log($_SESSION['ID'], $side, "Får " . $antall_rette . " rette og taper " . ($bet - $payout[$antall_rette] * $bet) . "kr", $pdo);
            echo ' og taper ' . number($bet - $payout[$antall_rette] * $bet) . ' kr';
        } else {
            give_money($_SESSION['ID'], $bet * $payout[$antall_rette], $pdo);
            update_gambling($_SESSION['ID'], $bet * $payout[$antall_rette], $pdo);

            user_log($_SESSION['ID'], $side, "Får " . $antall_rette . " rette og vinner " . $bet * $payout[$antall_rette] . "kr", $pdo);
            echo ' og vinner ' . number($bet * $payout[$antall_rette]) . ' kr';
        }

        give_cashback($_SESSION['ID'], $bet, $pdo);

        header("Location: ?side=keno&rette=" . $antall_rette . "&bet=" . $bet . "");
    }
}

if (isset($_GET['rette'])) {
    if ($_GET['rette'] >= 3) {
        echo '<div class="feedback success">
        <span>';
    } else {
        echo '<div class="feedback fail">
        <span>';
    }

    echo 'Du fikk ' . $_GET['rette'] . ' rette ';

    if ($_GET['rette'] == 0) {
        echo ' og taper ' . number($_GET['bet']) . ' kr';
    } elseif ($_GET['rette'] == 1 || $_GET['rette'] == 2) {
        echo ' og taper ' . number($_GET['bet'] - $payout[$_GET['rette']] * $_GET['bet']) . ' kr';
    } else {
        echo ' og vinner ' . number($_GET['bet'] * $payout[$_GET['rette']]) . ' kr';
    }
    echo '</span></div>';
}

?>
<div class="col-9 single">
    <div class="content keno">
        <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
        <form method="post" name="test">
            <div class="col-12 single">
                <div class="col-8 single boxes">
                    <ul>
                        <?php for ($i = 1; $i < 51; $i++) { ?>
                            <li>
                                <input class="checkbox" type="checkbox" id="<?php echo $i; ?>" name="<?php echo $i; ?>" value="<?php echo $i; ?>">
                                <label for="<?php echo $i; ?>"><?php echo $i; ?></label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div style="clear: both;"></div>
            <center style="margin-bottom: 10px; margin-top: 10px;">
                <center>
                    <div style="width: 200px" ; class="noselect a_as_button">Velg 10 tilfeldige tall</div>
                </center>

                <p class="description">
                    Minimum innsats: <?php echo number($min_bet); ?> kr
                    <br>
                    Maksimum innsats: <?php echo number($max_bet); ?> kr
                </p>

                <input type="text" name="bet" id="number" placeholder="Innsats">

                <input type="submit" name="numbers" value="Spill">
                <br><br>
                <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number($min_bet); ?>'" value="Min bet" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number($max_bet); ?>'" value="Max bet" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) * 0.1); ?>'" value="10%" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) * 0.25); ?>'" value="25%" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) * 0.5); ?>'" value="50%" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) * 0.75); ?>'" value="75%" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo)); ?>'" value="All in" />
            </center>
        </form>

    </div>
</div>

<div class="col-3 single" style="margin-top: 10px; margin-bottom: 10px;">
    <div class="content keno">
        <h4>Utbetaling</h4>
        <ul>
            <?php for ($i = 0; $i < count($payout); $i++) { ?>
                <li><?php echo $i ?> rette = <?php echo $payout[$i]; ?>x betaling</li>
            <?php } ?>
            <div style="clear: both;"></div>
        </ul>
    </div>
</div>

<style>
    .keno ul {
        padding: 0;
        margin: 0;
        clear: both;
    }

    .keno li {
        list-style-type: none;
        list-style-position: outside;
        padding: 7px;
        float: left;
    }

    input[type="checkbox"]:not(:checked),
    input[type="checkbox"]:checked {
        position: absolute;
        left: -9999%;
    }

    input[type="checkbox"]+label {
        display: inline-block;
        padding: 10px;
        cursor: pointer;
        color: var(--text-grey-color);
        background-color: var(--left-container-color);
        width: 20px;
        text-align: center;
    }

    input[type="checkbox"]:checked+label {
        color: white;
        background-color: var(--button-bg-color);
    }

    .noselect {
        -webkit-touch-callout: none;
        /* iOS Safari */
        -webkit-user-select: none;
        /* Safari */
        -khtml-user-select: none;
        /* Konqueror HTML */
        -moz-user-select: none;
        /* Old versions of Firefox */
        -ms-user-select: none;
        /* Internet Explorer/Edge */
        user-select: none;
        /* Non-prefixed version, currently
                                          supported by Chrome, Edge, Opera and Firefox */
    }
</style>

<script>
    number_space("#number");

    $("input:checkbox").click(function() {
        if ($("input:checkbox:checked").length > 10) {
            return false;
        }
    });

    var chkbox_list = new Array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50');

    var shuffled_arr = shuffle(chkbox_list);
    $('input[type=checkbox]').prop('checked', false);
    for (var i = 0; i < 10; i++) {
        $('#' + shuffled_arr[i]).prop('checked', true);
    }

    $('.a_as_button').click(function() {
        var shuffled_arr = shuffle(chkbox_list);
        $('input[type=checkbox]').prop('checked', false);
        for (var i = 0; i < 10; i++) {
            $('#' + shuffled_arr[i]).prop('checked', true);
        }
    })

    function shuffle(o) {
        for (var j, x, i = o.length; i; j = parseInt(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
        return o;
    };
</script>