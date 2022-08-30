<?php

function get_konk_title($pdo){
    $date = time();
    
    $query = $pdo -> prepare("SELECT KONK_title FROM konk WHERE KONK_from <= ? AND KONK_to >= ?");
    $query -> execute(array($date, $date));
    $row = $query -> fetch(PDO::FETCH_ASSOC);

    return $row['KONK_title'] ?? NULL;
}

function get_konk_disc($pdo){
    $date = time();
    
    $query = $pdo -> prepare("SELECT KONK_description FROM konk WHERE KONK_from <= ? AND KONK_to >= ?");
    $query -> execute(array($date, $date));
    $row = $query -> fetch(PDO::FETCH_ASSOC);

    return $row['KONK_description'] ?? NULL;
}

function get_konk_prize($place, $pdo){
    $date = time();

    $query = $pdo -> prepare("SELECT KONK_prize FROM konk WHERE KONK_from <= ? AND KONK_to >= ?");
    $query -> execute(array($date, $date));
    $row = $query -> fetch(PDO::FETCH_ASSOC);

    $arr = json_decode($row['KONK_prize'], true);
    
    return $arr[$place - 1];
}

?>

<div class="col-8 single">
    <div class="content">
        <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">

        <?php 
        if(!active_konk($pdo)){ 
            echo '<p class="description">Det er ingen aktive konkurraser nå. Sjekk listen under for planlagte konkurranser!'; 
        } else { ?>
            <span style="font-size: var(--font-small); color: var(--button-bg-color);">AKTIV KONKURRANSE</span>
            <h4><?php echo get_konk_title($pdo); ?></h4>
            <p class="description"><?php echo get_konk_disc($pdo); ?></p>
            <h4>Premier</h4>
            <ul class="description" style="margin-bottom: 30px;">
                <?php 
                    for($i = 1; $i <= 4; $i++){
                        echo '<li>'.$i.'. plass - '.number(get_konk_prize($i, $pdo)).' kr</li>';
                    }
                ?>

            </ul>
        
            <h3 style="margin: 30px 0px 10px 0px;">Plassering</h3>
            <table>
                <tr>
                    <th>Plassering</th>
                    <th>Bruker</th>
                    <th>Poeng</th>
                </tr>
                <?php 

                    $position = 0;

                    $sql = "SELECT KU_acc_id, KU_count FROM konk_user ORDER BY KU_count DESC";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $position++;

                ?>
                <tr>
                    <td><?php echo $position; echo '. plass'; ?></td>
                    <td><?php echo ACC_username($row['KU_acc_id'], $pdo); ?></td>
                    <td><?php echo number($row['KU_count']); ?></td>
                </tr>
                <?php } ?>
            </table>
        <?php } ?>

        <h3 style="margin: 30px 0px 10px 0px;">Fremtidige konkurranser</h3>
        <a class="description" href="?side=konk_gamle">Sjekk gamle konkurranser her</a>
        <table style="margin-top: 10px;">
            <tr>
                <th>Start</th>
                <th>Slutt</th>
                <th>Tittel</th>
            </tr>
            <?php 

            $sql = "SELECT * FROM konk WHERE KONK_to >= ".time()." ORDER BY KONK_from";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            ?>
            <tr>
                <td><?php if($row['KONK_from'] <= time() && $row['KONK_to'] >= time()) { echo '<span style="color: var(--button-bg-color);">Aktiv </span>'; } ?>
                    <?php echo date_to_text($row['KONK_from']); ?></td>
                <td><?php echo date_to_text($row['KONK_to']); ?></td>
                <td><?php echo $row['KONK_title']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
