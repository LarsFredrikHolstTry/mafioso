<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_POST['new'])) {
    $ticker = $_POST['ticker'];
    $name = $_POST['name'];
    $ticker = strtoupper($_POST['ticker']);

    $ticker_price = GetPrice($ticker, "NOK");

    $stmt = $pdo->prepare("SELECT SUC_ticker FROM supported_crypto WHERE SUC_ticker = :ticker");
    $stmt->execute(['ticker' => $ticker]);
    $row_exist = $stmt->fetch();

    if ($row_exist) {
        echo feedback("" . $ticker . " er allerede lagt til", "fail");
    } else {
        if ($ticker_price) {
            $payout_pr_gpu = (15000000 / $ticker_price) / 10000;

            $sql = "INSERT INTO supported_crypto (SUC_ticker, SUC_name, SUC_date, SUC_price, SUC_price_pr_gpu) VALUES (?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ticker, $name, time(), $ticker_price, $payout_pr_gpu]);

            echo feedback("" . $ticker . " ble lagt til", "success");
        } else {
            echo feedback("" . $ticker . " eksisterer ikke på Coinbase", "error");
        }
    }
}

?>
<div class="content">
    <h3 style="margin-bottom: 10px;">Nyhet</h3>
    <form method="post">
        <p>Ticker (Må samsvare med noe som coinbase har)</p>
        <input type="text" name="ticker">
        <p>Fullt navn (F.eks Bitcoin)</p>
        <input type="text" name="name">
        <input type="submit" name="new" value="Legg til">
    </form>

    <h4 style="margin-top: 40px;">Eksisterende krypto</h4>
    <?php echo feedback("Ta konkakt ASAP med Skitzo om bilde ikke finnes", "blue"); ?>
    <table>
        <tr>
            <th style="width: 5%">Bilde</th>
            <th>Ticker</th>
            <th>Navn</th>
            <th>Pris i NOK for 1 stk</th>
            <th>Sist oppdatert</th>
            <th>Pris per gpu</th>
            <th>Pris for full krypto pr time</th>
        </tr>
        <?php

        $sql = "SELECT * FROM supported_crypto ORDER BY SUC_ticker";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        ?>
            <tr>
                <td><img style="width: 30px; height: auto; border-radius: 45px; border: 5px solid rgba(255,255,255,.2);" src="img/crypto/ticket/v2/<?php echo strtolower($row['SUC_ticker']); ?>.png"></td>
                <td><?php echo $row['SUC_ticker']; ?></td>
                <td><?php echo $row['SUC_name']; ?></td>
                <td><?php echo number($row['SUC_price']); ?> NOK</td>
                <td><?php echo date_to_text($row['SUC_date']); ?></td>
                <td><?php echo number_format((float)$row['SUC_price_pr_gpu'], 5, '.', ''); ?> NOK</td>
                <td><?php echo number(($row['SUC_price_pr_gpu'] * 10000) * $row['SUC_price']); ?> NOK</td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php

if (isset($_POST['update_price'])) {
    $query = $pdo->prepare('SELECT * FROM supported_crypto');
    $query->execute();
    foreach ($query as $row) {
        $sql = "UPDATE supported_crypto SET SUC_date = " . time() . ", SUC_price = ? WHERE SUC_ticker = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([GetPrice($row['SUC_ticker'], "NOK"), $row['SUC_ticker']]);
    }
}

?>

<form method="post">
    <input type="submit" value="Oppdater pris" name="update_price">

</form>