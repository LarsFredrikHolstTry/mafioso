<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $family_name = get_my_familyname($_SESSION['ID'], $pdo);

    $query = $pdo->prepare("SELECT * FROM family WHERE FAM_name=?");
    $query->execute(array($family_name));
    $FAM_row = $query->fetch(PDO::FETCH_ASSOC);


    $mod_access = true;

    if (get_my_familyrole($_SESSION['ID'], $pdo) == 3 || get_my_familyrole($_SESSION['ID'], $pdo) == 2) {
        $mod_access = false;
    }

    $role[0] = "Sjef";
    $role[1] = "Direktør";
    $role[2] = "Økonom";
    $role[3] = "Medlem";
    $role[4] = "Spark ut";

?>
    <div class="content">
        <h3 style="margin-bottom: 1rem;">Medlemmer i <?php echo $family_name; ?></h3>
        <table>
            <tr>
                <th>Medlem</th>
                <th>Rolle</th>
                <th>EXP ranket i dag</th>
                <th>Medlem fra</th>

                <?php if ($mod_access) { ?>
                    <th>Endre rolle</th>
                <?php } ?>
            </tr>
            <?php

            $sql = "
SELECT 
    family_member.FAMMEM_role, family_member.FAMMEM_acc_id, family_member.FAMMEM_role, family_member.FAMMEM_date, accounts_stat.AS_daily_exp
FROM 
    family_member 
INNER JOIN 
    accounts_stat
ON 
    family_member.FAMMEM_acc_id = accounts_stat.AS_id
WHERE 
    family_member.FAMMEM_fam_id = " . $FAM_row['FAM_id'] . "
ORDER BY 
    accounts_stat.AS_daily_exp DESC
                ";
            $stmt = $pdo->query($sql);
            while ($row_memb = $stmt->fetch(PDO::FETCH_ASSOC)) {

                if (isset($_POST[$row_memb['FAMMEM_acc_id']])) {
                    $legal = array(0, 1, 2, 3, 4);
                    if (isset($_POST['rolle']) && in_array($_POST['rolle'], $legal)) {
                        if ($row_memb['FAMMEM_role'] == 0) {
                            echo feedback("Sjefenes rolle kan ikke endres.", "fail");
                        } elseif ($_POST['rolle'] == 0 && total_family_sjef(get_my_familyID($_SESSION['ID'], $pdo), $pdo) > 0) {
                            echo feedback("Det er kun 1 som kan være sjef for en familie", "fail");
                        } elseif ($_POST['rolle'] == 1 && total_family_direktor(get_my_familyID($_SESSION['ID'], $pdo), $pdo) > 0) {
                            echo feedback("Det er kun 1 som kan være direktør for en familie", "fail");
                        } elseif ($_POST['rolle'] == 2 && total_family_okonom(get_my_familyID($_SESSION['ID'], $pdo), $pdo) > 0) {
                            echo feedback("Det er kun 1 som kan være økonom for en familie", "fail");
                        } elseif ($_POST['rolle'] == 4) {
                            $sql = "DELETE FROM family_member WHERE FAMMEM_acc_id = " . $row_memb['FAMMEM_acc_id'] . "";
                            $pdo->exec($sql);

                            header("Location: ?side=familie&p=mbm");
                        } else {
                            $sql = "UPDATE family_member SET FAMMEM_role=? WHERE FAMMEM_fam_id=? AND FAMMEM_acc_id = ?";
                            $pdo->prepare($sql)->execute([$_POST['rolle'], get_my_familyID($_SESSION['ID'], $pdo), $row_memb['FAMMEM_acc_id']]);

                            header("Location: ?side=familie&p=mbm");
                        }
                    }
                }

            ?>
                <tr>
                    <td><?php echo ACC_username($row_memb['FAMMEM_acc_id'], $pdo); ?></td>
                    <td><?php echo family_roles($row_memb['FAMMEM_role']); ?></td>
                    <td><?php echo number(AS_session_row($row_memb['FAMMEM_acc_id'], 'AS_daily_exp', $pdo)); ?></td>
                    <td><?php echo date_to_text($row_memb['FAMMEM_date']); ?></td>
                    <?php if ($mod_access) { ?><td>
                            <form method="post">
                                <select name="rolle">
                                    <option value="0"><?php echo $role[0]; ?></option>
                                    <option value="1"><?php echo $role[1]; ?></option>
                                    <option value="2"><?php echo $role[2]; ?></option>
                                    <option value="3"><?php echo $role[3]; ?></option>
                                    <option value="4"><?php echo $role[4]; ?></option>

                                </select>
                                <input type="submit" name="<?php echo $row_memb['FAMMEM_acc_id']; ?>" value="Endre">
                            </form>
                        </td><?php } ?>
                </tr>
            <?php }  ?>
        </table>
    </div>
<?php } ?>