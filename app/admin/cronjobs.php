<?php

if (!isset($_GET['side'])) {
    die();
}

if ($_SESSION['ID'] != 1) {
    echo feedback("Ingen tilgang!", "error");
} else {

?>
    <div class="content">
        <h3 style="margin-bottom: 10px;">Cron jobs</h3>
        <?php echo feedback("Disse skal ALDRI røres med mindre man har fått tillatelse fra Skitzo!", "error"); ?>
        <table>
            <tr>
                <th style="width: 50%;">Cron job</th>
                <th>Info</th>
                <th>Intervall</th>
            </tr>
            <tr>
                <td><a target="_blank" href="cron_jobs/7DSA7dydsaHDNndmsad/run_me_every_hour.php">cron_jobs/7DSA7dydsaHDNndmsad/run_me_every_hour.php</a></td>
                <td>Firma, byskatt</td>
                <td>Hver time</td>
            </tr>
            <tr>
                <td><a target="_blank" href="cron_jobs/7DSA7dydsaHDNndmsad/lotto.php">cron_jobs/7DSA7dydsaHDNndmsad/lotto.php</a></td>
                <td>Lotto</td>
                <td>Hver fjerde time</td>
            </tr>
            <tr>
                <td><a target="_blank" href="cron_jobs/7DSA7dydsaHDNndmsad/run_me_every_10th_minute.php">cron_jobs/7DSA7dydsaHDNndmsad/run_me_every_10th_minute.php</a></td>
                <td>Aksjemarked</td>
                <td>Hvert 10. minutt</td>
            </tr>
            <tr>
                <td><a target="_blank" href="cron_jobs/7DSA7dydsaHDNndmsad/run_me_every_minute.php">cron_jobs/7DSA7dydsaHDNndmsad/run_me_every_minute.php</a></td>
                <td>Detektiv</td>
                <td>Hvert minutt</td>
            </tr>
            <tr>
                <td><a target="_blank" href="cron_jobs/7DSA7dydsaHDNndmsad/run_me_at_midnight.php">cron_jobs/7DSA7dydsaHDNndmsad/run_me_at_midnight.php</a></td>
                <td>****</td>
                <td>Hvert døgn</td>
            </tr>
            <tr>
                <td><a target="_blank" href="cron_jobs/7DSA7dydsaHDNndmsad/run_me_before_midnight.php">cron_jobs/7DSA7dydsaHDNndmsad/run_me_before_midnight.php</a></td>
                <td>Familie</td>
                <td>Hver time</td>
            </tr>
        </table>
    </div>
<?php } ?>