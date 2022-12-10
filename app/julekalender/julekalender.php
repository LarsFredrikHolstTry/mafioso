<?php

$isDecember = date('m') == 12;
$day = date('j');
$criteria = 20;

function getPrizeFromGivenDay($id, $day, $pdo){
	$query = $pdo->prepare("SELECT * FROM christmas WHERE CHR_acc_id = ? AND CHR_day = ?");
	$query->execute(array($id, $day));
	$CHR_row = $query->fetch(PDO::FETCH_ASSOC);

	return $CHR_row['CHR_outcome'] ?? null;
}

$query = $pdo->prepare("SELECT * FROM christmas WHERE CHR_acc_id = ?");
$query->execute(array($_SESSION['ID']));
$CHR_row_all = $query->fetch(PDO::FETCH_ASSOC);

$query = $pdo->prepare("SELECT * FROM christmas WHERE CHR_acc_id = ? AND CHR_day = ?");
$query->execute(array($_SESSION['ID'], $day));
$CHR_today = $query->fetch(PDO::FETCH_ASSOC);

$can_open_today = ($CHR_today['CHR_counter'] ?? 0) >= $criteria;

$remaining = $criteria - ($CHR_today['CHR_counter'] ?? 0);
$remaining_str = $remaining < 0 ? 0 : $remaining;

