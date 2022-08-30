<?php

if (!isset($_GET['side'])) {
    die();
}

if (!isset($_GET['acc_id'])) {

?>
    <div class="col-10 single">
        <div class="content">
            <div class="pad_10">
                <?php

                if (isset($_POST['search'])) {
                    if ($_POST['username'] == null || strlen($_POST['username']) < 3) {
                        echo feedback('Skriv minst 3 tegn', "fail");
                    } elseif (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['username'])) {
                        echo feedback('Ugyldig input', "fail");
                    } else {
                ?>
                        <table>
                            <tr>
                                <th>Brukernavn</th>
                                <th>Sist aktiv</th>
                                <th>Handling</th>
                            </tr>
                            <?php
                            $username = $_POST['username'];

                            $sql = "SELECT * FROM accounts WHERE ACC_username LIKE '%$username%'";
                            $stmt = $pdo->query($sql);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <tr>
                                    <td><?php echo ACC_username($row['ACC_id'], $pdo); ?></td>
                                    <td><?php echo date_to_text($row['ACC_last_active']) ?></td>
                                    <td><a href="?side=admin&adminside=heatmap_krim&acc_id=<?php echo $row['ACC_id'] ?>">Sjekk heatmap (krim)</a></td>
                                </tr>
                        <?php }
                        } ?>
                        </table>
                    <?php } else { ?>
                        <form method="post">
                            Hvilken bruker ønsker du å sjekke ut?
                            <input name="username" type="text" placeholder="Brukernavn...">
                            <input name="search" type="submit" value="Søk">
                        </form>
                    <?php } ?>
            </div>
        </div>
    </div>

<?php } else { ?>

    <form method="post">
        <label for="time">Velg tidsperspektiv</label>
        <select name="time" id="cars">
            <option value="1">Siste timen</option>
            <option value="2">Siste 2 timer</option>
            <option value="3">Siste 6 timer</option>
            <option value="4">Siste 12 timer</option>
            <option value="5">Siste 24 timer</option>
        </select>
        <input type="submit" name="update" value="Oppdater">
    </form>



    <div class="col-8 single">
        <div class="content" id="heatmap">

            <?php

            if (isset($_POST['time'])) {
                $times[0] = 0;
                $times[1] = 3600;
                $times[2] = 7200;
                $times[3] = 21600;
                $times[4] = 43200;
                $times[5] = 86400;

                $query = $pdo->prepare('SELECT * FROM heatmap WHERE HM_page = :loc AND HM_acc_id = :id AND HM_date > :time');
                $query->execute(array(':loc' => "kriminalitet", ':id' => $_GET['acc_id'], ':time' => (time() -  $times[$_POST['time']])));

                foreach ($query as $row) {
                    $x = $row['HM_x'] - 2.5;
                    $y = $row['HM_y'] - 2.5;

                    echo "<div style='
                          left: " . $x . ";
                          top: " . $y . ";
                          padding: 5px 5px;
                          background-color: rgba(50, 205, 50,.1);
                          border-radius: 10px;
                          position: absolute;'></div>";
                }
            }


            ?>
            <img class="action_image" src="img/action/actions/kriminalitet.png">
            <table>
                <tr>
                    <th style="width: 40%;">Handling</th>
                    <th style="width: 20%;">Ventetid</th>
                    <th style="width: 20%;">Sjanse</th>
                    <th style="width: 20%;">Utbetaling</th>
                </tr>

                <?php

                if (active_heist($_SESSION['ID'], $pdo) && heist_row($heist_id, 'HEIST_type', $pdo) == 1 && heist_row($heist_id, 'HEIST_status', $pdo) == 1) {
                    $crime_amount = 9;
                } else {
                    $crime_amount = 8;
                }
                for ($i = 0; $i < $crime_amount; $i++) {

                ?>
                    <tr class="cursor_hover clickable-row" data-href="?side=kriminalitet&alt=<?php echo $i; ?>">
                        <td>Lorem ipsum </td>
                        <td>Lorem ipsum s</td>
                        <td>Lorem ipsum %</td>
                        <td>Lorem ipsum </td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>

    <br><br>
    <b>Forklaring:</b>
    <br>
    <div style='float: left; height: 1px; width: 1px; padding: 5px 5px; background-color: rgba(50, 205, 50, 0.1); border-radius: 10px;'></div>Lite
    <br>
    <div style='float: left; height: 1px; width: 1px; padding: 5px 5px; background-color: rgba(50, 205, 50, 0.5); border-radius: 10px;'></div>Middels (Over 5 trykk på samme sted)
    <br>
    <div style='float: left; height: 1px; width: 1px; padding: 5px 5px; background-color: rgba(50, 205, 50, 1); border-radius: 10px;'></div>Mye (Risiko om bot (Over 10 trykk på samme sted))
    <style>
        #heatmap {
            position: relative;
        }
    </style>

<?php } ?>