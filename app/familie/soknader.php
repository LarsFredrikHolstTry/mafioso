<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);

    if (get_my_familyrole($_SESSION['ID'], $pdo) == 3) {
        echo feedback("Medlemmer kan ikke se eller behandle søknader", "error");
    } else {

        if (isset($_GET['avslatt'])) {
            echo feedback("Søknad ble avslått", "success");
        }

        if (isset($_GET['godtatt'])) {
            echo feedback("Søknad ble godtatt", "success");
        }

?>
        <div class="content">
            <h4 style="margin-bottom: 10px;">Søknader</h4>
            <?php
            if (isset($_GET['id'])) {

                $query = $pdo->prepare("SELECT * FROM family_applicant WHERE FAMAP_id = ?");
                $query->execute(array($_GET['id']));
                $FAM_applicant = $query->fetch(PDO::FETCH_ASSOC);

                if ($FAM_applicant['FAMAP_fam_id'] == $my_family_id) {

                    if (isset($_POST['no'])) {
                        $sql = "DELETE FROM family_applicant WHERE FAMAP_id = " . $_GET['id'] . "";
                        $pdo->exec($sql);

                        $text = "Din søknad om å bli med i " . get_familyname($FAM_applicant['FAMAP_fam_id'], $pdo) . " ble avslått";

                        if (notificationSettings('notJoinFamily', $_SESSION['ID'], $pdo)) {
                            send_notification($FAM_applicant['FAMAP_acc_id'], $text, $pdo);
                        }

                        header("Location: ?side=familie&p=soknader&avslatt");
                    }

                    if (isset($_POST['yes'])) {
                        if (total_family_members($my_family_id, $pdo) >= 20) {
                            echo feedback("Det er allerede 20 medlemmer i familien.", "fail");
                        } else {
                            $sql = "DELETE FROM family_applicant WHERE FAMAP_acc_id = " . $FAM_applicant['FAMAP_acc_id'] . "";
                            $pdo->exec($sql);

                            $sql = "INSERT INTO family_member (FAMMEM_acc_id, FAMMEM_fam_id, FAMMEM_role, FAMMEM_date) VALUES (?,?,?,?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$FAM_applicant['FAMAP_acc_id'], $my_family_id, 3, time()]);

                            $text = "Din søknad om å bli med i " . get_familyname($FAM_applicant['FAMAP_fam_id'], $pdo) . " ble godtatt";

                            if (notificationSettings('joinsFamily', $_SESSION['ID'], $pdo)) {
                                send_notification($FAM_applicant['FAMAP_acc_id'], $text, $pdo);
                            }

                            header("Location: ?side=familie&p=soknader&godtatt");
                        }
                    }

                    echo '<span>Søknad fra: ';
                    echo ACC_username($FAM_applicant['FAMAP_acc_id'], $pdo);
                    echo ' - Søknad mottat: ' . date_to_text($FAM_applicant['FAMAP_date']) . '</span>';
                    echo '<br><br>';

                    $soknad_text = $FAM_applicant['FAMAP_text'];
                    $soknad_text = htmlspecialchars($soknad_text);
                    $soknad_text = showBBcodes($soknad_text);
                    $soknad_text = nl2br($soknad_text);

                    echo $soknad_text;

            ?>
                    <form method="post">
                        <input type="submit" name="yes" value="Godta søknad">
                        <input type="submit" name="no" value="Avslå søknad">
                    </form>

                <?php

                } else {
                    echo feedback("Ugyldig søknad.", "fail");
                }
            } else {


                $stmt = $pdo->prepare("SELECT count(*) FROM family_applicant WHERE FAMAP_fam_id = ? ORDER BY FAMAP_date DESC");
                $stmt->execute([$my_family_id]);
                $count = $stmt->fetchColumn();

                if ($count == 0) {
                    echo feedback('Du har ingen ventede søknader', "blue");
                } else {

                ?>

                    <table>
                        <tr>
                            <th>Bruker</th>
                            <th>Dato</th>
                        </tr>
                        <?php

                        $sql = "SELECT * FROM family_applicant WHERE FAMAP_fam_id = $my_family_id ORDER BY FAMAP_date DESC";
                        $stmt = $pdo->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {


                        ?>
                            <tr class="cursor_hover clickable-row" data-href="?side=familie&p=soknader&id=<?php echo $row['FAMAP_id']; ?>">
                                <td><?php echo ACC_username($row['FAMAP_acc_id'], $pdo); ?></td>
                                <td><?php echo date_to_text($row['FAMAP_date']); ?></td>
                            </tr>

                        <?php } ?>
                    </table>

            <?php
                }
            }

            ?>
        </div>
<?php }
} ?>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>