<?php

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    $exp = 75;

    $types[0] = "Defuse the bomb";
    $types[1] = "Bankheist";
    /* $types[2] = "F*!KING CIRCUZ";
 */
    $countdown[0] = time() + 1200;
    $countdown[1] = time() + 2400;
    /* $countdown[2] = time() + 3600;
 */
    $cooldown[0] = time() + 2400;
    $cooldown[1] = time() + 3600;
    /* $cooldown[2] = time() + 4800;
 */
    $risk[0] = "Lav";
    $risk[1] = "Middels";
    /* $risk[2] = "Høy";
 */
    $desc[0] = "Finn den riktige kombinasjonen ved å utføre kriminelle handlinger og avkoble bomben før den eksploderer";
    $desc[1] = "Stjel mest mulig penger fra banken i løpet av 40 minutter. Desto mer penger gruppen får tak i, jo mer penger vil alle tjene.";
    /* $desc[2] = "Klar for en utfordring?";
 */
    $time_heist[0] = 20;
    $time_heist[1] = 40;
    /* $time_heist[2] = 60;
 */
    $payout[0] = mt_rand(15000000, 25000000);
    $payout[1] = 35000000;
    /* $payout[2] = 75000000;
 */
    $notification[0] = "Dere klarte å avkoble bomben før tiden. Som belønning får du " . number($payout[0]) . " kr!";
    $notification[1] = "";
    /* $notification[2] = "OPPDRAGSTEKST ".$payout[2]." kr!";
 */
    $feedback[0] = "Koden er riktig! Som belønning ønsker Cari Polti å gi hvert medlem av heistet " . number($_GET['payout'] ?? 0) . " kr!";
    $feedback[1] = "Dere kjørte fra politiet og som belønning kan dere dele alle pengene på medlemmene";
    /* $feedback[2] = "";
 */
    if (isset($_GET['feedback'])) {
        if (is_numeric($_GET['feedback']) && $_GET['feedback'] == 0 || $_GET['feedback'] < 3) {
            echo feedback($feedback[$_GET['feedback']], "success");
        }
    }

    if (heist_ready($_SESSION['ID'], $pdo)) {
        $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
        $stmt->execute(array(
            ':cd_id' => $_SESSION['ID']
        ));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $time_left = $row['CD_heist'] - time();

        $minutes = floor($time_left / 60);
        $seconds = $time_left - $minutes * 60;

        echo feedback('Du har nylig utført et heist. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan utføre et nytt heist.', 'cooldown'); ?>
        <script>
            timeleft(<?php echo $time_left; ?>, "countdowntimer");
        </script>

        <?php

    } else {

        if (!active_heist($_SESSION['ID'], $pdo)) {

            if (isset($_GET['join_heist'])) {
                $heist_id = $_GET['join_heist'];
                if (heist_exist($heist_id, $pdo) && heist_row($heist_id, 'HEIST_status', $pdo) == 0) {
                    if (heist_row($heist_id, 'HEIST_city', $pdo) == AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) {
                        $sql = "INSERT INTO heist_members (HEIME_acc_id, HEIME_heist_id) VALUES (?,?)";
                        $pdo->prepare($sql)->execute([$_SESSION['ID'], $heist_id]);

                        header("Location: ?side=heist");
                    } else {
                        echo feedback("Du er ikke i riktig by", "error");
                    }
                } else {
                    echo feedback("Heistet eksisterer ikke", "error");
                }
            }

            for ($j = 0; $j < count($desc); $j++) {
                if (isset($_POST['heist_' . $j . ''])) {

                    if ($j == 0) {
                        $info = mt_rand(1000000, 9999999);
                    } elseif ($j == 2) {
                        $info = mt_rand(0, 5);
                    } else {
                        $info = 0;
                    }

                    // NOTE: Do not change the 0 for the HEIST_countdown (2nd parameter)
                    // it is used to avoid players from extending their heist time later
                    start_heist($j, 0, $_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), 0, $info, $pdo);

                    header("Location: ?side=heist");
                }
            }

        ?>
            <div class="col-9 single">
                <form method="post">
                    <div class="content">
                        <?php for ($i = 0; $i < count($desc); $i++) { ?>
                            <div class="col-4">
                                <img style="margin-bottom: 10px; border-radius: 1px;" src="img/action/actions/heist<?php echo ($i + 1); ?>.png">
                                <p class="description"><?php echo $desc[$i]; ?></p>
                                <p>
                                    Tid: <b><?php echo $time_heist[$i]; ?> minutter</b><br>
                                    Risiko: <b><?php echo $risk[$i]; ?></b>
                                </p>
                                <input type="submit" name="heist_<?php echo $i; ?>" value="Start heist">
                            </div>
                        <?php } ?>
                        <div style="clear: both;"></div>
                    </div>
                </form>
            </div>

            <div class="col-7 single" style="margin-top: 10px;">
                <div class="content">
                    <h3>Aktive heist</h3>
                    <p class="description">Her har du mulighet for å bli med på heist som ikke er startet enda. Dersom du blir med på et åpent heist vil alle medlemmene i heistet se hvilken by du er i.</p>
                    <table>
                        <tr>
                            <th>Leder</th>
                            <th>Type</th>
                            <th>By</th>
                            <th></th>
                        </tr>
                        <?php

                        $sql = "SELECT * FROM heist WHERE HEIST_status = 0";
                        $stmt = $pdo->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        ?>
                            <tr>
                                <td><a href="?side=profil&id=<?php echo $row['HEIST_leader']; ?>"><?php echo ACC_username($row['HEIST_leader'], $pdo); ?></a></td>
                                <td><?php echo $types[$row['HEIST_type']]; ?></td>
                                <td><?php echo city_name($row['HEIST_city']); ?></td>
                                <td><a class="btn" href="?side=heist&join_heist=<?php echo $row['HEIST_id']; ?>">Bli med</a></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        <?php } else {

            $heist_id = get_my_heistID($_SESSION['ID'], $pdo);
            $heist_members = heist_members($heist_id, $pdo);

            $stmt = $pdo->prepare("SELECT * FROM heist WHERE HEIST_id = :id");
            $stmt->execute(['id' => $heist_id]);
            $row_heist = $stmt->fetch();

            $desc_detailed[0] = "Oppdraget er å finne koden til å avkoble bomben som er plantet i bilen til Cari Polti. Dersom du skriver koden feil vil nedtellingen miste 10 sekunder. Sørg for å få koden riktig.";

            $desc_detailed[1] = "Dere skal skaffe mest mulig penger og kjøre fra politiet før tiden går ut. Kjapp dere!";

            $desc_detailed[2] = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae diam placerat, tempus mi id, scelerisque magna. Pellentesque eleifend in nibh ac volutpat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.";

            if (isset($_POST['end_heist'])) {
                heist_end($heist_id, $pdo);
                header("Location: ?side=heist");
            }

            if (isset($_POST['leave_heist'])) {
                $sql = "DELETE FROM heist_members WHERE HEIME_acc_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['ID']]);

                header("Location: ?side=heist");
            }

            if (isset($_POST['start_heist'])) {
                $sql = "UPDATE heist SET HEIST_status = ?, HEIST_countdown = ? WHERE HEIST_id = ? AND HEIST_countdown = 0";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([1, $countdown[heist_row($heist_id, 'HEIST_type', $pdo)], $heist_id]);
            }

        ?>
            <div class="col-12" style="margin-top: -10px;">
                <div class="col-8" style="margin-top: -10px;">
                    <div class="content">
                        <div class="col-6">
                            <center>
                                <img style="margin-bottom: 10px; border-radius: 1px;" src="img/action/actions/heist<?php echo ($row_heist['HEIST_type'] + 1); ?>.png">
                            </center>
                        </div>
                        <div class="col-6" style="margin-top: -10px;">
                            <h4>Heist panel</h4>
                            <p class="description">
                                <?php echo $desc_detailed[$row_heist['HEIST_type']]; ?>
                            </p>
                            <?php if (heist_row($heist_id, 'HEIST_status', $pdo) == 0) { ?>
                                <form method="post">
                                    <?php if ($row_heist['HEIST_leader'] == $_SESSION['ID']) { ?>
                                        <input type="submit" name="start_heist" value="Start heist">
                                        <input type="submit" name="end_heist" value="Avslutt heist">
                                    <?php } else { ?>
                                        <input type="submit" name="leave_heist" value="Forlat heist">
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <?php if (heist_row($heist_id, 'HEIST_type', $pdo) == 0) {
                                    $heist_members = heist_members($heist_id, $pdo);
                                    $explodes_at = heist_row($heist_id, 'HEIST_countdown', $pdo);
                                    $time_left = $explodes_at - time();

                                    echo feedback('Koden til bomben finnes ved å utføre diverse handlinger. Dersom dere møter riktig person vil personen gi deg deler av koden. Gå sammen og finn koden før det er for sent!', "blue");

                                    if (isset($_POST['defuse'])) {
                                        if (heist_row($heist_id, 'HEIST_leader', $pdo) == $_SESSION['ID']) {
                                            if (is_numeric($_POST['code']) && strlen($_POST['code']) == 7) {
                                                if (heist_row($heist_id, 'HEIST_info', $pdo) == $_POST['code']) {
                                                    $payout = 25000 * ($explodes_at - time());

                                                    // This can happen if they defuse it right before the time runs out and the server is a bit slow
                                                    if ($payout < 0) {
                                                        // Make sure the members arent billed.
                                                        $payout = 0;
                                                    }

                                                    foreach ($heist_members as $member) {
                                                        give_money($member->ACC_id, $payout, $pdo);
                                                        give_exp($member->ACC_id, $exp, $pdo);

                                                        send_notification(
                                                            $member->ACC_id,
                                                            "Dere klarte å avkoble bomben før tiden. Som belønning får du " . number($payout) . " kr!",
                                                            $pdo
                                                        );

                                                        heist_give_cooldown($member->ACC_id, $cooldown[0], $pdo);

                                                        update_dagens_utfordring($member->ACC_id, 3, $pdo);

                                                        if (AS_session_row($member->ACC_id, 'AS_mission', $pdo) == 40) {
                                                            mission_update(
                                                                AS_session_row($member->ACC_id, 'AS_mission_count', $pdo) + $payout,
                                                                AS_session_row($member->ACC_id, 'AS_mission', $pdo),
                                                                mission_criteria(AS_session_row($member->ACC_id, 'AS_mission', $pdo)),
                                                                $member->ACC_id,
                                                                $pdo
                                                            );
                                                        }
                                                    }

                                                    heist_end($heist_id, $pdo);
                                                    header("Location: ?side=heist&feedback=0&payout=$payout");
                                                } else {
                                                    $sql = "UPDATE heist SET HEIST_countdown = HEIST_countdown - ? WHERE HEIST_id = ? ";
                                                    $stmt = $pdo->prepare($sql);
                                                    $stmt->execute([10, $heist_id]);

                                                    echo feedback("Dere skrev feil kode! 10 sekunder er fjernet fra nedtellingen", "error");
                                                }
                                            } else {
                                                echo feedback("Koden kan kun bestå av tall og ikke mer enn 7 siffer!", "fail");
                                            }
                                        } else {
                                            echo feedback("Det er kun lederen som kan taste inn koden", "fail");
                                        }
                                    }

                                    $minutes = floor($time_left / 60);
                                    $seconds = $time_left - $minutes * 60;
                                ?>
                                    <script>
                                        const explodesAt = new Date(<?php echo $explodes_at * 1000 ?>);
                                        const memberCount = <?php echo count($heist_members); ?>;
                                        timeleft(<?php echo $time_left; ?>, "countdowntimer");
                                    </script>


                                    <?php include 'app/heist/bomb.php'; ?>

                                    <?php } elseif (heist_row($heist_id, 'HEIST_type', $pdo) == 1) {
                                    $heist_members = heist_members($heist_id, $pdo);
                                    $heist_ends_at = heist_row($heist_id, 'HEIST_countdown', $pdo);
                                    $time_left = $heist_ends_at - time();

                                    $minutes = floor($time_left / 60);
                                    $seconds = $time_left - $minutes * 60;

                                    if (isset($_POST['run'])) {
                                        if (heist_row($heist_id, 'HEIST_leader', $pdo) != $_SESSION['ID']) {
                                            echo feedback("Det er kun lederen som kan starte kjøringen!", "error");
                                        } else {
                                            $sql = "UPDATE heist SET HEIST_status = ? WHERE HEIST_id = ? ";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([2, $heist_id]);
                                        }
                                    }

                                    if (heist_row($heist_id, 'HEIST_status', $pdo) == 1) {
                                    ?>
                                        <p>Deres oppdrag er å skaffe mest mulig penger fra kriminalitet. Pengene må ranes og fraktes før tiden har gått ut. Det er viktig å komme seg unna politiet med pengene før tiden går ut.</p>
                                        <?php

                                        echo feedback('<b>Tid igjen:</b> <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t>', 'cooldown'); ?>
                                        <script>
                                            timeleft(<?php echo $time_left; ?>, "countdowntimer");
                                        </script>

                                        <?php echo feedback("<b>Penger i gruppen:</b> " . number(heist_row($heist_id, 'HEIST_info', $pdo)) . " kr", 'money'); ?>
                                        <?php if (heist_row($heist_id, 'HEIST_leader', $pdo) == $_SESSION['ID']) { ?>
                                            <form method="post">
                                                <input type="submit" name="run" value="Kjør fra politiet med tyvegodset.">
                                            </form>
                                        <?php } ?>
                                        <?php } else {

                                        for ($i = 0; $i < 4; $i++) {
                                            if (isset($_POST[$i])) {
                                                if (heist_row($heist_id, 'HEIST_leader', $pdo) != $_SESSION['ID']) {
                                                    echo feedback("Det er kun lederen som kan kjøre!", "error");
                                                } else {
                                                    if (mt_rand(0, 3) == 3) {

                                                        $new_value = heist_row($heist_id, 'HEIST_info', $pdo) * .8;
                                                        $new_value = floor($new_value);

                                                        $sql = "UPDATE heist SET HEIST_info = ? WHERE HEIST_id = ? ";
                                                        $stmt = $pdo->prepare($sql);
                                                        $stmt->execute([$new_value, $heist_id]);

                                                        if (heist_row($heist_id, 'HEIST_status', $pdo) > 3) {
                                                            $sql = "UPDATE heist SET HEIST_status = HEIST_status - ? WHERE HEIST_id = ? ";
                                                            $stmt = $pdo->prepare($sql);
                                                            $stmt->execute([1, $heist_id]);
                                                        }

                                                        echo feedback("Du ble stoppet av politiet og mistet 20% av potten!", "error");
                                                    } else {
                                                        $sql = "UPDATE heist SET HEIST_status = HEIST_status + ? WHERE HEIST_id = ? ";
                                                        $stmt = $pdo->prepare($sql);
                                                        $stmt->execute([1, $heist_id]);

                                                        echo feedback("Du kjørte unna politiet!", "success");
                                                    }
                                                }
                                            }
                                        }

                                        if (heist_row($heist_id, 'HEIST_status', $pdo) == 8) {

                                            $money = heist_row($heist_id, 'HEIST_info', $pdo) / count($heist_members);
                                            $text = "Dere klarte å kjøre fra politiet og du får " . number($money) . " kr.";

                                            foreach ($heist_members as $member) {
                                                give_money($member->ACC_id, $money, $pdo);
                                                give_exp($member->ACC_id, $exp, $pdo);

                                                send_notification($member->ACC_id, $text, $pdo);

                                                heist_give_cooldown($member->ACC_id, $cooldown[heist_row($heist_id, 'HEIST_type', $pdo)], $pdo);

                                                update_dagens_utfordring($member->ACC_id, 3, $pdo);

                                                if (AS_session_row($member->ACC_id, 'AS_mission', $pdo) == 40) {
                                                    mission_update(
                                                        AS_session_row($member->ACC_id, 'AS_mission_count', $pdo) + $money,
                                                        AS_session_row($member->ACC_id, 'AS_mission', $pdo),
                                                        mission_criteria(AS_session_row($member->ACC_id, 'AS_mission', $pdo)),
                                                        $member->ACC_id,
                                                        $pdo
                                                    );
                                                }
                                            }

                                            heist_end($heist_id, $pdo);
                                            header("Location: ?side=heist&feedback=1");
                                        } else {

                                        ?>
                                            <?php if (heist_row($heist_id, 'HEIST_leader', $pdo) == $_SESSION['ID']) { ?>
                                                <?php echo feedback("<b>Penger i gruppen:</b> " . number(heist_row($heist_id, 'HEIST_info', $pdo)) . " kr", 'money'); ?>
                                                <?php echo feedback('<b>Tid igjen:</b> <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t>', 'cooldown'); ?>
                                                <script>
                                                    timeleft(<?php echo $time_left; ?>, "countdowntimer");
                                                </script>

                                                <p>Du må nå kjøre fra politiet! For hver gang du blir stoppet av politiet mister du 20% av potten.</p>
                                                <p>Hvilken vei vil du kjøre?</p>
                                                <form method="post">
                                                    <?php include 'app/heist/gta.php'; ?>
                                                </form>
                                            <?php } else {

                                                echo feedback('<b>Tid igjen:</b> <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t>', 'cooldown'); ?>
                                                <script>
                                                    timeleft(<?php echo $time_left; ?>, "countdowntimer");
                                                </script>
                                            <?php
                                                echo feedback("<b>Penger i gruppen:</b> " . number(heist_row($heist_id, 'HEIST_info', $pdo)) . " kr", 'money');
                                                echo feedback("Lederen av heistet er i ferd med å kjøre fra politiet!", "blue");
                                            }

                                            ?>
                                    <?php }
                                    } ?>

                                <?php } elseif (heist_row($heist_id, 'HEIST_type', $pdo) == 2) { ?>
                                    Circus
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </div>
                <div class="col-4" style="margin-top: -10px;">
                    <div class="content">
                        <h4 style="margin-bottom: 10px;">Medlemmer</h4>
                        <table>
                            <tr>
                                <th>Brukernavn</th>
                            </tr>
                            <?php
                            foreach ($heist_members as $member) {
                            ?>
                                <tr>
                                    <td>
                                        <?php if ($member->is_leader) { ?>
                                            👑
                                        <?php } ?>
                                        <a href="?side=profil&id=<?php echo $member->ACC_id; ?>"><?php echo ACC_username($member->ACC_id, $pdo); ?></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <?php if (count($heist_members) > 1) { // Only include the chat if there are more than one in group 
                ?>
                    <div class="col-4" style="margin-top: -10px; float:right">
                        <div class="content">
                            <?php include 'app/heist/heist_chat.php'; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
<?php }
    }
} ?>