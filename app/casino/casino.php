<?php

if (!active_vip($_SESSION['ID'], $pdo)) {
    $cashback_takeout = 15;
} else {
    $cashback_takeout = 0;
}

if (!active_vip($_SESSION['ID'], $pdo)) {
    $cashback = 0.01;
} else {
    $cashback = 0.05;
}

$vip_price = 200;


if (isset($_POST['cashback_out'])) {
    if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) >= $cashback_takeout) {
        if (get_cashback_saldo($_SESSION['ID'], $pdo) > 0) {
            give_money($_SESSION['ID'], get_cashback_saldo($_SESSION['ID'], $pdo), $pdo);
            take_poeng($_SESSION['ID'], $cashback_takeout, $pdo);

            $cashback_out = get_cashback_saldo($_SESSION['ID'], $pdo);

            $sql = "UPDATE cashback SET CB_saldo = ? WHERE CB_acc_id = ? ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([0, $_SESSION['ID']]);

            echo feedback("Du tok ut " . number($cashback_out) . " fra cashback", "success");
        } else {
            echo feedback("Du har ingenting å ta ut", "error");
        }
    } else {
        echo feedback("Du har ikke nok poeng for å ta ut cashback", "error");
    }
}

if (isset($_GET['vip_bonus'])) {

    if (isset($_GET['buy'])) {
        if (!active_vip($_SESSION['ID'], $pdo) && AS_session_row($_SESSION['ID'], 'AS_points', $pdo) >= $vip_price) {
            take_poeng($_SESSION['ID'], $vip_price, $pdo);

            $stmt = $pdo->prepare("SELECT * FROM cashback WHERE CB_acc_id = ?");
            $stmt->execute([$_SESSION['ID']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $sql = "INSERT INTO cashback (CB_acc_id, CB_vip) VALUES (?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['ID'], 1]);
            } else {
                $sql = "UPDATE cashback SET CB_vip = ? WHERE CB_acc_id = ? ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([1, $_SESSION['ID']]);
            }

            echo feedback("Du kjøpte VIP bonus for casinoet!", "success");
        } else {
            echo feedback("Ugyldig! Du har enten for lite poeng eller allerede er VIP", "error");
        }
    }

?>
    <div class="col-7 single">
        <div class="content">
            <img class="action_image" src="img/action/gambling/vip_bonus.png">
            <?php if (active_vip($_SESSION['ID'], $pdo)) { ?>
                <?php echo feedback("Du har allerede VIP bonus!", "blue"); ?>
            <?php } ?>
            <h3>VIP Bonus</h3>
            <br>
            <b>Godene med VIP Bonus:</b>
            <ul style="margin-top: 5px;">
                <li><span>•</span> Med VIP bonus vil du få 0,05% cashback på alle kasinoets spill!</li>
                <li>• Du får også gratis uttak av cashback! Noe som vanligvis koster 15 poeng.</li>
                <li>• Høyere maksbet på terninger og keno</li>
                <li>• VIP Skilt bak brukernavn</li>
            </ul>
            <center style="margin-top: 20px;">
                <a href="?side=casino" style="color: grey;">Tilbake til kasino</a>
                <?php if (!active_vip($_SESSION['ID'], $pdo)) { ?>
                    &#160;&#160;
                    <a href="?side=casino&vip_bonus&buy" class="a_as_button">Kjøp (<?php echo $vip_price; ?> poeng)</a>
                <?php } ?>
            </center>
        </div>
    </div>
<?php } else { ?>

    <div class="col-10 single">
        <div class="content">
            <div class="col-12">
                <div class="col-6">
                    <h4>VIP bonus</h4>
                    <p class="description">Med VIP bonus får du opp til 0,05% cashback på spillene dine og gratis uttak av cashback!</p>
                    <br>
                    <a href="?side=casino&vip_bonus" class="a_as_button_secondary">LES MER</a>
                </div>
                <div class="col-6">
                    <img class="action_image" src="img/action/gambling/vip_bonus.png">
                </div>
            </div>
            <div class="col-12">
                <center>Din cashback saldo: <?php echo number(get_cashback_saldo($_SESSION['ID'], $pdo)) . ' / ' . number(get_max_cashback(active_vip($_SESSION['ID'], $pdo))); ?> kr <span onclick="showDiv()" class="description info">(info)</span>
                    <form method="post"><input type="submit" name="cashback_out" value="Ta ut cashback saldo <?php if (!active_vip($_SESSION['ID'], $pdo)) { ?>for <?php echo $cashback_takeout; ?> poeng <?php } ?>"></form>
                </center>
                <div id="guide" style="display:none;">
                    <?php

                    echo feedback("Hver gang du taper eller vinner på casinoet vil du få " . $cashback . "% i cashback av det du satset. Pengene vil du kun få hvis spillet går gjennom. Du får ikke cashback av å sette ett spill på kast mynt eller terninger og tiden går ut.", "blue");
                    ?>
                </div>
                <script>
                    function showDiv() {
                        document.getElementById('guide').style.display = "block";
                    }
                </script>
            </div>
            <a href="?side=lotto">
                <div class="col-3">
                    <img class="action_image" src="img/action/gambling/lotto.png">
                    <b>Lotto</b><span class="description" style="float: right;"><?php echo $cashback; ?>% cashback</span>
                </div>
            </a>
            <a href="?side=kast_mynt">
                <div class="col-3">
                    <img class="action_image" src="img/action/gambling/kast_mynt.png">
                    <b>Kast mynt</b><span class="description" style="float: right;"><?php echo $cashback; ?>% cashback</span>
                </div>
            </a>
            <a href="?side=keno">
                <div class="col-3">
                    <img class="action_image" src="img/action/gambling/keno.png">
                    <b>Keno </b><span class="description" style="float: right;"><?php echo $cashback; ?>% cashback</span>
                </div>
            </a>
            <a href="?side=terninger">
                <div class="col-3">
                    <img class="action_image" src="img/action/gambling/terninger.png">
                    <b>Terninger</b><span class="description" style="float: right;"><?php echo $cashback; ?>% cashback</span>
                </div>
            </a>
            <a href="?side=blackjack">
                <div class="col-3">
                    <img class="action_image" src="img/action/gambling/blackjack.png">
                    <b>Blackjack
                        <!--<span style="color: orange;">Stengt</span>-->
                    </b><span class="description" style="float: right;"><?php echo $cashback; ?>% cashback</span>
                </div>
            </a>
            <a href="?side=miner">
                <div class="col-3">
                    <img class="action_image" src="img/action/gambling/blackjack.png">
                    <b>Miner</b><span class="description" style="float: right;"><?php echo $cashback; ?>% cashback</span>
                </div>
            </a>
            <!--         <a href="?side=hestelop">
            <div class="col-3">
                <img class="action_image" src="img/action/gambling/hestelop.png">
                <b>Hesteveddeløp</b><span class="description" style="float: right;"><?php echo $cashback; ?>% cashback</span>
            </div>
        </a> -->
            <div style="clear: both;"></div>
        </div>
    </div>

<?php } ?>

<style>
    .info:hover {
        text-decoration: underline;
        cursor: pointer;
    }
</style>