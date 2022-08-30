<?php

$horse_name[0] = "Secretariat";
$horse_name[1] = "Seattle Slew";
$horse_name[2] = "Man o' War";
$horse_name[3] = "Citation";
$horse_name[4] = "Red Rum";
$horse_name[5] = "Seabiscuit";
$horse_name[6] = "Kelso";
$horse_name[7] = "Native Dancer";

$chance[0] = 5;
$chance[1] = 10;
$chance[2] = 15;
$chance[3] = 20;
$chance[4] = 25;
$chance[5] = 35;
$chance[6] = 40;
$chance[7] = 50;

$payout[0] = 10;
$payout[1] = 9;
$payout[2] = 8;
$payout[3] = 7;
$payout[4] = 6;
$payout[5] = 5;
$payout[6] = 3;
$payout[7] = 1;

$winner = mt_rand(0, 100);

if($winner <= $chance[0]){
    $winning_horse = 7;
} elseif($winner <= $chance[1]){
    $winning_horse = 6;
} elseif($winner <= $chance[2]){
    $winning_horse = 5;
} elseif($winner <= $chance[3]){
    $winning_horse = 4;
} elseif($winner <= $chance[4]){
    $winning_horse = 3;
} elseif($winner <= $chance[5]){
    $winning_horse = 2;
} elseif($winner <= $chance[6]){
    $winning_horse = 1;
} else {
    $winning_horse = 0;
}

echo $winning_horse;

?>

<div class="col-8 single">
    <div class="content">
        <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
        <div class="col-8 single">
            <table>
                <tr>
                    <th>Hest</th>
                    <th>Sjanse</th>
                    <th>Utbetaling</th>
                </tr>
                <?php for($i = 0; $i < count($horse_name); $i++){ ?>
                <tr>
                    <td>
                        <label class="label_container"><?php echo $horse_name[$i]; ?>
                            <input type="radio" name="hestelop" value="<?php echo $i; ?>">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td>
                        <?php echo $chance[$i]; ?>%
                    </td>
                    <td>
                        x<?php echo $payout[$i]; ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
        
    </div>
</div>