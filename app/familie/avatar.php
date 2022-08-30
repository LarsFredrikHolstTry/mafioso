<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $my_fam_id = get_my_familyID($_SESSION['ID'], $pdo);

    if (isset($_POST['update_avatar'])) {

        $name = $_FILES['file']['name'];
        $target_dir = "././img/avatar/avatar";
        $target_file = $target_dir . time() . "-" . basename($_FILES["file"]["name"]);

        // Select file type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Valid file extensions
        $extensions_arr = array("jpg", "jpeg", "png", "gif");

        // Check extension
        if (in_array($imageFileType, $extensions_arr)) {

            // Insert record
            $stmt = $pdo->prepare("UPDATE family SET FAM_avatar = :fam_avatar WHERE FAM_id = :fam_id");
            $stmt->bindParam(":fam_avatar", $target_file);
            $stmt->bindParam(":fam_id", $my_fam_id);
            $stmt->execute();

            // Upload file
            move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . time() . "-" . $name);

            header("Location: ?side=familie&p=avatar&new");
        } else {
            echo feedback("Ugyldig filtype", "error");
        }
    }

    if (isset($_GET['p']) && $_GET['p'] == 'avatar') {
        if (isset($_GET['new'])) {
            echo feedback("Familieavataret ble oppdatert", "success");
        }
    }

?>
    <div class="col-7 single">
        <div class="content">
            <h3 style="margin-bottom: 10px;">Endre avatar</h3>
            <p class="description">Lovlige filtyper: jpg, jpeg, png, gif</p>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="size" value="1000000">
                <input class="btn" style="float:left; width: 100%;" type="file" name="file">
                <br><br>
                <input class="btn" type="submit" name="update_avatar" style="width: 100%;" value="Last opp avatar">
            </form>
        </div>
    </div>
<?php } ?>