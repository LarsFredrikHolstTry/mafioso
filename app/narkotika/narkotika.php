<?php

$max = 150;

$price[0] = 450;
$price[1] = 450;
$price[2] = 450;

$drug[0] = "MDMA";
$drug[1] = "LSD";
$drug[2] = "amfetamin";

?>

<div class="col-9 single">
    <div class="content">
    <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png"> 
    <form method="post">
        <p class="description">Når du kjøper narkotika har du mulighet for å selge dette i andre land. Narkotika kan kun kjøpes i Berlin og blir solgt via flyplass-siden</p>
        <?php for($i = 0; $i < count($drug); $i++){ ?>
            <div class="col-4">
                <h4>Kjøp <?php echo $drug[$i]; ?></h4>
                <p class="description">Du har: 0<br>Pris: <?php echo number($price[$i]); ?>kr</p>
                <input style="width: 50%;" type="text" id="drug_input_<?php echo $i; ?>"  placeholder="Antall <?php echo $drug[$i]; ?>">
                <input type="button" id="max_drugs_<?php echo $i; ?>" value="Max">
                <input type="submit" value="Kjøp">
            </div>
            <script>
            
            $('#max_drugs_<?php echo $i; ?>').click(function() {
                $('#drug_input_<?php echo $i; ?>').val(<?php echo $max; ?>);
            });

            </script>
        <?php } ?>
        <div style="clear: both;"></div>
        </form>
    </div>
</div>

