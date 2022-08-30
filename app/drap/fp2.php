<?php

if($ACC_session_row['ACC_type'] != 3){
    echo feedback("Ingen adgang", "fail");
} else {
    
$defence = $AS_session_row['AS_def'];
$my_price_pr = 0;

$fp_from[0] = 0;
$fp_to[0] = 10000;

$fp_from[1] = $fp_to[0];
$fp_to[1] = 50000;

$fp_from[2] = $fp_to[1];
$fp_to[2] = 1000000;

$fp_from[3] = $fp_to[2];
$fp_to[3] = 5000000;

$fp_from[4] = $fp_to[3];
$fp_to[4] = INF;

$price_pr[0] = 1500;
$price_pr[1] = 2500;
$price_pr[2] = 3000;
$price_pr[3] = 5000;
$price_pr[4] = 10000;

$total_price = 0;

if(isset($_POST['post'])){
    if(isset($_POST['amount'])){
        $amount = remove_space($_POST['amount']);
        
        $defenceAfterBuying = $defence + $amount;

        foreach ($price_pr as $key => $value) {
            for ($i=$defence; $i <= $defenceAfterBuying; $i++) { 
                if ($i > $fp_from[$key] && $i <= $fp_to[$key]) {
                    $total_price += $price_pr[$key];
                }
            }
        }
        
        if(is_numeric($amount) && $amount > 0){
            if($total_price <= $AS_session_row['AS_money']){
                
                give_fp($amount, $_SESSION['ID'], $pdo);
                take_money($_SESSION['ID'], $total_price, $pdo);
                
                header("Location: ?side=drap&p=fp2&kjop=".$amount."");
            } else {
                echo feedback("Du har ikke råd til ".number($amount)." forsvar", "error");
            }
        } else {
            echo feedback("Ugyldig input", "fail");
        }
    }
}

if(isset($_GET['kjop'])){
    if(is_numeric($_GET['kjop'])){
        echo feedback("Du har kjøpt ".number($_GET['kjop'])." forsvarspoeng", "success");
    } else {
        echo feedback("Ugyldig input", "fail");
    }
}

?>
<div class="row">
    <div class="col-6" style="padding: 0 2.5 0 0;">
        <div class="content">
            <div class="header">
                <span><img class="header_icon" src="img/icons/shield_add.png">Forsvarspoeng</span>
            </div>
            <div class="pad_10">
                <img src="img/drap/forsvarspoeng.png" style="max-width: 100%; display: block; margin-left: auto; margin-right: auto;"><br>
                Ved å kjøpe forsvarspoeng har du større sjanse for å overleve gjennom spillet. Jo mer forsvarspoeng du har desto mer kuler må brukes for å angripe deg.
                <br><br>
                <div style="  margin: auto; width: 50%;">
                    <form method="post">
                        <input type="text" id="number" style="width: 78%;" name="amount" placeholder="Antall...">
                        <input type="submit" name="post" value="Kjøp">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4" style="padding: 0 0 0 2.5;">
        <div class="content">
            <div class="header">
                <span><img class="header_icon" src="img/icons/shield.png">Prisliste</span>
            </div>
            <div class="pad_10">                
                <table>
                    <tr>
                        <th>Antall forsvarspoeng</th>
                        <th>Pris pr</th>
                    </tr>
                    <?php  for($i = 0; $i < count($fp_from); $i++){ ?>
                    <tr>
                        <td><?php echo number($fp_from[$i]); ?> - <?php echo number($fp_to[$i]); ?></td>
                        <td><?php echo number($price_pr[$i]); ?> kr</td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script>

number_space("#number");

</script>