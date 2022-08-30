<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    if (isset($_GET['p'])) {
        if ($_GET['p'] == "ny") {
            include_once 'ny_melding.php';
        } else {
            echo feedback("Ugyldig side", "fail");
        }
    } else {
        if (!isset($_GET['id'])) {

?>
            <div class="col-9 single">
                <div class="content">
                    <h3 style="margin-bottom: 10px;">Innboks</h3>
                    <a href="?side=innboks&p=ny" class="a_as_button_secondary">Ny melding</a>
                    <table id="datatable" data-order='[[2]]'>
                    <thead>
                        <tr>
                            <th>Tittel</th>
                            <th>Samtale med</th>
                            <th>Sist melding</th>
                        </tr>
                    </thead>
                        <?php

                        $sql = "SELECT * FROM pm WHERE PM_acc_id_from = " . $_SESSION['ID'] . " OR PM_acc_id_to = " . $_SESSION['ID'] . " ORDER BY PM_date DESC";
                        $stmt = $pdo->query($sql);
                        while ($row_postboks = $stmt->fetch(PDO::FETCH_ASSOC)) {

                            $query = $pdo->prepare("SELECT * FROM pm_new WHERE PMN_pmid = ?");
                            $query->execute(array($row_postboks['PM_pmid']));
                            $row_new = $query->fetch(PDO::FETCH_ASSOC);

                            $acc_id1 =      $row_new['PMN_acc_id1'];
                            $acc_id1_new =  $row_new['PMN_acc_id1_new'];

                            $acc_id2 =      $row_new['PMN_acc_id2'];
                            $acc_id2_new =  $row_new['PMN_acc_id2_new'];

                            $unread = false;

                            if ($acc_id1 == $_SESSION['ID'] && $acc_id1_new > 0) {
                                $unread = true;
                            } elseif ($acc_id2 == $_SESSION['ID'] && $acc_id2_new > 0) {
                                $unread = true;
                            }

                        ?>
                        <tbody>
                            <tr class="cursor_hover clickable-row" data-href="?side=innboks&id=<?php echo $row_postboks['PM_pmid']; ?>">
                                <td>
                                    <?php
                                    if ($unread) {
                                        echo '<span style="color: #009fe3">NY! </span>';
                                    }

                                    if (strlen($row_postboks['PM_title']) > 45) {
                                        echo substr($row_postboks['PM_title'], 0, 45) . '...';
                                    } else {
                                        echo $row_postboks['PM_title'];
                                    }

                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($row_postboks['PM_acc_id_to'] == $_SESSION['ID']) {
                                        echo '<a href="?side=profil&id=' . $row_postboks['PM_acc_id_from'] . '">' . ACC_username($row_postboks['PM_acc_id_from'], $pdo) . '</a>';
                                    } else {
                                        echo '<a href="?side=profil&id=' . $row_postboks['PM_acc_id_to'] . '">' . ACC_username($row_postboks['PM_acc_id_to'], $pdo) . '</a>';
                                    }
                                    ?>
                                </td>

                                <td data-order="<?php echo $row_postboks['PM_date'] ?>"><?php echo date_to_text($row_postboks['PM_date']); ?></td>
                            </tr>
                        </tbody>

                        <?php } ?>
                    </table>
                </div>
            </div>
            <?php } else {

            $id = $_GET['id'];

            $query = $pdo->prepare("SELECT * FROM pm WHERE PM_pmid = ?");
            $query->execute(array($id));
            $row_pm_title = $query->fetch(PDO::FETCH_ASSOC);

            if (!pm_exist($id, $pdo)) {
                echo feedback("Ugyldig id", "fail");
            } else {


                if (isset($_GET['answer'])) {
                    echo feedback("Meldingen ble sendt", "success");
                }

            ?>
                <div class="col-8 single">
                    <div class="content">
                        <h4>Tittel:
                            <?php

                            if (strlen($row_pm_title['PM_title']) > 45) {
                                echo substr($row_pm_title['PM_title'], 0, 45) . '...';
                            } else {
                                echo $row_pm_title['PM_title'];
                            }

                            ?>
                        </h4>
                        <p><a href="?side=innboks">Tilbake til innboks</a></p>
                        <?php

                        if (isset($_POST['answer_pm'])) {
                            $answer_text =  $_POST['answer_text'];
                            $answer_pm =    $_POST['answer_pm'];

                            $date = time();
                            $one = 1;

                            $sql = "INSERT INTO pm_text (PMT_pmid, PMT_acc_id, PMT_text, PMT_date) VALUES (?,?,?,?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$id, $_SESSION['ID'], $answer_text, $date]);

                            $sql_pm_new = "SELECT * FROM pm_new WHERE PMN_pmid = '" . $id . "'";
                            $stmt_pm_new = $pdo->query($sql_pm_new);
                            while ($row_pm_new = $stmt_pm_new->fetch(PDO::FETCH_ASSOC)) {
                                if ($row_pm_new['PMN_acc_id1'] != $_SESSION['ID']) {
                                    $sql = "UPDATE pm_new SET PMN_acc_id1_new = ? WHERE PMN_pmid = ?";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([$one, $id]);
                                } elseif ($row_pm_new['PMN_acc_id2'] != $_SESSION['ID']) {
                                    $sql = "UPDATE pm_new SET PMN_acc_id2_new = ? WHERE PMN_pmid = ?";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([$one, $id]);
                                }
                            }

                            $sql = "UPDATE pm SET PM_date = ? WHERE PM_pmid = ?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$date, $id]);

                            header("Location: ?side=innboks&id=" . $id . "&answer");
                        }

                        if ($row_pm_title['PM_acc_id_to'] == $_SESSION['ID']) {
                            $acc_id_second_for_block = $row_pm_title['PM_acc_id_from'];
                        } else {
                            $acc_id_second_for_block = $row_pm_title['PM_acc_id_to'];
                        }

                        if (strpos($row_pm_title['PM_title'], 'SUPPORT SVAR') !== false) {
                            echo feedback("Du kan ikke svare på support. Send ny support om du har flere spørsmål.", "blue");
                        } elseif (blocked($_SESSION['ID'], $acc_id_second_for_block, $pdo)) {
                            echo feedback("Spilleren har enten blokkert deg, eller så har du blokkert spilleren.", "error");
                        } else { ?>
                            <span class="description">Samtale med:
                                <?php
                                if ($row_pm_title['PM_acc_id_to'] == $_SESSION['ID']) {
                                    echo ACC_username($row_pm_title['PM_acc_id_from'], $pdo);
                                } else {
                                    echo ACC_username($row_pm_title['PM_acc_id_to'], $pdo);
                                }
                                ?></span>
                            <form method="post">
                                <?php include_once '././textarea_style.php'; ?>
                                <textarea name="answer_text" style="margin-top: 0px; border-top: none; border-radius: 0px;" id="txtarea" rows="3" wrap="nowrap" required></textarea>
                                <input style="margin: 0;" type="submit" name="answer_pm" value="Svar">
                            </form>
                        <?php } ?>
                        <?php

                        $sql_pm = "SELECT * FROM pm_text WHERE PMT_pmid = '" . $id . "' ORDER BY PMT_date DESC";
                        $stmt_pm = $pdo->query($sql_pm);
                        while ($row_pm = $stmt_pm->fetch(PDO::FETCH_ASSOC)) {

                            $sql_pm_new_update = "SELECT * FROM pm_new WHERE PMN_pmid = '" . $id . "'";
                            $stmt_pm_new_update = $pdo->query($sql_pm_new_update);
                            while ($row_pm_new_update = $stmt_pm_new_update->fetch(PDO::FETCH_ASSOC)) {
                                if ($row_pm_new_update['PMN_acc_id1'] == $_SESSION['ID'] && $row_pm_new_update['PMN_acc_id1_new'] > 0) {
                                    $sql_mark_as_read = "UPDATE pm_new SET PMN_acc_id1_new = ? WHERE PMN_pmid = ?";
                                    $stmt_mark_as_read = $pdo->prepare($sql_mark_as_read);
                                    $stmt_mark_as_read->execute([0, $id]);
                                } elseif ($row_pm_new_update['PMN_acc_id2'] == $_SESSION['ID'] && $row_pm_new_update['PMN_acc_id2_new'] > 0) {
                                    $sql_mark_as_read = "UPDATE pm_new SET PMN_acc_id2_new = ? WHERE PMN_pmid = ?";
                                    $stmt_mark_as_read = $pdo->prepare($sql_mark_as_read);
                                    $stmt_mark_as_read->execute([0, $id]);
                                }
                            }

                        ?>
                            <p class="description" style="margin-bottom: 0px;">
                                <?php
                                if ($row_pm['PMT_acc_id'] == $_SESSION['ID']) {
                                    echo ACC_username($_SESSION['ID'], $pdo);
                                    echo " - ";
                                    echo date_to_text($row_pm['PMT_date']);
                                } elseif ($row_pm['PMT_acc_id'] != $_SESSION['ID']) {
                                    echo ACC_username($row_pm['PMT_acc_id'], $pdo);
                                    echo " - ";
                                    echo date_to_text($row_pm['PMT_date']);
                                }
                                ?>
                            </p>
                            <div style="padding-bottom: 15px; word-wrap: break-word; white-space: pre-line;">
                                <?php
                                $PMT_text = $row_pm['PMT_text'];
                                $PMT_text = htmlspecialchars($PMT_text);
                                $PMT_text = showBBcodes($PMT_text);
                                echo $PMT_text;
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
<?php }
        }
    }
} ?>

<script type="text/javascript">

    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });

    $(document).ready(function() {
        $('#datatable').DataTable();
    });

</script>
<script type="text/javascript" src="includes/datatable/datatables.min.js"></script>