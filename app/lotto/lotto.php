<?php

$price = 5000;
$price_jackpot = 4500;
$jackpot = lotto_jackpot($pdo) * $price_jackpot;
$total_coupon = lotto_total_coupon($pdo);

if(isset($_POST['buy'])){
    $amount = remove_space($_POST['amount']);
    $total_price = $amount * $price;
    if($amount <= 0){
        echo feedback("Du må kjøpe minst 1 kupong", "fail");
    } elseif($total_price <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo)){
        buy_lotto_coupon($_SESSION['ID'], $amount, $pdo);
        take_money($_SESSION['ID'], $total_price, $pdo);
        update_gambling($_SESSION['ID'], -$total_price, $pdo);
        
        if(get_cashback_saldo($_SESSION['ID'], $pdo) < get_max_cashback(active_vip($_SESSION['ID'], $pdo))){
            give_cashback($_SESSION['ID'], $total_price, $pdo);
        }
                
        header("Location: ?side=lotto&buy=$amount");
    } else {
        echo feedback("Du har ikke nok penger til å kjøpe ".number($amount)." lottokuponger", "fail");
    }
}

if(isset($_GET['buy'])){
    if($_GET['buy'] == 1){
        echo feedback("Du kjøpte 1 lottokupong", "success");
    } else {
        echo feedback("Du kjøpte ".number($_GET['buy'])." lottokuponger", "success");
    }
}

?>

<div class="col-12 single" style="margin-top: -10px;">
    <div class="col-7">
        <div class="content">
        <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            <div class="col-12">
                <div class="col-5">
                    <h4>Potten: </h4><?php echo number($jackpot); ?> kr<br><br>
                </div>
                <div class="col-7">
                    <h4>Pris pr lottokupong:</h4> <?php echo number($price); ?> kr<br><br>
                </div>
                <div class="col-12">
                    <h4>Trekning:</h4> <span style="color: darkgrey;">04:00,</span> 08:00, <span style="color: darkgrey;">12:00,</span> 16:00, <span style="color: darkgrey;">20:00,</span> 00:00<br><br>
                </div>
                <?php if(active_lotto($_SESSION['ID'], $pdo)){ ?>
                <p class="description">Du har <?php 

                if(lotto_coupon($_SESSION['ID'], $pdo) >= 2){
                    echo '<b>'.number(lotto_coupon($_SESSION['ID'], $pdo))."</b> kuponger"; 
                } else {
                    echo '<b>'.lotto_coupon($_SESSION['ID'], $pdo)."</b> kupong";
                }

                 } ?></p>
                <form method="post">
                    <input type="text" name="amount" id="number" style="width: 70%; float: left;" placeholder="Antall lottokuponger">
                    <input type="submit" name="buy" style="width: 29%; float: right;" value="Kjøp">
                </form>
                <div class="col-12">
                    <p class="description">Premien for vinneren er potten og 50 EXP</p>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
    <div class="col-5">
        <div class="content">
                <h3 style="margin-bottom: 10px;">10 siste vinnere</h3>
                <table>
                    <tr>
                        <th>Vinner</th>
                        <th>Sum</th>
                        <th>Dato</th>
                    </tr>
                    <?php 

                    $sql = "SELECT * FROM lotto_winner ORDER BY LOTTOW_date DESC LIMIT 10";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                    <tr>
                        <td><?php echo ACC_username($row['LOTTOW_acc_id'], $pdo); ?></td>
                        <td><?php echo number($row['LOTTOW_sum']); ?> kr</td>
                        <td><?php echo date_to_text($row['LOTTOW_date']); ?></td>
                    </tr>
                    <?php } ?>
                </table>
        </div>
    </div>
</div>
<script>

number_space("#number");

</script>
