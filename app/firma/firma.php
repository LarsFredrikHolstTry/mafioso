<?php

$firms = array('7-Eleven', 'Levis butikk', 'Restaurant', 'Aksjemeglerhus', 'Strømselskap', 'Skipsmegler i Maritim foretning');
$price = array(25000000, 75000000, 150000000, 500000000, 1500000000, 5000000000);
$maxAmount = array(5, 4, 3, 2, 1, 1);

?>

<div class="col-6 single">
    <div class="content">
        <h4>Kjøp firma</h4>
        <?php

        $i = 0;
        foreach ($firms as &$value) {
            echo $value;

            echo 'Pris: ' . number($price[$i]) . 'kr';
            echo 'Du har: 1 av ' . $maxAmount[$i];

            $i++;
            echo '<br>';
        }

        ?>
    </div>
</div>