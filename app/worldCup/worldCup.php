<?php

$min_bet = 10000;

if(isset($_GET['noTeam'])){
	echo feedback('Du må velge hjemeseier, borteseier eller uavgjort!', 'error');
}

if(isset($_GET['success'])){
	echo feedback('Innsatsen er satt, lykke til!', 'success');
}

if(isset($_GET['regret'])){
	echo feedback('Du har angret en innsats og får derfor tilbake pengene', 'success');
}

if(isset($_GET['started'])){
	echo feedback('Du kan ikke angre en startet kamp!', 'error');
}
?>

<div class="col-8 single">
    <div class="content">
			<h4>Fotball-VM i Qatar 2022 ⚽</h4>
			<p class="description"> 
				Fra 3. Desember og frem til finalen 18. Desember har du mulighet for å satse penger på hvem som
				vinner kampene fra Åttendedelsfinale og frem til Finalen 18. Desember.
			</p>
			<p>Regler:</p>
			<ul class="description">
				<li>➖ Stillingen til 90 min (+ tillegsminutter) er den som er kåret som vinneren av kampen</li>
				<li>➖ Man kan ikke kombinere kampene, men kun satse på 1 og 1 kamp</li>
				<li>➖ Når kampen har startet er det ikke mulig å angre eller satse på kampen, det er derfor viktig å legge innsats før kampen starter</li>
				<li>➖ Minste innsats er <?= number($min_bet) ?> kr</li>
				<li>➖ Tallene i gul indikerer oddsen. Venstre = hjemmelagets odds, midten = uavgjort odds, høyre = bortelagets odds</li>
			</ul>

			<br><br>
			<h4>Tilgjengelige kamper</h4>
			<?php 
			
			$stmt = $pdo->prepare('SELECT * FROM world_cup_match WHERE WCM_start_date >= '.time() - 12000);
			$stmt->execute(array());
	
			foreach ($stmt as $row) {

				$hasBetted = false;
				$match_started = false;

				$teamStr[0] = 'hjemmeseier';
				$teamStr[1] = 'uavgjort';
				$teamStr[2] = 'borteseier';

				$query = $pdo->prepare("SELECT * FROM world_cup_bet WHERE WC_acc_id = ? AND WC_match_id = ?");
				$query->execute(array($_SESSION['ID'], $row['WCM_id']));
				$active_bet = $query->fetch(PDO::FETCH_ASSOC);

				$outcomeStr = '';

				if($row['WCM_start_date'] <= time()){
					$match_started = true;
				}

				if($active_bet){
					$hasBetted = true;
					$outcomeStr = $outcomeStr.'Du har satset '.number($active_bet['WC_bet']).' kr på '.$teamStr[$active_bet['WC_team']];
				}

				if(isset($_POST[$row['WCM_id']])){
					$bet = remove_space($_POST['betAmount']);
					$team = $_POST['betTeam'];
					$odds = 0;

					if(!is_numeric($team)){
						header("Location: ?side=worldCup&noTeam");
					}

					if($team == 0){
						$odds = $row['WCM_home_odds'];
					} elseif($team == 1){
						$odds = $row['WCM_away_odds'];
					} elseif($team == 2){
						$odds = $row['WCM_draw_odds'];
					}

					if(is_numeric($team) && is_numeric($bet) && $bet >= $min_bet && $bet <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo)){
						$sql = "INSERT INTO world_cup_bet (WC_acc_id, WC_team, WC_bet, WC_odds, WC_date, WC_match_id) VALUES (?,?,?,?,?,?)";
						$stmt = $pdo->prepare($sql);
						$stmt->execute([$_SESSION['ID'], $team, $bet, $odds, time(), $row['WCM_id']]);

						take_money($_SESSION['ID'], $bet, $pdo);

						header("Location: ?side=worldCup&success");
					} else {
						echo feedback('Ugyldig innsats!', 'error');
					}
				}

				if(isset($_POST[$row['WCM_id'].'_regret'])){
					$string = $row['WCM_id'].'_regret';
					$new_string = explode("_", $string);

					$id = $new_string[0];

					$query = $pdo->prepare("SELECT * FROM world_cup_bet WHERE WC_acc_id = ? AND WC_match_id = ?");
					$query->execute(array($_SESSION['ID'], $id));
					$active_bet = $query->fetch(PDO::FETCH_ASSOC);

					if($active_bet){
						$sql = "DELETE FROM world_cup_bet WHERE WC_acc_id = " . $_SESSION['ID'] . " AND WC_match_id = ".$id."";
            $pdo->exec($sql);

						give_money($_SESSION['ID'], $active_bet['WC_bet'], $pdo);

						header("Location: ?side=worldCup&regret");
					} else {
						echo feedback('Du har ingen innsats på denne kampen', 'error');
					}
				}

				if(isset($_POST[$row['WCM_id'].'_home'])){
					$string = $row['WCM_id'].'_regret';
					$new_string = explode("_", $string);

					$id = $new_string[0];

					$stmt_ = $pdo->prepare('SELECT * FROM world_cup_bet WHERE WC_match_id = '.$id.' AND WC_team = 0');
					$stmt_->execute();

					$query_match = $pdo->prepare("SELECT * FROM world_cup_match WHERE WCM_id=?");
					$query_match->execute(array($id));
					$match_row = $query_match->fetch(PDO::FETCH_ASSOC);
			
					foreach ($stmt_ as $row_) {
						$money_outcome = ($row_['WC_bet']*$match_row['WCM_home_odds'])/100;

						send_notification($row_['WC_acc_id'], $match_row['WCM_home'].' vant kampen og du vant '.number($money_outcome).' kr!', $pdo);
						give_money($_SESSION['ID'], $money_outcome, $pdo);
					}
				}

				if(isset($_POST[$row['WCM_id'].'_draw'])){
					$string = $row['WCM_id'].'_regret';
					$new_string = explode("_", $string);

					$id = $new_string[0];

					$stmt_ = $pdo->prepare('SELECT * FROM world_cup_bet WHERE WC_match_id = '.$id.' AND WC_team = 1');
					$stmt_->execute();

					$query_match = $pdo->prepare("SELECT * FROM world_cup_match WHERE WCM_id=?");
					$query_match->execute(array($id));
					$match_row = $query_match->fetch(PDO::FETCH_ASSOC);
			
					foreach ($stmt_ as $row_) {
						$money_outcome = ($row_['WC_bet']*$match_row['WCM_draw_odds'])/100;

						send_notification($row_['WC_acc_id'], $match_row['WCM_draw'].' vant kampen og du vant '.number($money_outcome).' kr!', $pdo);
						give_money($_SESSION['ID'], $money_outcome, $pdo);
					}
				}

				if(isset($_POST[$row['WCM_id'].'_away'])){
					if($match_started){
						header("Location: ?side=worldCup&started");
					}

					$string = $row['WCM_id'].'_regret';
					$new_string = explode("_", $string);

					$id = $new_string[0];

					$stmt_ = $pdo->prepare('SELECT * FROM world_cup_bet WHERE WC_match_id = '.$id.' AND WC_team = 2');
					$stmt_->execute();

					$query_match = $pdo->prepare("SELECT * FROM world_cup_match WHERE WCM_id=?");
					$query_match->execute(array($id));
					$match_row = $query_match->fetch(PDO::FETCH_ASSOC);
			
					foreach ($stmt_ as $row_) {
						$money_outcome = ($row_['WC_bet']*$match_row['WCM_awa_odds'])/100;

						send_notification($row_['WC_acc_id'], $match_row['WCM_awa'].' vant kampen og du vant '.number($money_outcome).' kr!', $pdo);
						give_money($_SESSION['ID'], $money_outcome, $pdo);
					}
				}
			
			?>
			<div class="col-12 nice-boxshadow" style="margin-top: 10px;">
				<div class="content">
					<form method="post">
					<div style="display: flex; justify-content: center; margin-bottom: 10px;">
						<span style="margin-left: 10px;"><?php
						if($match_started){ ?>
								<span class="blink_me">LIVE</span
					<?php } else {
						echo date_to_text($row['WCM_start_date']); 
					}
						 ?></span>
					</div>

					<div style="display: flex; justify-content: space-between;">
						<span>
							<img class="team_img" src="img/vm/<?= strtolower($row['WCM_home']) ?>.svg" />
							<span class="description"><?= $row['WCM_home'] ?></span>
							<br><br>
							<span><span style="font-weight: bold; color: yellow;">
							<?php if(!$hasBetted && !$match_started){ ?><input type="radio" name="betTeam" value="0"> <?php } ?>
							<?= $row['WCM_home_odds']/100 ?>
						</span></span> 
						</span> 
						<span>VS.
							<br>
							<span style="opacity: 0">Uavgjort</span>
							<br><br>
							<span><span style="font-weight: bold; color: yellow;">
							<?php if(!$hasBetted && !$match_started){ ?> <input type="radio" name="betTeam" value="1"> <?php } ?>
							<?= $row['WCM_draw_odds']/100 ?>
						</span></span>  
						</span> 
						<span>
							<img class="team_img" src="img/vm/<?= strtolower($row['WCM_away']) ?>.svg" />
							<span class="description"><?= $row['WCM_away'] ?></span>
							<br><br>
							<span><span style="font-weight: bold; color: yellow;">
							<?php if(!$hasBetted && !$match_started){ ?> <input type="radio" name="betTeam" value="2"> <?php } ?>
							<?= $row['WCM_away_odds']/100 ?>
						</span></span> 
						</span>
					</div>
					
					<div style="display: flex; justify-content: center; margin-top: 15px;">
					<?php if(!$hasBetted && !$match_started){ ?>
						<div>
							<input type="text" name="betAmount" id="number" placeholder="Innsats">
							<input type="submit" name=<?= $row['WCM_id'] ?> value="Spill">
						</div>
						<?php 
						} else {
							if($row['WCM_start_date'] <= time()){
								?>
								<div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
								<?= $outcomeStr ? $outcomeStr : '' ?>
							</div>
								<?php 
							} elseif($hasBetted) {
							?>
							<div style="display: flex; flex-direction: column">
							<p><?= $outcomeStr ?></p>
							<?php if(!$match_started) { ?>
							<input type="submit" name="<?= $row['WCM_id'] ?>_regret" value="Angre innsats og få tilbake innsatsen"/>
							<?php } ?>
						</div>
						<?php 
							}
						} 
						?>
					</div>
					</div>
					<?php 
						if($_SESSION['ID'] == 1){
							?>
							<div style="display: flex; justify-content: center">
								<div style="display: flex; justify-content: center; flex-direction: column">
								<div>
									<h4>Utfall</h4>
								</div>
								<div>
									<input type="submit" name="<?= $row['WCM_id'] ?>_home" value="Hjemmeseier"/>
									<input type="submit" name="<?= $row['WCM_id'] ?>_draw" value="uavgjort"/>
									<input type="submit" name="<?= $row['WCM_id'] ?>_away" value="borteseier"/>
								</div>
								</div>
							</div>
							<?php
						}
					?>
				</form>
			</div>
			<?php } ?>
			<div style="clear: both;"></div>
    </div>
</div>
<script>
number_space("#number");
</script>
<style>
.nice-boxshadow{
	border-radius: 7px;
	-moz-box-shadow: 0px 1px 5px 0px #101010;
	-webkit-box-shadow: 0px 1px 5px 0px #101010;
	box-shadow: 0px 1px 5px 0px #101010;
}

.team_img {
	max-height: 20px;
	margin-right: 5px;
}

.blink_me {
	color: red;
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}

</style>