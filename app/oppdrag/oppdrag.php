<?php

if(isset($_POST['done'])){
    $value = 0;

    if(in_array(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), $missions_with_generic_values)){
        $value = get_mission_value($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), $pdo);
    } else {
        $value = AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo);
    }

    check_mission_done($value, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
}

if(isset($_GET['v'])){
    if(mission_money($_GET['v']) > 0){
        $money_payment = true;
    } else {
        $money_payment = false;
    }

    if(mission_exp($_GET['v']) > 0){
        $exp_payment = true;
    } else {
        $exp_payment = false;
    }
    
    if($money_payment && $exp_payment){
        echo feedback("Du har utført oppdrag ".($_GET['v'] + 1)." og får ".number(mission_money($_GET['v']))." kr og ".number(mission_exp($_GET['v']))." exp", "success");
    } elseif($exp_payment && !$money_payment){
        echo feedback("Du har utført oppdrag ".($_GET['v'] + 1)." og får ".number(mission_exp($_GET['v']))." exp", "success");
    } elseif($money_payment && !$exp_payment){
        echo feedback("Du har utført oppdrag ".($_GET['v'] + 1)." og får ".number(mission_money($_GET['v']))." kr", "success");
    }
    
    check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);
}

?>
<div class="col-11 single">

    <div class="col-7" style="padding-top: 0px;">
        <div class="content" style="text-align: center;">
            <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            <?php if(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) >= $total_missions){
                echo 'Du har utført alle oppdrag som er på spillet for tiden. Flere oppdrag kommer fortløpende. Har du tips til oppdrag så send de i support-funksjonen';
            } else { ?>
            <p class="description"><?php echo $mission_text[AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)]; ?></p>
            <p>Ditt oppdrag: <?php echo mission(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)); ?></p>
            <?php 

            $value = 0;

            if($missions_with_generic_values && in_array(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), $missions_with_generic_values)){
                $value = get_mission_value($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), $pdo);
            } else {
                $value = AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo);
            }

            if (in_array(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo),$missions_numberz)) {

            ?>
            <p>Du har: <?php echo number($value)." av ".number(mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo))); ?></p>
            <?php } ?>
            <form method="post">
                <input type="submit" name="done" value="Ferdig">
            </form>
            <?php } ?>
        </div>
    </div>
    <div class="col-5" style="padding-top: 0px;">
        <div class="content" style="overflow-y: scroll; height: 400px;">
            <h4>Oppdragsliste</h4>
            <ul>
            <?php for($i = 0; $i < $total_missions; $i++){ ?>
                <li>
                <p class="description" style="<?php if(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == $i){ echo 'color: white;'; } elseif(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) > $i){ echo 'text-decoration: line-through;'; } ?> margin: 2px 0px;"><b>Oppdrag <?php echo $i + 1; echo ': </b>'; echo mission($i); ?></b></li>
            <?php } ?>
            </ul>
        </div>
    </div>
</div>