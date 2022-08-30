<?php

/* TODO: Just temperary, delete this and js function when event is started */
$start = $diamondStart - time();

?>

<div class="col-7 single">
    <div class="content">
        <img class="action_image" src="img/action/actions/diamantheistet.png">
        <?php if (!$activeDiamondEvent) { ?>
            <p style="margin-top: 2rem;" class="description tac">Starter om <span id="countdown"><?php echo seconds2human($start); ?></span></p>
        <?php } else { ?>
            <div class="col-6">
                <h4>Velkommen til diamantheistet!</h4>
                <p class="description">
                    Jeg er sjeiken av Dubai og jeg
                    trenger din hjelp. Min frue og
                    meg skal gifte oss 25. Juni og
                    det er mangel på diamanter.
                    <br>
                    <br>
                    Det jeg trenger din hjelp til er å skaffe meg
                    diamanter før bryllupet. Du har frem til 24. Juni klokken 18:00 å skaffe meg flest mulig diamanter.
                    Hvordan du skaffer diamentene er opp til deg.
                    <br>
                    <br>
                    Siden budsjettet er stramt kan jeg kun belønne de 3 beste mafiosoene.
                    Gå ut og stjel diamanter for meg!
                    <br>
                    <br>
                    <span class="small_header">Belønning</span>
                    <br>
                    Jeg heter Sergej og er assistenten til Sjeiken, det er jeg som vil
                    hjelpe deg i å skaffe flest mulig diamanter. Som sjeiken nevnte tidligere
                    er det kun de 3 beste mafiosoene som vil få belønning.
                    Du har mulighet til å skaffe diamanter på hvilken som helst måte du vil,
                    men den absolutt beste måten å skaffe diamanter på er ved å gå i gruvene
                    i Abu Dhabi. Du kan reise dit via flyplassen.
                    <br><br>
                    For å gi diamantene til sjeiken må du selge de via lageret ditt for 100 000kr.
                    De 3 spillerne som har solgt flest diamanter til sjeiken før 25. Juni vil få en ekstra belønnelse.
                    <br><br>
                    Det er totalt funnet <span style="color:var(--button-bg-color)">
                        <?php
                        $stmt = $pdo->prepare("SELECT SUM(DE_diamonds) FROM diamond_event");
                        $stmt->execute();
                        $count = $stmt->fetchColumn();

                        $stmt_lager = $pdo->prepare("SELECT count(TH_type) FROM things WHERE TH_type = 39");
                        $stmt_lager->execute();
                        $count_lager = $stmt_lager->fetchColumn();

                        echo number($count + $count_lager);

                        ?>
                    </span> diamanter i Abu Dhabi!
                </p>
            </div>
            <div class="col-6">
                <table>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th>Spiller</th>
                        <th style="width: 45%;">Antall diamanter</th>
                    </tr>
                    <?php
                    $i = 0;

                    $query = $pdo->prepare('SELECT * FROM diamond_event ORDER BY DE_diamonds DESC');
                    $query->execute();
                    foreach ($query as $row) {

                        $i++;

                    ?>
                        <tr>
                            <td><?php echo $i ?>.</td>
                            <td><?php echo ACC_username($row['DE_acc_id'], $pdo); ?></td>
                            <td><?php echo number($row['DE_diamonds']); ?> stk</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <div style="clear:both;"></div>
        <?php } ?>
    </div>
</div>
<?php if (!$activeDiamondEvent) { ?>
    <script>
        var secondsEnd = <?php echo $start ?>;

        function diamantTimerEnd() {

            var days = Math.floor(secondsEnd / 24 / 60 / 60);
            var hoursLeft = Math.floor((secondsEnd) - (days * 86400));
            var hours = Math.floor(hoursLeft / 3600);
            var minutesLeft = Math.floor((hoursLeft) - (hours * 3600));
            var minutes = Math.floor(minutesLeft / 60);
            var remainingSeconds = secondsEnd % 60;

            function pad(n) {
                return (n < 10 ? "" + n : n);
            }
            document.getElementById('countdown').innerHTML = pad(days) + "d " + pad(hours) + "t " + pad(minutes) + "m " + pad(remainingSeconds) + "s";
            if (secondsEnd == 0) {
                clearInterval(countdownTimer);
                document.getElementById('countdown').innerHTML = "Completed";
            } else {
                secondsEnd--;
            }
        }
        var countdownTimer = setInterval('diamantTimerEnd()', 1000);
    </script>
<?php } ?>