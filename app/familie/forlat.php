<?php

$my_family_id = get_my_familyID($_SESSION['ID'], $pdo);

if (isset($_POST['submit'])) {
    if (get_my_familyrole($_SESSION['ID'], $pdo) == 0) {
        $sql = "DELETE FROM family WHERE FAM_id = $my_family_id";
        $pdo->exec($sql);

        $sql = "DELETE FROM family_member WHERE FAMMEM_fam_id = " . $my_family_id . "";
        $pdo->exec($sql);

        $sql = "DELETE FROM territorium WHERE TE_family_id = " . $my_family_id . "";
        $pdo->exec($sql);

        header("Location: ?side=familie&nedlagt");
    } else {
        $sql = "DELETE FROM family_member WHERE FAMMEM_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
        $pdo->exec($sql);

        header("Location: ?side=familie&forlat");
    }
}

?>
<div class="col-7 single">
    <div class="content">
        <h4 style="margin: 0 0 1rem 0;">Forlat familie</h4>
        <?php

        if (get_my_familyrole($_SESSION['ID'], $pdo) == 0) {
            echo feedback('Du er eier av denne familien, dersom du velger å forlate den vil den bli lagt ned og alle brukerene vil være uten familie. Alt av forsvar, kuler og territorium vil annuleres.', "blue");
        } else {
            echo '<p class="description">Er du sikker på at du vil forlate familien din?</p>';
        }

        ?>
        <form method="post" style="margin: 1rem 0 0 0;">
            <input type="submit" name="submit" value="Forlat familie">
        </form>
    </div>
</div>