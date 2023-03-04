<div class="col-4">
        <h3>Velkommen tilbake <?php echo ACC_username($_SESSION['ID'], $pdo); ?></h3>
        <br>
        <div class="rankbar">
            <?php if (AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) < 15) { ?>
                <span><?php echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)); ?> av <?php echo number(exp_to(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo))); ?> EXP til <b class="rank"><?php echo rank_list(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) + 1); ?> </b></span>
                <p class="description">Framgang: <?php echo round(rank_progress(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)), 2); ?>%</p>
                <p class="description">Dagens EXP: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_daily_exp', $pdo)); ?></p>
            <?php } else {
                echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)) . ' EXP';
            } ?>
            <div id="progress"></div>
        </div>
        <div style="clear: both;"></div>
        <h3 style="margin-top: 20px;">Dersom du dør</h3>
        <p class="description">
            Dersom du dør vil du få en liten boost for å opprette en ny bruker:
        </p>
        <?php if (AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) >= 5) { ?>
            <ul class="description">
                <li><?php echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo) * 0.1) ?> EXP</li>
                <li><?php echo number(250000000) ?> kroner</li>
                <li>50 poeng</li>
                <li>5 energidrikker</li>
                <li>1 hemmelig kiste</li>
                <li>M4A4 som startvåpen</li>
            </ul>

        <?php } else {
            echo feedback("Du er ikke høy nok rank for å få boost ved dødsfall. Du må være minst ranken " . rank_list(5) . " for å få boost", "blue");
        } ?>
    </div>
    <div class="col-4">
        <h3>Statistikk</h3>
        <ul class="description">
            <li>EXP: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)); ?></li>

            <li>EXP i dag: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_daily_exp', $pdo)); ?></li>

            <li>Kriminalitet: <?php echo US_session_row($_SESSION['ID'], 'US_krim_v', $pdo) + US_session_row($_SESSION['ID'], 'US_krim_m', $pdo); ?> (<span style="color: #19bc63;"><?php echo US_session_row($_SESSION['ID'], 'US_krim_v', $pdo); ?></span>/<span style="color: orange;"><?php echo US_session_row($_SESSION['ID'], 'US_krim_m', $pdo); ?></span>)</li>

            <li>Biltyveri: <?php echo US_session_row($_SESSION['ID'], 'US_gta_v', $pdo) + US_session_row($_SESSION['ID'], 'US_gta_m', $pdo); ?> (<span style="color: #19bc63;"><?php echo US_session_row($_SESSION['ID'], 'US_gta_v', $pdo); ?></span>/<span style="color: orange;"><?php echo US_session_row($_SESSION['ID'], 'US_gta_m', $pdo); ?></span>)</li>

            <li>Brekk: <?php echo US_session_row($_SESSION['ID'], 'US_brekk_v', $pdo) + US_session_row($_SESSION['ID'], 'US_brekk_m', $pdo); ?> (<span style="color: #19bc63;"><?php echo US_session_row($_SESSION['ID'], 'US_brekk_v', $pdo); ?></span>/<span style="color: orange;"><?php echo US_session_row($_SESSION['ID'], 'US_brekk_m', $pdo); ?></span>)</li>

            <li>Stjel: <?php echo US_session_row($_SESSION['ID'], 'US_stjel_v', $pdo) + US_session_row($_SESSION['ID'], 'US_stjel_m', $pdo); ?> (<span style="color: #19bc63;"><?php echo US_session_row($_SESSION['ID'], 'US_stjel_v', $pdo); ?></span>/<span style="color: orange;"><?php echo US_session_row($_SESSION['ID'], 'US_stjel_m', $pdo); ?></span>)</li>

            <li>Hurtige oppdrag utført: <?php echo number(US_session_row($_SESSION['ID'], 'US_hurtig_oppdrag', $pdo)); ?></li>

            <li>Utbrytninger: <?php echo number(US_session_row($_SESSION['ID'], 'US_jail', $pdo)); ?></li>

            <?php $profit = total_sold_stocks($_SESSION['ID'], $pdo) - total_bought_stocks($_SESSION['ID'], $pdo); ?>
            <li>Aksjemarked profitt:
                <?php if ($profit < 0) { ?>
                    <span style="color: orange;"><?php echo number($profit); ?></span>
                <?php } else { ?>
                    <span style="color: #19bc63;"><?php echo number($profit); ?></span>
                <?php } ?>
                kr
            </li>

            <li>Gambling:
                <?php if (US_session_row($_SESSION['ID'], 'US_gambling', $pdo) < 0) { ?>
                    <span style="color: orange;"><?php echo number(US_session_row($_SESSION['ID'], 'US_gambling', $pdo)); ?></span>
                <?php } else { ?>
                    <span style="color: #19bc63;"><?php echo number(US_session_row($_SESSION['ID'], 'US_gambling', $pdo)); ?></span>
                <?php } ?>
                kr
            </li>

        </ul>
    </div>
    <div class="col-4">

    <?php 

