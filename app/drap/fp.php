<?php

if(!isset($_GET['side'])){
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

$price = 5000;
    
if(half_fp($_SESSION['ID'], $pdo)){
    echo feedback("Du har nylig utført et angrep og har halvt forsvar i 12 timer fra angrepet tok sted", "error");
}


if(isset($_POST['post'])){
    if(isset($_POST['amount'])){
        $amount = remove_space($_POST['amount']);
        
        $my_price_pr = $amount * $price;

        if(is_numeric($amount) && $amount > 0){
            if($my_price_pr <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo)){
                $total_price = $my_price_pr;
                
                give_fp($amount, $_SESSION['ID'], $pdo);
                take_money($_SESSION['ID'], $total_price, $pdo);
                
                header("Location: ?side=drap&p=fp&kjop=".$amount."");
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
<div class="col-9 single">
    <div class="col-8">
        <div class="content">
            <h3>Forsvarspoeng</h3>
            <p class="description">Ved å kjøpe forsvarspoeng har du større sjanse for å overleve gjennom spillet. Jo mer forsvarspoeng du har desto mer kuler må brukes for å angripe deg.</p>
            <div style="  margin: auto; width: 50%;">
                <form method="post">
                    <input type="text" id="number" style="width: 78%;" name="amount" placeholder="Antall...">
                    <input type="submit" name="post" value="Kjøp">
                </form>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="content">
            <h3>Prisliste</h3>
            <p class="description">Pris pr forsvarspoeng er satt til <?php echo number($price); ?> kr</p>
        </div>
    </div>
</div>
<script>

number_space("#number");

</script>

<?php } ?>