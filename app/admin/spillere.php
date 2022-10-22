<?php

if (!isset($_GET['side'])) {
    die();
}

if (!isset($_GET['id'])) {

?>
    <div class="content">
        <table id="datatable" data-order='[[2]]'>
            <thead>
                <tr>
                    <th>Brukernavn</th>
                    <th>Registrert</th>
                    <th>Sist aktiv</th>
                    <th>Exp</th>
                    <th>Handling</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $stmt = $pdo->prepare('SELECT ACC_id, ACC_last_active FROM accounts ORDER BY ACC_last_active DESC');
                $stmt->execute(array());

                foreach ($stmt as $row) {
                    $reg_date = ACC_session_row($row['ACC_id'], 'ACC_register_date', $pdo);
                    $last_date = ACC_session_row($row['ACC_id'], 'ACC_last_active', $pdo);
                    $exp = AS_session_row($row['ACC_id'], 'AS_exp', $pdo);
                ?>
                    <tr>
                        <td><?php echo ACC_username($row['ACC_id'], $pdo); ?></td>
                        <td data-order="<?php echo $reg_date ?>"><?php echo date_to_text($reg_date); ?></td>
                        <td data-order="<?php echo $last_date ?>"><?php echo date_to_text($last_date); ?></td>
                        <td data-order="<?php echo $exp ?>"><?php echo number($exp); ?></td>
                        <td><a href="?side=admin&adminside=spillere&id=<?php echo $row['ACC_id']; ?>">Endre</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } else {

    $query = $pdo->prepare("SELECT * FROM accounts WHERE ACC_id=?");
    $query->execute(array($_GET['id']));
    $ACC_edit_row = $query->fetch(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT * FROM accounts_stat WHERE AS_id=?");
    $query->execute(array($_GET['id']));
    $AS_edit_row = $query->fetch(PDO::FETCH_ASSOC);

    $query = $pdo->prepare("SELECT * FROM user_statistics WHERE US_acc_id=?");
    $query->execute(array($_GET['id']));
    $US_edit_row = $query->fetch(PDO::FETCH_ASSOC);


    if (isset($_GET['edit_profile'])) {
        if (isset($_POST['update_profile'])) {
            $profile_text = $_POST['profile_text'];

            $stmt = $pdo->prepare("UPDATE accounts_stat SET AS_bio = :as_bio WHERE AS_id = :as_id");
            $stmt->bindParam(":as_bio", $profile_text);
            $stmt->bindParam(":as_id", $_GET['id']);
            $stmt->execute();

            changelog($_GET['id'], "Endre profil", 0, 0, $_SESSION['ID'], $pdo);

            header("Location: ?side=admin&adminside=spillere&id=" . $_GET['id'] . "");
        }

    ?>

        <form method="post">
            <textarea name="profile_text" style="width: 100%; height: 390px;" id="txtarea"><?php echo $AS_edit_row['AS_bio']; ?></textarea>
            <input type="submit" name="update_profile" value="Oppdater profil">
            <a class="btn" style="color: grey;" href="?side=admin&adminside=spillere&id=<?php echo $_GET['id'] ?>">Avbryt</a>
        </form>

    <?php }


    if (isset($_POST['plus'])) {
        if ($_SESSION['ID'] == 1) {
            if (isset($_POST['new_money'])) {
                $_POST['new_money'] = remove_space($_POST['new_money']);
                changelog($_GET['id'], "Penger +", $AS_edit_row['AS_money'], ($AS_edit_row['AS_money'] + $_POST['new_money']), $_SESSION['ID'], $pdo);

                give_money($_GET['id'], $_POST['new_money'], $pdo);

                header("Location: ?side=admin&adminside=spillere&id=" . $_GET['id'] . "");
            } elseif (isset($_POST['new_money_bank'])) {
                $_POST['new_money_bank'] = remove_space($_POST['new_money_bank']);
                changelog($_GET['id'], "Bank_penger +", $AS_edit_row['AS_bankmoney'], ($AS_edit_row['AS_bankmoney'] + $_POST['new_money_bank']), $_SESSION['ID'], $pdo);

                give_bank_money($_GET['id'], $_POST['new_money_bank'], $pdo);

                header("Location: ?side=admin&adminside=spillere&id=" . $_GET['id'] . "");
            } elseif (isset($_POST['new_exp'])) {
                $_POST['new_exp'] = remove_space($_POST['new_exp']);
                changelog($_GET['id'], "EXP +", $AS_edit_row['AS_exp'], ($AS_edit_row['AS_exp'] + $_POST['new_exp']), $_SESSION['ID'], $pdo);

                give_exp($_GET['id'], $_POST['new_exp'], $pdo);

                header("Location: ?side=admin&adminside=spillere&id=" . $_GET['id'] . "");
            } elseif (isset($_POST['new_def'])) {
                $_POST['new_def'] = remove_space($_POST['new_def']);
                changelog($_GET['id'], "FP +", $AS_edit_row['AS_def'], ($AS_edit_row['AS_def'] + $_POST['new_def']), $_SESSION['ID'], $pdo);

                give_fp($_POST['new_def'], $_GET['id'], $pdo);

                header("Location: ?side=admin&adminside=spillere&id=" . $_GET['id'] . "");
            }
        } else {
            echo feedback("Ugyldig handling.", "error");
        }
    }


    if (isset($_POST['minus'])) {
        if ($_SESSION['ID'] == 1) {
            if (isset($_POST['new_money'])) {
                $_POST['new_money'] = remove_space($_POST['new_money']);
                changelog($_GET['id'], "Penger -", $AS_edit_row['AS_money'], ($AS_edit_row['AS_money'] - $_POST['new_money']), $_SESSION['ID'], $pdo);

                take_money($_GET['id'], $_POST['new_money'], $pdo);

                header("Location: ?side=admin&adminside=spillere&id=" . $_GET['id'] . "");
            } elseif (isset($_POST['new_money_bank'])) {
                $_POST['new_money_bank'] = remove_space($_POST['new_money_bank']);
                changelog($_GET['id'], "Bank_penger -", $AS_edit_row['AS_bankmoney'], ($AS_edit_row['AS_bankmoney'] - $_POST['new_money_bank']), $_SESSION['ID'], $pdo);

                take_bank_money($_GET['id'], $_POST['new_money_bank'], $pdo);

                header("Location: ?side=admin&adminside=spillere&id=" . $_GET['id'] . "");
            } elseif (isset($_POST['new_exp'])) {
                $_POST['new_exp'] = remove_space($_POST['new_exp']);
                changelog($_GET['id'], "EXP -", $AS_edit_row['AS_exp'], ($AS_edit_row['AS_exp'] - $_POST['new_exp']), $_SESSION['ID'], $pdo);

                take_exp($_GET['id'], $_POST['new_exp'], $pdo);

                header("Location: ?side=admin&adminside=spillere&id=" . $_GET['id'] . "");
            } elseif (isset($_POST['new_def'])) {
                $_POST['new_def'] = remove_space($_POST['new_def']);
                changelog($_GET['id'], "FP -", $AS_edit_row['AS_def'], ($AS_edit_row['AS_def'] - $_POST['new_def']), $_SESSION['ID'], $pdo);

                take_fp($_POST['new_def'], $_GET['id'], $pdo);

                header("Location: ?side=admin&adminside=spillere&id=" . $_GET['id'] . "");
            }
        } else {
            echo feedback("Ugyldig handling", "error");
        }
    }

    ?>
    <div class="col-12">
        <div class="content">
            <h4 style="margin-bottom: 10px;">Endre spiller: <?php echo $ACC_edit_row['ACC_username']; ?></h4>
            <div class="col-6">
                <table>
                    <tr>
                        <th>Valg</th>
                        <th>Nåværende verdi</th>
                        <th>Endring</th>
                        <th style="width: 1%;"></th>
                    </tr>
                    <tr>
                        <form method="post">
                            <td>Penger ute</td>
                            <td><?php echo number($AS_edit_row['AS_money']); ?></td>
                            <td><input type="text" id="number" name="new_money" style="margin: 0;" placeholder="Antall" required></td>
                            <td><input type="submit" name="plus" style="margin: 1; width: 20px; color: green;" value="+"><input type="submit" name="minus" style="margin: 1; width: 20px; color: red;" value="-"></td>
                        </form>
                    </tr>
                    <tr>
                        <form method="post">
                            <td>Penger bank</td>
                            <td><?php echo number($AS_edit_row['AS_bankmoney']); ?></td>
                            <td><input type="text" id="number2" name="new_money_bank" style="margin: 0;" placeholder="Antall" required></td>
                            <td><input type="submit" name="plus" style="margin: 1; width: 20px; color: green;" value="+"><input type="submit" name="minus" style="margin: 1; width: 20px; color: red;" value="-"></td>
                        </form>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <table>
                    <tr>
                        <th>Valg</th>
                        <th>Nåværende verdi</th>
                        <th>Endring</th>
                        <th style="width: 1%;"></th>
                    </tr>
                    <tr>
                        <form method="post">
                            <td>EXP</td>
                            <td><?php echo number($AS_edit_row['AS_exp']); ?></td>
                            <td><input type="text" id="number3" name="new_exp" style="margin: 0;" placeholder="Antall" required></td>
                            <td><input type="submit" name="plus" style="margin: 1; width: 20px; color: green;" value="+"><input type="submit" name="minus" style="margin: 1; width: 20px; color: red;" value="-"></td>
                        </form>
                    </tr>
                    <tr>
                        <form method="post">
                            <td title="forsvarspoeng">FP (?)</td>
                            <td><?php echo number($AS_edit_row['AS_def']); ?></td>
                            <td><input type="text" id="number4" name="new_def" style="margin: 0;" placeholder="Antall" required></td>
                            <td><input type="submit" name="plus" style="margin: 1; width: 20px; color: green;" value="+"><input type="submit" name="minus" style="margin: 1; width: 20px; color: red;" value="-"></td>
                        </form>
                    </tr>
                </table>
            </div>
            <div class="pad_10">
                <a href="?side=admin&adminside=spillere&id=<?php echo $_GET['id'] ?>&edit_profile" class="btn">Endre profil</a>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="content">
            <div class="header">
                <h4 style="margin-bottom: 10px;">Informasjon om <?php echo $ACC_edit_row['ACC_username']; ?></h4>
            </div>
            <div class="col-6">
                <?php

                $acc_type[0] = "0 = Vanlig bruker";
                $acc_type[1] = "1 = Support";
                $acc_type[2] = "2 = Moderator";
                $acc_type[3] = "3 = Administrator";
                $acc_type[4] = "4 = Drept";
                $acc_type[5] = "666 = Utestengt";

                if (isset($_POST['change_acc_type'])) {
                    $new_acc_type = $_POST['acc_type'];

                    if ($_SESSION['ID'] != 1) {
                        echo feedback("Det er bare Skitzo som kan endre brukere til administrator og moderator.", "fail");
                    } else {
                        $sql = "UPDATE accounts SET ACC_type = ? WHERE ACC_id = ?";
                        $pdo->prepare($sql)->execute([$new_acc_type, $_GET['id']]);

                        echo feedback("Brukeren ble oppdatert", "success");
                    }
                }

                if (isset($_POST['change_acc_status'])) {
                    $new_acc_status = $_POST['acc_status'];


                    $sql = "UPDATE accounts SET ACC_status = ? WHERE ACC_id = ?";
                    $pdo->prepare($sql)->execute([$new_acc_status, $_GET['id']]);

                    echo feedback("Brukeren ble oppdatert", "success");
                }

                ?>
                <table>
                    <tr>
                        <th>Beskrivelse</th>
                        <th>Data</th>
                    </tr>
                    <tr>
                        <td>Acc_type: </td>
                        <td>
                            <form method="post">
                                <input type="text" name="acc_type" style="width: 40%; margin-right: 5px;" value="<?php echo $ACC_edit_row['ACC_type']; ?>">
                                <input name="change_acc_type" value="Oppdater" type="submit">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Acc_status: </td>
                        <td>
                            <form method="post">
                                <input type="text" name="acc_status" style="width: 40%; margin-right: 5px;" value="<?php echo $ACC_edit_row['ACC_status']; ?>">
                                <input name="change_acc_status" value="Oppdater" type="submit">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>Endre brukernavn:</td>
                        <td>
                            <form method="post">
                                <input type="text" name="new_username" style="width: 40%; margin-right: 5px;" value="<?php echo $ACC_edit_row['ACC_username']; ?>">
                                <input name="change_username" value="Oppdater" type="submit">
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>E-mail:</td>
                        <td><?php echo $ACC_edit_row['ACC_mail']; ?></td>
                    </tr>
                    <tr>
                        <td>Registrert:</td>
                        <td><?php echo date_to_text($ACC_edit_row['ACC_register_date']); ?></td>
                    </tr>
                    <tr>
                        <td>Sist aktiv:</td>
                        <td><?php echo date_to_text($ACC_edit_row['ACC_last_active']); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <?php

                if (isset($_POST['change_username'])) {
                    $new_username = $_POST['new_username'];

                    if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) != 3) {
                        echo feedback("Det er bare Skitzo som kan endre brukernavn.", "fail");
                    } else {
                        $sql = "UPDATE accounts SET ACC_username = ? WHERE ACC_id = ?";
                        $pdo->prepare($sql)->execute([$new_username, $_GET['id']]);

                        echo feedback("Brukernavnet ble oppdatert", "success");
                    }
                }

                ?>
                <table>
                    <tr>
                        <th>Beskrivelse</th>
                        <th>Data</th>
                    </tr>
                    <tr>
                        <td>Penger ute:</td>
                        <td><?php echo number($AS_edit_row['AS_money']); ?> kr</td>
                    </tr>
                    <tr>
                        <td>Penger i banken:</td>
                        <td><?php echo number($AS_edit_row['AS_bankmoney']); ?> kr</td>
                    </tr>
                    <tr>
                        <td>Forsvarspoeng</td>
                        <td><?php echo number($AS_edit_row['AS_def']); ?></td>
                    </tr>
                    <tr>
                        <td>Antall poeng</td>
                        <td><?php echo number($AS_edit_row['AS_points']); ?></td>
                    </tr>
                    <tr>
                        <td>Antall exp</td>
                        <td><?php echo number($AS_edit_row['AS_exp']); ?></td>
                    </tr>
                    <tr>
                        <td>By</td>
                        <td><?php echo city_name($AS_edit_row['AS_city']); ?></td>
                    </tr>
                </table>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div class="col-12">
        <div class="content">
            <h4 style="margin-bottom: 10px;">changelog</h4>
            <table id="datatable2" data-order="[[5]]">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Kolonne</th>
                        <th>Verdi før</th>
                        <th>Verdi etter</th>
                        <th>Endret av</th>
                        <th>Dato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $sql = "SELECT * FROM changelog WHERE CL_acc_id = " . $_GET['id'] . " ORDER BY CL_date DESC";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <tr>
                            <td data-order="<?php echo $row['CL_id'] ?>"><?php echo $row['CL_id']; ?></td>
                            <td data-order="<?php echo $row['CL_edit_column'] ?>"><?php echo $row['CL_edit_column']; ?></td>
                            <td data-order="<?php echo $row['CL_value_before'] ?>"><?php echo number($row['CL_value_before']); ?></td>
                            <td data-order="<?php echo $row['CL_value_after'] ?>"><?php echo number($row['CL_value_after']); ?></td>
                            <td><?php echo ACC_username($row['CL_changed_by'], $pdo); ?></td>
                            <td data-order="<?php echo $row['CL_date'] ?>"><?php echo date_to_text($row['CL_date']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12">
        <div class="content">
            <h4 style="margin-bottom: 10px;">Siste 1000 handlinger</h4>
            <table id="datatable3" data-page-length="50" data-order="[[6]]">
                <thead>
                    <tr>
                        <th>Penger</th>
                        <th>Bank penger</th>
                        <th>EXP</th>
                        <th style="width: 5%;">By</th>
                        <th style="width: 5%;">Side</th>
                        <th>Handling</th>
                        <th>Dato</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $sql = "SELECT * FROM user_log WHERE UL_acc_id = " . $_GET['id'] . " ORDER BY UL_date DESC LIMIT 1000";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <tr>
                            <td data-order="<?php echo $row['UL_money_hand'] ?>"><?php echo number($row['UL_money_hand']); ?></td>
                            <td data-order="<?php echo $row['UL_money_bank'] ?>"><?php echo number($row['UL_money_bank']); ?></td>
                            <td data-order="<?php echo $row['UL_exp'] ?>"><?php echo number($row['UL_exp']) ?></td>
                            <td><?php echo city_name($row['UL_city']) ?></td>
                            <td><?php echo $row['UL_page'] ?></td>
                            <td><?php echo $row['UL_handling'] ?></td>
                            <td data-order="<?php echo $row['UL_date'] ?>"><?php echo date_to_text($row['UL_date']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php } ?>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });

    $(document).ready(function() {
        $('#datatable2').DataTable();
    });

    $(document).ready(function() {
        $('#datatable3').DataTable();
    });
</script>
<script type="text/javascript" src="includes/datatable/datatables.min.js"></script>