if($_SESSION['ID'] == 1){
    if(isset($_POST['newUtfordring'])){
        create_new_utfordring($pdo);
    }

    ?>

<form method="post">
    <input type="submit" value="Lag ny utfordring" name="newUtfordring">
</form>

<?php 

}

if(isset($_GET['str'])){
    echo feedback($_GET['str'], 'success');
}

        $levelsArr[0] = 'Du er på nivå: Enkel<br> 10 til 15 minutter';
        $levelsArr[1] = 'Du er på nivå: Middels<br> 30 til 45 minutter';
        $levelsArr[2] = 'Du er på nivå: Vanskelig<br> 1 til 1,5 timer';
        $levelsArr[3] = 'Du er på nivå: Ekstrem<br><b>Tid:</b> 4 til 5 timer';

        $dagensUtfordring = getDagensUtfordring($_SESSION['ID'], $pdo);

        $query = $pdo->prepare("SELECT * FROM dagens_utfordring_user WHERE DAUTUS_acc_id = ?");
        $query->execute(array($_SESSION['ID']));
        $utford_user = $query->fetch(PDO::FETCH_ASSOC);
        
        $level = $utford_user['DAUTUS_level'] ?? 0;
        if($level > 3){
            $level = 3;
        }

        if($dagensUtfordring){
            ?>
            <h3>Dagens utfordring</h3>
            <p class="description"><?php echo $utford_user && $utford_user['DAUTUS_level'] > 3 ? 'Du er ferdig for i dag! Kom tilbake i morgen for nye utfordringer' : $levelsArr[$level] ?>
            <br><br>
            <?php


            if(isset($_POST['get_payment'])){
                $moneyPerCrime = 500000;
                $defencePerGta = 1350;
                $BulletsPerGta = 1;
                $MoneyPerGta = 1000000;
                $moneyPerRc = 1;  // Energidrikk eller forsvar
                $moneyPerSteal = 1; // Hemmelig kiste eller happy hour
                $defencePerRc = 1550;

                foreach($dagensUtfordring as $utfordring){
                    if($level == 0){
                        $moneyCrime = $dagensUtfordring['crime'] * $moneyPerCrime;

                        $outPutString = "Du fikk ".number($moneyCrime)." kr fra dagens enkle oppdrag!";

                        give_money($_SESSION['ID'], $moneyCrime, $pdo);

                        header("Location: ?dagens&str=$outPutString");
                        break;
                    } elseif($level == 1){
                        $moneyCrime = $dagensUtfordring['crime'] * $moneyPerCrime;
                        $defenceGTA = $dagensUtfordring['gta'] * $defencePerGta;
                        $outPutString = "Du fikk ".number($moneyCrime)." kr og ".number($defenceGTA)." forsvar fra dagens middels vanskelige oppdrag!";

                        give_money($_SESSION['ID'], $moneyCrime, $pdo);
                        give_fp($defenceGTA, $_SESSION['ID'], $pdo);

                        echo feedback($outPutString, 'success');

                        header("Location: ?dagens&str=$outPutString"); 
                        break;
                    } elseif($level == 2){
                        $moneyCrime = $dagensUtfordring['crime'] * $moneyPerCrime;
                        $bulletsGTA = $dagensUtfordring['gta'] * $BulletsPerGta;
                        $energydrinkOrDefenceForRc = mt_rand(0, 1);
                        if($energydrinkOrDefenceForRc == 0){
                            $outputSTR = "energidrikk";
                            update_things($_SESSION['ID'], 29, $pdo);
                        } else {
                            $defenceRC = $dagensUtfordring['rc'] * $defencePerRc;
                            $outputSTR = number($defenceRC). " forsvar";
                            give_fp($defenceRC, $_SESSION['ID'], $pdo);
                        }

                        $outPutString = "Du fikk ".number($moneyCrime)." kr, ".number($bulletsGTA)." kuler og ".$outputSTR." fra dagens vanskelige oppdrag!";

                        give_money($_SESSION['ID'], $moneyCrime, $pdo);
                        give_bullets($_SESSION['ID'], $bulletsGTA, $pdo);

                        echo feedback($outPutString, 'success');
                        header("Location: ?dagens&str=$outPutString");  
                        break;
                    } elseif($level == 3){
                        $moneyCrime = $dagensUtfordring['crime'] * $moneyPerCrime;
                        $moneyGTA = $dagensUtfordring['gta'] * $MoneyPerGta;
                        $energydrinkOrDefenceForRc = mt_rand(0, 1);
                        if($energydrinkOrDefenceForRc == 0){
                            $outputSTRRC = "energidrikk";
                            update_things($_SESSION['ID'], 29, $pdo);
                        } else {
                            $defenceRC = $dagensUtfordring['rc'] * $defencePerRc;
                            $outputSTRRC = number($defenceRC). " forsvar";
                            give_fp($defenceRC, $_SESSION['ID'], $pdo);
                        }

                        $happyHourOrHemmeligKiste = mt_rand(0, 1);
                        if($happyHourOrHemmeligKiste == 0){
                            $boost[0] = "1 time happy hour";
                            $boost[1] = "3 time happy hour";
                            $boost[2] = "6 time happy hour";
                            $boost[3] = "12 time happy hour";
                            $boost[4] = "24 time happy hour";
                            $happyHourOptions = mt_rand(0, 4);
                    
                            $outputSTR = $boost[$happyHourOptions];
                            
                            if($happyHourOptions == 0){
                                update_things($_SESSION['ID'], 34, $pdo);
                            } elseif($happyHourOptions == 1){
                                update_things($_SESSION['ID'], 35, $pdo);
                            } elseif($happyHourOptions == 2){
                                update_things($_SESSION['ID'], 36, $pdo);
                            } elseif($happyHourOptions == 3){
                                update_things($_SESSION['ID'], 37, $pdo);
                            } elseif($happyHourOptions == 5){
                                update_things($_SESSION['ID'], 38, $pdo);
                            }

                        } else {
                            $outputSTR = "hemmelig kiste";
                            update_things($_SESSION['ID'], 30, $pdo);
                        }

                        $outPutString = "Du fikk ".number($moneyCrime + $moneyGTA)." kr, ".$outputSTRRC." og ".$outputSTR." fra dagens ekstreme oppdrag!";

                        give_money($_SESSION['ID'], $moneyCrime, $pdo);

                        echo feedback($outPutString, 'success');

                        header("Location: ?dagens&str=$outPutString");
                        break;
                    }
                }
                
                $sql = "UPDATE dagens_utfordring_user SET DAUTUS_level = DAUTUS_level + 1 WHERE DAUTUS_acc_id='" . $_SESSION['ID'] . "'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }


        $dagensUtfordringIsDone = false;

        foreach($dagensUtfordring as $utfordring){
            if($level == 0){
                if(($utford_user['DAUTUS_crime'] ?? 0) >= $dagensUtfordring['crime']){
                    $dagensUtfordringIsDone = true;
                }
            } elseif($level == 1){
                if(
                    $utford_user['DAUTUS_crime'] >= $dagensUtfordring['crime'] 
                    && 
                    $utford_user['DAUTUS_gta'] >= $dagensUtfordring['gta']
                ){
                    $dagensUtfordringIsDone = true;
                }
            } elseif($level == 2){
                if(
                    $utford_user['DAUTUS_crime'] >= $dagensUtfordring['crime'] 
                    && 
                    $utford_user['DAUTUS_gta'] >= $dagensUtfordring['gta']
                    && 
                    $utford_user['DAUTUS_rc'] >= $dagensUtfordring['rc']
                ){
                    $dagensUtfordringIsDone = true;
                }
            } elseif($level == 3){
                if(
                    $utford_user['DAUTUS_crime'] >= $dagensUtfordring['crime'] 
                    && 
                    $utford_user['DAUTUS_gta'] >= $dagensUtfordring['gta']
                    && 
                    $utford_user['DAUTUS_rc'] >= $dagensUtfordring['rc']
                    && 
                    $utford_user['DAUTUS_stjel'] >= $dagensUtfordring['steal']
                ){
                    $dagensUtfordringIsDone = true;
                }
            }
        }

        ?>
        
        <div style="display: flex; flex-direction: column; gap: 10px;">

        <?php

            // Crime
            $crimePercentage = round((($utford_user['DAUTUS_crime'] ?? 0) / $dagensUtfordring['crime']) * 100, 2);
            $crimeProgressOutput = $crimePercentage > 100 ? 100 : $crimePercentage;
            ?>
                <div class="rankbar_dagens_utfordring">
                    <h4>Vellykkede kriminalitet</h4>
                    <p class="description">Framgang: <?php
                    echo ($utford_user['DAUTUS_crime'] ?? 0) . ' av ' . $dagensUtfordring['crime'] . ' ('.$crimeProgressOutput.'%)';
                    ?></p>
                    <div id="progress_crime"></div>
                </div>
                <style>
                    #progress_crime:after {
                        width: <?php echo $crimeProgressOutput.'%'; ?>;
                    }
                </style>
            <?php

            // Gta
            if(!!$dagensUtfordring['gta']){
                $gtaPercentage = round((($utford_user['DAUTUS_gta'] ?? 0) / $dagensUtfordring['gta']) * 100, 2);
                $gtaProgressOutput = $gtaPercentage > 100 ? 100 : $gtaPercentage;
            ?>
                <div class="rankbar_dagens_utfordring">
                    <h4>Vellykkede biltyveri</h4>
                    <p class="description">Framgang: <?php
                    echo ($utford_user['DAUTUS_gta'] ?? 0) . ' av ' . $dagensUtfordring['gta'] . ' ('.$gtaProgressOutput.'%)';
                    ?></p>
                    <div id="progress_gta"></div>
                </div>
                <style>
                    #progress_gta:after {
                        width: <?php echo $gtaProgressOutput.'%'; ?>;
                    }
                </style>
            <?php
            }

            // RC
            if(!!$dagensUtfordring['rc']){
                $rcPercentage = round((($utford_user['DAUTUS_rc'] ?? 0) / $dagensUtfordring['rc']) * 100, 2);
                $rcProgressOutput = $rcPercentage > 100 ? 100 : $rcPercentage;
            ?>
                <div class="rankbar_dagens_utfordring">
                    <h4>Vunnet kappløp</h4>
                    <p class="description">Framgang: <?php
                    echo ($utford_user['DAUTUS_rc'] ?? 0) . ' av ' . $dagensUtfordring['rc'] . ' ('.$rcProgressOutput.'%)';
                    ?></p>
                    <div id="progress_rc"></div>
                </div>
                <style>
                    #progress_rc:after {
                        width: <?php echo $rcProgressOutput.'%'; ?>;
                    }
                </style>
            <?php
            }
            // Steal
            if(!!$dagensUtfordring['steal']){
                $stealPercentage = round((($utford_user['DAUTUS_stjel'] ?? 0) / $dagensUtfordring['steal']) * 100, 2);
                $stealProgressOutput = $stealPercentage > 100 ? 100 : $stealPercentage;
                ?>
                <div class="rankbar_dagens_utfordring">
                    <h4>Vellykkede stjel fra andre brukere</h4>
                    <p class="description">Framgang: <?php
                    echo ($utford_user['DAUTUS_stjel'] ?? 0) . ' av ' . $dagensUtfordring['steal'] . ' ('.$stealProgressOutput.'%)';
                    ?></p>
                    <div id="progress_steal"></div>
                </div>
                <style>
                    #progress_steal:after {
                        width: <?php echo $stealProgressOutput.'%'; ?>;
                    }
                </style>
            <?php
            }
        ?>

        </div>

        <?php

        if($dagensUtfordringIsDone && $utford_user['DAUTUS_level'] <= 3){
            ?>
                <form method="post" style="margin-top: 10px;">
                    <input type="submit" name="get_payment" value="Hent belønning">
                </form>
            <?php
        }

        } else {
            echo feedback('Dagens utfordring blir generert. Kom tilbake senere.', 'blue');
        }

        ?>
        

    </div>
    <div style="clear: both;"></div>