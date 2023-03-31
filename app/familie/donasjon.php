<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);

    if (isset($_GET['p']) && $_GET['p'] == 'donasjon') {
        if (isset($_GET['money_in'])) {
            echo feedback("Du donerte " . number($_GET['money_in']) . " kr til din familie", "success");
        }

        if (isset($_GET['money_out'])) {
            echo feedback("Du tok ut " . number($_GET['money_out']) . " kr fra familiekassen", "success");
        }
    }

    if (isset($_POST['money_in'])) {
        $value = remove_space($_POST['value']);
        if (isset($value) && is_numeric($value) && $value > 0) {
            if ($value > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
                echo feedback("Du kan ikke donere mer penger enn du har på hånden.", "error");
            } else {
                take_money($_SESSION['ID'], $value, $pdo);
                give_my_family_money($my_family_id, $value, $pdo);
                update_donationlist($my_family_id, $_SESSION['ID'], 0, $value, $pdo);

                if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 30) {
                    mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + $value, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                }

                header("location: ?side=familie&p=donasjon&money_in=" . $value . "");
            }
        } else {
            echo feedback("Ugyldig verdi", "fail");
        }
    }

    if (isset($_POST['money_out'])) {
        $value = remove_space($_POST['value']);
        if (isset($value) && is_numeric($value) && $value > 0) {
            if (get_my_familyrole($_SESSION['ID'], $pdo) == 3) {
                echo feedback("Brukere kan ikke ta ut penger fra familiekassen", "error");
            } elseif ($value > get_my_familybank($my_family_id, $pdo)) {
                echo feedback("Du kan ikke ta ut mer penger enn det er i familiekassen", "error");
            } else {
                take_my_family_money($my_family_id, $value, $pdo);
                give_money($_SESSION['ID'], $value, $pdo);
                update_donationlist($my_family_id, $_SESSION['ID'], 1, $value, $pdo);

                header("location: ?side=familie&p=donasjon&money_out=" . $value . "");
            }
        } else {
            echo feedback("Ugyldig verdi", "fail");
        }
    }

?>
    <div class="content">
        <form method="post">
            <h3 style="text-align: center;">Penger i familiekassen: <?php echo number(get_my_familybank($my_family_id, $pdo)); ?> kr</h3><br>
            <p class="description">Donasjoner kan hjelpe familien ved å bygge kapital som kan brukes for å styrke deres forsvar eller kjøpe familiebedrift.</p>
            <p>Sett inn penger</p>
            <input style="width: 70%;" type="text" id="number" name="value" placeholder="Antall...">
            <input style="width: 29%;" type="submit" name="money_in" value="Sett inn">
        </form>
        <?php if (get_my_familyrole($_SESSION['ID'], $pdo) != 3) { ?>
            <form method="post">
                <p>Ta ut penger</p>
                <input style="width: 70%;" type="text" id="number2" name="value" placeholder="Antall...">
                <input style="width: 29%;" type="submit" name="money_out" value="Ta ut">
            </form>
        <?php } ?>
        <?php
        if (get_my_familyrole($_SESSION['ID'], $pdo) != 3) {
        ?>
            <h3 style="margin: 20px 0px 10px 0px;">Transaksjonsliste</h3>
            <table id="datatable" data-order='[[3]]'>
                <thead>
                    <tr>
                        <th>Bruker</th>
                        <th>Handling</th>
                        <th>Verdi</th>
                        <th>Dato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $my_family = get_my_familyID($_SESSION['ID'], $pdo);

                    $action[0] = "<span style='color: green;'>Penger inn</span>";
                    $action[1] = "<span style='color: orange;'>Penger ut</span>";
                    $action[2] = "<span style='color: orange;'>Forsvarspoeng kjøp</span>";
                    $action[3] = "<span style='color: orange;'>Territorium kjøp</span>";
                    $action[4] = "<span style='color: orange;'>Forsvar kjøp</span>";

                    $sql = "SELECT * FROM family_donation WHERE FAMDON_fam_id = $my_family ORDER BY FAMDON_date DESC";
                    $stmt = $pdo->query($sql);
                    while ($row_famdon = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <tr>
                            <td><?php echo ACC_username($row_famdon['FAMDON_acc_id'], $pdo); ?></td>
                            <td><?php echo $action[$row_famdon['FAMDON_action']]; ?></td>
                            <td data-order='<?php echo $row_famdon['FAMDON_value']; ?>'><?php echo number($row_famdon['FAMDON_value']); ?></td>
                            <td data-order='<?php echo $row_famdon['FAMDON_date']; ?>'><?php echo date_to_text($row_famdon['FAMDON_date']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <script>
        number_space("#number");
        number_space("#number2");

        /*         $(document).ready(function() {
                $('#datatable').DataTable();
            }); */
    </script>
    <!-- <script type="text/javascript" src="includes/datatable/datatables.min.js"></script> -->
<?php } ?>