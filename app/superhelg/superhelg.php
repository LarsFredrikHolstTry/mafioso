<?php


$stmt = $pdo->prepare("SELECT SHELG_start, SHELG_end FROM super_helg WHERE SHELG_start <= ? AND SHELG_end >= ?");
$stmt->execute([time(), time()]);
$row_happy_hour = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<div class="col-8 single">
    <div class="content">
        <h3 class="tac">Happy hour</h3>
        <p class="tac description">Happy hour varer fra 
            <?php echo time_to_text($row_happy_hour['SHELG_start']) . " " . date_to_text_long($row_happy_hour['SHELG_start']); ?>
            til 
            <?php echo time_to_text($row_happy_hour['SHELG_end']) . " " . date_to_text_long($row_happy_hour['SHELG_end']); ?> 
            <br>
            Happy hour gir dobbel EXP og dobbel verdi på biler og ting
        </p>
    </div>
</div>