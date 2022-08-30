<?php

if(!isset($_GET['side'])){
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {
    
$protect_price = 500000; // 500 000

if(isset($_POST['submit'])){
    if($protect_price > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)){
        echo feedback("Du har ikke nok penger til å kjøpe ransbeskyttelse", "fail");
    } elseif(AS_session_row($_SESSION['ID'], 'AS_protection', $pdo) == 1) {
        echo feedback("Du har allerede ransbeskyttelse", "fail");
    } else {
        
    if(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 1){
        mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
    }
        
        $sql = "UPDATE accounts_stat SET AS_protection = 1 WHERE AS_id='".$_SESSION['ID']."'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        take_money($_SESSION['ID'], $protect_price, $pdo);
                
        header("Location: ?side=drap&p=ransbeskyttelse&kjop");
    }
}

if(isset($_GET['kjop'])){
    echo feedback("Du kjøpte ransbeskyttelse", "success");
}

?>
<div class="col-6" style="margin: 0 auto; padding: 0; float: none;">
    <div class="content">
        <img class="action_image" src="img/action/actions/ransbeskyttelse.png">
        <p class="description">Med en ransbeskyttelse kan du beskytte deg mot ran fra andre spillere. Du mister 1 stk ransbeskyttelse ved midnatt.</p>
        <p class="description">Pris for beskyttelse er <?php echo number($protect_price); ?> kr og dekker penger på hånden din.</p>

        <?php if(AS_session_row($_SESSION['ID'], 'AS_protection', $pdo) <= 0){ ?>
        <form method="post">
            <input type="submit" name="submit" value="Kjøp ransbeskyttelse">
        </form>
            <?php } else { 
    if(AS_session_row($_SESSION['ID'], 'AS_protection', $pdo) == 1){
        echo 'Du har ransbeskyttelse i '. AS_session_row($_SESSION['ID'], 'AS_protection', $pdo) .' dag';
    } else {
        echo 'Du har ransbeskyttelse i '. AS_session_row($_SESSION['ID'], 'AS_protection', $pdo) .' dager';
    }
            } ?>
    </div>
</div>
<?php } ?>