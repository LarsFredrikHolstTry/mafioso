<?php

$price = 100;

$number = uniqid();
$varray = str_split($number);
$len = sizeof($varray);
$otp = array_slice($varray, $len - 5, $len);
$otp = implode(",", $otp);
$otp = str_replace(',', '', $otp);
$random_string = $otp;
$pin = mt_rand(1000, 9999);

if (isset($_POST['make_konto'])) {
    if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) < $price) {
        echo feedback("Du har ikke nok poeng for å opprette en swissbank konto", "fail");
    } else {
        $sql = "INSERT INTO swiss (SWISS_account, SWISS_password, SWISS_saldo) VALUES (?,?,?)";
        $pdo->prepare($sql)->execute([$random_string, $pin, 0]);

        $text = "Swissbank konto er " . $random_string . " og PIN-koden er " . $pin . "";
        send_notification($_SESSION['ID'], $text, $pdo);

        take_poeng($_SESSION['ID'], $price, $pdo);

        echo feedback("Du har opprettet en bankkonto i Sveits. Kontonavn og passord kommer som varsel", "success");
    }
}

if (isset($_POST['sjekk_saldo'])) {
    $account_name = $_POST['account_name'];
    $account_pin =  $_POST['account_pin'];

    $query = $pdo->prepare("SELECT SWISS_saldo FROM swiss WHERE SWISS_account = ? AND SWISS_password = ?");
    $query->execute(array($account_name, $account_pin));
    $SWISS_row = $query->fetch(PDO::FETCH_ASSOC);

    if (!$SWISS_row) {
        echo feedback("Feil kontonavn eller PIN-kode", "error");
    } else {
        echo feedback("Saldo på konto " . $account_name . " er " . number($SWISS_row['SWISS_saldo']) . " kr", "success");
    }
}

if (isset($_POST['money_out'])) {
    $account_name = $_POST['account_name'];
    $account_pin =  $_POST['account_pin'];
    $account_money = remove_space($_POST['account_money']);

    $query = $pdo->prepare("SELECT SWISS_saldo FROM swiss WHERE SWISS_account = ? AND SWISS_password = ?");
    $query->execute(array($account_name, $account_pin));
    $SWISS_row = $query->fetch(PDO::FETCH_ASSOC);

    if (!is_numeric($account_money) || $account_money < 0) {
        echo feedback("Ugyldig beløp", "error");
    } elseif (!$SWISS_row) {
        echo feedback("Feil kontonavn eller PIN-kode", "error");
    } elseif ($account_money > $SWISS_row['SWISS_saldo']) {
        echo feedback("Det er ikke nok penger på konto", "error");
    } else {
        $amount = floor($account_money);
        $sql = "UPDATE swiss SET SWISS_saldo = (SWISS_saldo - $amount) WHERE SWISS_account='" . $account_name . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        give_money($_SESSION['ID'], $amount, $pdo);

        echo feedback(number($amount) . " kr ble tatt ut av konto " . $account_name, "success");
    }
}

if (isset($_POST['money_in'])) {
    $account_name = $_POST['account_name'];
    $account_money = remove_space($_POST['account_money']);

    $query = $pdo->prepare("SELECT SWISS_saldo FROM swiss WHERE SWISS_account = ?");
    $query->execute(array($account_name));
    $SWISS_row = $query->fetch(PDO::FETCH_ASSOC);

    if (!is_numeric($account_money) || $account_money < 0) {
        echo feedback("Ugyldig beløp", "error");
    } elseif (!$SWISS_row) {
        echo feedback("Kontonavnet eksisterer ikke", "error");
    } elseif ($account_money > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
        echo feedback("Du kan ikke sette inn mer penger enn du har på hånden", "error");
    } else {
        $amount_10 = 0.10 * $account_money;
        $amount = floor($account_money - $amount_10);
        $sql = "UPDATE swiss SET SWISS_saldo = (SWISS_saldo + $amount) WHERE SWISS_account='" . $account_name . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        take_money($_SESSION['ID'], $amount, $pdo);

        echo feedback(number($amount) . " kr ble satt inn på konto " . $account_name, "success");
    }
}

?>

<div class="col-12 single" style="margin-top: -10px;">
    <div class="col-6">
        <div class="content">
            <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            <h4>Opprett konto</h4>
            <p class="description">Du kan opprette en konto i Sveits for 100 poeng. Alle med din swissbank kontonavn kan sette inn i kontoen, men kun de med swissbank PIN-kode kan ta ut penger. Du vil få swissbank konto og PIN-kode som varsel, dette må skrives ned da det ikke kan hentes ut senere. 1% av summen blir trukket ved midnatt.</p>
            <p>Pris: 100 poeng</p>
            <form method="post">
                <input type="submit" name="make_konto" value="Opprett konto">
            </form>
            <br>
            <h3>Sjekk saldo</h3>
            <form method="post">
                <div class="col-6">
                    Swissbank kontonavn
                    <input type="text" name="account_name" placeholder="<?php echo $random_string; ?>">
                </div>
                <div class="col-6">
                    Swissbank PIN-kode
                    <input type="text" name="account_pin" placeholder="****">
                </div>
                <div style="clear: both;"></div>
                <input style="margin: 10px;" type="submit" name="sjekk_saldo" value="Sjekk saldo">
            </form>
        </div>
    </div>
    <div class="col-6">
        <div class="content">
            <h3>Sett inn penger</h3>
            <p class="description">Ved inntak vil 10% av summen gå til staten.</p>
            <form method="post">
                <div class="col-6">
                    <p>Swissbank kontonavn</p>
                    <input type="text" name="account_name" placeholder="<?php echo $random_string; ?>">
                </div>
                <div class="col-6">
                    <p>Beløp</p>
                    <input type="text" id="number" name="account_money">
                </div>
                <input style="margin: 10px;" type="submit" name="money_in" value="Sett inn">
                <div style="clear: both;"></div>
            </form>
            <br>
            <h3>Ta ut penger</h3>

            <form method="post">
                <div class="col-6">
                    <p>Swissbank kontonavn</p>
                    <input type="text" name="account_name" placeholder="<?php echo $random_string; ?>">
                </div>
                <div class="col-6">
                    <p>Swissbank PIN-kode</p>
                    <input type="text" name="account_pin" placeholder="****">
                </div>
                <div class="col-12" style="padding-top: 0px;">
                    <p>Beløp</p>
                    <input type="text" id="number2" name="account_money" placeholder="Beløp">
                    <input type="submit" name="money_out" value="Ta ut">
                </div>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>
</div>
<script>
    number_space("#number");
    number_space("#number2");
</script>