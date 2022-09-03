<?php

ob_start();
include '../../env.php';
include_once '../../functions/functions.php';
include_once '../../functions/feedbacks.php';
include_once '../../functions/dates.php';

$sql = "DELETE FROM fengsel WHERE FENG_countdown < " . time() . "";
$pdo->exec($sql);

?>
<center>
	<p>Andre spillere i fengsel:</p>
</center>
<table id="myTable">
	<tr>
		<th>Spiller</th>
		<th>Begrunnelse</th>
		<th style="text-align: center;">Tid igjen</th>
	</tr>

	<?php
	$query = $pdo->prepare('SELECT * FROM fengsel');
	$query->execute();
	$i = 0;
	foreach ($query as $row) {
	?>

		<tr>
			<td><?php echo ACC_username($row['FENG_acc_id'], $pdo); ?></td>
			<td><?php echo $row['FENG_reason']; ?></td>
			<td style="text-align: center;">
				<?php

				$time_left = $row['FENG_countdown'] - time();

				$minutes = floor($time_left / 60);
				$seconds = $time_left - $minutes * 60;

				echo '<t id="countdowntimer_' . $i . '">' . $minutes . ' minutter og ' . $seconds . ' sekunder'; ?>
			</td>
		</tr>

	<?php $i++;
	} ?>
	<?php if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 43 && AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) == 0) { ?>
		<tr>
			<td>Kamero Camilla</td>
			<td style="text-align: center;">Drapsforsøk</td>
			<td style="text-align: center;">9 år</td>
		</tr>
	<?php } ?>
</table>

<style>
	.blob {
		display: inline-block;
		background: black;
		border-radius: 50%;
		box-shadow: 0 0 0 0 rgba(0, 0, 0, 1);
		margin-left: 7px;
		height: 8px;
		width: 8px;
		transform: scale(1);
		animation: pulse-black 2s infinite;
	}

	.blob.red {
		background: rgba(255, 25, 25, 1);
		box-shadow: 0 0 0 0 rgba(255, 25, 25, 1);
		animation: pulse-red 2s infinite;
	}

	@keyframes pulse-red {
		0% {
			transform: scale(0.95);
			box-shadow: 0 0 0 0 rgba(255, 25, 25, 0.7);
		}

		70% {
			transform: scale(1);
			box-shadow: 0 0 0 10px rgba(255, 25, 25, 0);
		}

		100% {
			transform: scale(0.95);
			box-shadow: 0 0 0 0 rgba(255, 25, 25, 0);
		}
	}
</style>