if(isset($_GET['open'])){
	$open = $_GET['open'];
	
	if($open <= 0 || $open >= 25){
		echo feedback('Ugyldig dato!', 'error');
	} elseif(!$can_open_today){
		echo feedback('Du må utføre 20 kriminaliteter for å åpne dagens luke!', 'error');
	} elseif(getPrizeFromGivenDay($_SESSION['ID'], $day, $pdo) != null){
		echo feedback('Du har allerede åpnet dagens luke! Kom tilbake i morgen for mer snacks!', 'error');
	} else {
		$outcomeArr = [];
		$outcomeType = [];

		$x = 0;

		while($x <= 5) {
			$chance = mt_rand(0,100);
			if(!in_array('happy_hour', $outcomeType) && $chance > 0 && $chance <= 5){
				array_push($outcomeType, 'happy_hour');
				$happy_hour = mt_rand(34, 38);

				$outcome_str = thing($happy_hour);
				update_things($_SESSION['ID'], $happy_hour, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('diamant', $outcomeType) && $chance > 5 && $chance <= 10){
				array_push($outcomeType, 'diamant');

				$outcome_str = "diamant";
				update_things($_SESSION['ID'], 39, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('money', $outcomeType) && $chance > 10 && $chance <= 20){
				array_push($outcomeType, 'money');
				$amount = mt_rand(10000000,500000000);

				$outcome_str = number($amount) . " kr";
				give_money($_SESSION['ID'], $amount, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('points', $outcomeType) && $chance > 20 && $chance <= 30){
				array_push($outcomeType, 'points');
				$amount = mt_rand(5 , 55);

				$outcome_str = "$amount poeng";
				give_poeng($_SESSION['ID'], $amount, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('rare_car', $outcomeType) && $chance > 30 && $chance <= 35){
				array_push($outcomeType, 'rare_car');
				$car_id = mt_rand(19,21);
				$city = mt_rand(0,4);

				$outcome_str = car($car_id) .' i '. city_name($city);
				update_garage($_SESSION['ID'], $car_id, $city, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('bullets', $outcomeType) && $chance > 35 && $chance <= 40){
				array_push($outcomeType, 'bullets');
				$amount = mt_rand(5,100);

				$outcome_str = $amount ." kuler";
				give_bullets($_SESSION['ID'], $amount, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('exp', $outcomeType) && $chance > 40 && $chance <= 50){
				array_push($outcomeType, 'exp');
				$amount = mt_rand(10, 1000);

				$outcome_str = number($amount) . " exp";
				give_exp($_SESSION['ID'], $amount, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('energidrikk', $outcomeType) && $chance > 50 && $chance <= 60){
				array_push($outcomeType, 'energidrikk');

				$outcome_str = 'energidrikk';
				update_things($_SESSION['ID'], 29, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('hemmelig kiste', $outcomeType) && $chance > 60 && $chance <= 70){
				array_push($outcomeType, 'hemmelig kiste');
				
				$outcome_str = 'hemmelig kiste';
				update_things($_SESSION['ID'], 30, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('byskatt', $outcomeType) && $chance > 70 && $chance <= 80){
				array_push($outcomeType, 'byskatt');

				$outcome_str = 'byskatt til 0%';
				$sql = "UPDATE city_tax SET CTAX_tax = 0 WHERE CTAX_city = ?";
				$stmt = $pdo->prepare($sql);
				$stmt->execute([AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('garasje', $outcomeType) && $chance > 80 && $chance <= 90){
				array_push($outcomeType, 'garasje');
				$outcome = mt_rand(10,	50);

				$outcome_str = "$outcome plasser i garasjen";
				upgrade_garage($_SESSION['ID'], $outcome, $pdo);
				array_push($outcomeArr, $outcome_str);
			} elseif(!in_array('lager', $outcomeType) && $chance > 90 && $chance <= 100){
				array_push($outcomeType, 'lager');
				$outcome = mt_rand(10,	50);

				$outcome_str = "$outcome plasser i lageret";
				upgrade_thing($_SESSION['ID'], $outcome, $pdo);
				array_push($outcomeArr, $outcome_str);
			}

			$x++;
		}
		$outcome_string = '';

		for($i = 0; $i < count($outcomeArr); $i++){
			$isLast = $i == count($outcomeArr);
			$isFirst = $i == 0;
			$isSecondLast = $i == count($outcomeArr) - 1;
			
			if(!$isFirst && !$isLast){
				$outcome_string = $outcome_string.', ';
			}

			if($isSecondLast){
				$outcome_string = $outcome_string.' og ';
			}

			$outcome_string = $outcome_string . $outcomeArr[$i];

		}

		echo feedback('Du åpnet dagens luke og fant '. $outcome_string, 'success');

		$sql = "UPDATE christmas SET CHR_outcome = ? WHERE CHR_acc_id = ? AND CHR_day = ? ";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$outcome_string, $_SESSION['ID'], date('j')]);
	}
}


?>
<div class="col-9 single">
    <div class="content">
		<img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
					<p class="description" style="text-align: center;">Tradisjonen tro er julekalenderen tilbake på Mafioso! Hver dag i 24 dager har du mulighet 
						for å åpne en luke i julekalenderen for å finne masse snacks!
					</p>
			<?php

				for($i = 1; $i <= 24; $i++){
					?>
						<div class="col-3" >
								<div class="gift_div" style="border-color: 
						<?php if($day == $i){ 
							echo '#087fef'; 
							} elseif($day < $i){ 
								echo 'grey'; 
								} elseif($day > $i) { 
									echo 'grey';
								}?>;">

									<h4><?= $i ?></h4>
									<?php 
									if($day == $i){ 
										if(!$can_open_today){
											echo '<span>Du mangler '.$remaining_str.' krim for å åpne dagens luke!</span>';
										} else {
											$outcome = getPrizeFromGivenDay($_SESSION['ID'], $i, $pdo);
										if($outcome){
											echo '<span>'.$outcome, '</span>';
											} else { 
												?>
												<a href="?side=julekalender&open=<?= $i ?>" class="a_as_button">Åpne luken</a>
												<?php
												} 
											}
										} elseif($day < $i){ 
											?>
											<span class="description">Denne kan ikke åpnes før <?= $i ?>. Desember</span>
											<?php } elseif($day > $i) {
												$outcome = getPrizeFromGivenDay($_SESSION['ID'], $i, $pdo);
												
												echo $outcome ? $outcome : '<span class="description">Ingen premie.<br>Dette kan bety at du ikke åpnet denne luken.</span>';
											} ?>
						</div>
					</div>
					<?php
				}

			?>
			<div style="clear: both;"></div>
    </div>
</div>

<style>
	.gift_div {
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		text-align: center;
		padding: 20px; 
		border: 1px; 
		border-style: dotted;
		height: 150px;
	}

</style>