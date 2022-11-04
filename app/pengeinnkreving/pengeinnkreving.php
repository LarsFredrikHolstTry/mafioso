<?php

if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) == 0) {
    echo feedback('Funksjonen gjennomgår testing og vil lanseres fortløpende!', 'blue');
} else {

    $price_start = 10000000;

    $utstyrName = ['Ingen utstyr', 'Avansert utstyr', 'Medium utstyr', 'Dårlig utstyr'];
    $chanceBoost = [0, 80, 50, 30];
    $utstyrPrice = ['0', '10000000', '5000000', '1000000'];

    function getPengGroupid($userId, $pdo)
    {
        $group_id = 0;

        $sql = "SELECT * FROM peng_group";
        $stmt = $pdo->query($sql);
        while ($row_members = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arr = json_decode($row_members['PENG_members']);

            if ($row_members['PENG_leader'] == $userId) {
                $group_id = $row_members['PENG_id'];
            }

            if (in_array($userId, $arr)) {
                $group_id = $row_members['PENG_id'];
            }
        }

        return $group_id;
    }

    $isMemberOfAGroup = getPengGroupid($_SESSION['ID'], $pdo) != 0;

    if (isset($_GET['m'])) {
        echo feedback('Pengeinnkrevingen feilet og gruppen ble brutt opp!', 'fail');
    }

    if (isset($_GET['equipment'])) {
        echo feedback('Du kjøpte ' . $utstyrName[$_GET['equipment']] . ' til din gruppe', 'success');
    }

    if (isset($_POST['start_group'])) {
        if ($price_start > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
            echo feedback('Du har ikke nok penger til å starte en pengeinnkrevingsgruppe', 'error');
        } else {
            $sql = "INSERT INTO peng_group (PENG_leader, PENG_money, PENG_equipment, PENG_members, PENG_date) VALUES (?,?,?,?,?)";
            $pdo->prepare($sql)->execute([$_SESSION['ID'], 0, 0, json_encode([]), time()]);

            take_money($_SESSION['ID'], $price_start, $pdo);

            header("Location: ?side=pengeinnkreving");

            // echo feedback('Du har startet en pengeinnkrevingsgruppe! Kjøp utstyr og start pengeinnkrevingen', 'blue');
        }
    }

    if (isset($_GET['join'])) {
        $join_id = $_GET['join'];

        if ($isMemberOfAGroup) {
            echo feedback('Ugyldig ID: Du er allerede med i en pengeinnkrevingsgruppe!', 'fail');
        } elseif (!is_numeric($join_id) || $join_id < 0) {
            echo feedback('Ugyldig ID: ID er NaN', 'fail');
        } elseif (!$isMemberOfAGroup && event_ready($_SESSION['ID'], $pdo)) {
            echo feedback('Ikke mulig å bli med i gruppe. Du har enten ventetid eller er allerede med i en gruppe', 'fail');
        } else {
            $query = $pdo->prepare("SELECT PENG_members FROM peng_group WHERE PENG_id = ?");
            $query->execute(array($join_id));
            $PENG_row = $query->fetch(PDO::FETCH_ASSOC);

            if (!$PENG_row) {
                echo feedback('Ugyldig ID: Gruppen eksisterer ikke', 'fail');
            } else {

                $members = json_decode($PENG_row['PENG_members']);
                $memberArr = [];

                foreach ($members as $member) {
                    array_push($memberArr, $member);
                }

                array_push($memberArr, $_SESSION['ID']);

                $sql = "UPDATE peng_group SET PENG_members = ? WHERE PENG_id = ? ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([json_encode($memberArr), $join_id]);
            }
        }
    }


?>

    <div class="col-12 single">

        <div class="col-7">
            <div class="content">
                <?php if ($isMemberOfAGroup) {
                    $pengId = getPengGroupid($_SESSION['ID'], $pdo);
                    $query = $pdo->prepare("SELECT * FROM peng_group WHERE PENG_id=?");
                    $query->execute(array($pengId));
                    $PENG_row = $query->fetch(PDO::FETCH_ASSOC);

                    if ($PENG_row['PENG_equipment'] == 0) {
                        include 'velgUtstyr.php';
                    } else {
                        include 'handling.php';
                    }

                    if (isset($_POST['leaveGroup'])) {
                        if ($PENG_row['PENG_leader'] == $_SESSION['ID']) {
                            last_event($_SESSION['ID'], ' sin pengeinnkrevingsgruppe tjente ' . number($PENG_row['PENG_money']) . ' kr per medlem før gruppen ble oppløst.', $pdo);

                            $sql = "DELETE FROM peng_group WHERE PENG_leader = " . $_SESSION['ID'];
                            $pdo->exec($sql);

                            header("Location: ?side=pengeinnkreving");
                        } else {
                            $members = json_decode($PENG_row['PENG_members']);
                            $memberArr = [];

                            foreach ($members as $member) {
                                array_push($memberArr, $member);
                            }

                            foreach (array_keys($memberArr, $_SESSION['ID'], true) as $key) {
                                unset($memberArr[$key]);
                            }

                            $sql = "UPDATE peng_group SET PENG_members = ? WHERE PENG_id = ? ";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([json_encode($memberArr), $PENG_row['PENG_id']]);

                            header("Location: ?side=pengeinnkreving");
                        }
                    }
                } else { ?>
                    <img class="action_image" src="img/action/actions/pengeinnkreving.png" />
                    <?php

                    if (event_ready($_SESSION['ID'], $pdo)) {
                        $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
                        $stmt->execute(array(
                            ':cd_id' => $_SESSION['ID']
                        ));
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        $time_left = $row['CD_event'] - time();

                        $minutes = floor($time_left / 60);
                        $seconds = $time_left - $minutes * 60;

                        echo feedback('Du har nylig vært med på pengeinnkreving. Det er <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan starte en ny gruppe eller bli med i en gruppe.', 'cooldown'); ?>
                        <script>
                            timeleft(<?php echo $time_left; ?>, "countdowntimer");
                        </script>

                    <?php

                    } else {

                    ?>
                        <p>I pengeinnkreving gjelder det å jobbe raskt og smart. Det lønner seg ikke å bli grådig og
                            handlingene må gjøres etter tur og orden. For å ikke bli tatt av politiet er det viktig
                            å ikke bli for trigger-happy. Utennom det er det bare å kjøre på.</p>
                        <form method="post">
                            <p>
                                Prisen for å starte en gruppe er 10 000 000kr
                            </p>
                            <input type="submit" name="start_group" value="Start gruppe" />
                        </form>
                <?php }
                } ?>
            </div>
        </div>
        <?php if (!event_ready($_SESSION['ID'], $pdo) || $isMemberOfAGroup) { ?>
            <div class="col-5">
                <div class="content">
                    <?php if (getPengGroupid($_SESSION['ID'], $pdo) != 0) {
                        $pengId = getPengGroupid($_SESSION['ID'], $pdo);
                        $query = $pdo->prepare("SELECT * FROM peng_group WHERE PENG_id=?");
                        $query->execute(array($pengId));
                        $PENG_row = $query->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <h3 style="margin-bottom: 10px;">Min gruppe</h3>
                        <p>Penger tjent totalt: <?= number($PENG_row['PENG_money']) ?>kr</p>
                        <span>Leder: <?= ACC_username($PENG_row['PENG_leader'], $pdo); ?></span>
                        <br>
                        <span>Andre medlemmer:
                            <?php

                            $hasMembers = count(json_decode($PENG_row['PENG_members'])) > 0;

                            if ($hasMembers) {
                                for ($i = 0; $i < count(json_decode($PENG_row['PENG_members'])); $i++) {
                                    echo ACC_username(json_decode($PENG_row['PENG_members'])[$i], $pdo) . ',';
                                }
                            } else {
                                echo '<span class="description">Ingen medlemmer</span>';
                            }

                            ?>
                        </span>
                        <form method="post">
                            <p class="description">Du kan når som helst forlate gruppen du er med i</p>
                            <input type="submit" value="Forlat gruppe" name="leaveGroup" onclick="return confirm('Er du sikker på at du ønsker å forlate pengeinnkrevingsgruppen? Hvis du er leder vil du legge ned hele gruppen')" />
                        </form>
                        <h4 style="margin-top: 20px;">Siste 20 handlinger</h4>
                        <div>
                            <?php
                            $sql = "SELECT * FROM peng_logg WHERE PENGLOG_pengID = " . $PENG_row['PENG_id'] . " ORDER BY PENGLOG_date DESC";
                            $stmt = $pdo->query($sql);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <p><?= $row['PENGLOG_desc'] ?> - <?= date_to_text($row['PENGLOG_date']) ?> </p>
                            <?php } ?>
                        </div>
                    <?php
                    } else { ?>
                        <h3 style="margin-bottom: 10px;">Åpne grupper</h3>
                        <table>
                            <tr>
                                <th>Leder</th>
                                <th>Handling</th>
                            </tr>
                            <?php

                            $sql = "SELECT * FROM peng_group WHERE NOT PENG_equipment = 0";
                            $stmt = $pdo->query($sql);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                            ?>
                                <tr>
                                    <td><?php echo ACC_username($row['PENG_leader'], $pdo); ?></td>
                                    <td><a href="?side=pengeinnkreving&join=<?= $row['PENG_id']; ?>">Bli med</a></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>