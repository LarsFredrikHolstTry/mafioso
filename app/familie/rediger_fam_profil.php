<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    if (get_my_familyrole($_SESSION['ID'], $pdo) == 3) {
        echo feedback("Medlemmer kan ikke endre på familieprofilen.", "fail");
    } else {

        $family_name = get_my_familyname($_SESSION['ID'], $pdo);

        $query = $pdo->prepare("SELECT * FROM family WHERE FAM_name=?");
        $query->execute(array($family_name));
        $FAM_row = $query->fetch(PDO::FETCH_ASSOC);

        if (isset($_POST['update_profile'])) {
            $profile_text = $_POST['profile_text'];

            $stmt = $pdo->prepare("UPDATE family SET FAM_profil = :fam_pro WHERE FAM_id = :fam_id");
            $stmt->bindParam(":fam_pro", $profile_text);
            $stmt->bindParam(":fam_id", $FAM_row['FAM_id']);
            $stmt->execute();

            header("Location: ?side=familie&p=rp&new");
        }

        if (isset($_GET['p']) && $_GET['p'] == 'rp') {
            if (isset($_GET['new'])) {
                echo feedback("Familieprofilen ble oppdatert", "success");
            }
        }

?>
        <div class="content">
            <h4 style="margin-bottom: 10px;">Rediger familieprofil</h4>

            <?php include_once '././textarea_style.php'; ?>
            <form method="post">
                <textarea name="profile_text" style="width: 100%; height: 390px;" id="txtarea"><?php echo $FAM_row['FAM_profil']; ?></textarea>
                <input type="submit" name="update_profile" value="Oppdater profil">
            </form>
        </div>
<?php }
} ?>