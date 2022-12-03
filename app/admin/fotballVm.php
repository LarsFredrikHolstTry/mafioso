<?php

if (!isset($_GET['side'])) {
    die();
}

if ($_SESSION['ID'] == 1) {

    if (isset($_POST['publish'])) {
			$start = strtotime($_POST['startTime']);

			$sql = "INSERT INTO world_cup_match (WCM_home, WCM_away, WCM_home_odds, WCM_away_odds, WCM_draw_odds, WCM_start_date) VALUES (?,?,?,?,?,?)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([$_POST['home_team'], $_POST['away_team'], $_POST['home_team_odds'], $_POST['away_team_odds'], $_POST['draw_team_odds'], $start]);

			echo feedback('Kampen mellom '.$_POST['home_team'].' og '.$_POST['away_team'].' er registrert', 'success');
    }

?>
    <div class="content">
        <h3 style="margin-bottom: 10px;">Session</h3>
        <form method="post">
					<span> Oddsen ganges på 100 (eks: odds på 1,4 er 140 i odds)</span>
					<br><br>
					<input type="text" name="home_team" placeholder="Hjemmelag" required>
					<input type="text" name="home_team_odds" placeholder="Hjemmelag odds" required>
					<br>
					<input type="text" name="away_team" placeholder="Bortelag" required>
					<input type="text" name="away_team_odds" placeholder="Bortelag odds" required> 
					<br>
					<input type="text" name="draw_team_odds" placeholder="Uavgjort odds" required> 
					<br>
					<input type="datetime-local" id="startTime" name="startTime" required>
					<br>
					<input type="submit" name="publish" value="Lagre kamp">
        </form>
    </div>
<?php } else {
    echo feedback("Ingen tilgang!", "error");
} ?>