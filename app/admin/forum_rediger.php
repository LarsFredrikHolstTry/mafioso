<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $query = $pdo->prepare("SELECT * FROM forum WHERE FRM_id=?");
    $query->execute(array($id));
    $FRM_row = $query->fetch(PDO::FETCH_ASSOC);

    $topic_id = $FRM_row['FRM_topic_id'];
    $title = $FRM_row['FRM_title'];
    $content = $FRM_row['FRM_content'];

    if (isset($_POST['edit'])) {
        $title_new = $_POST['title'];
        $content_new = $_POST['content'];

        $arr_before = array('title' => $title, 'content' => $content);
        $arr_before = json_encode($arr_before);

        $arr_after = array('title' => $title_new, 'content' => $content_new);
        $arr_after = json_encode($arr_after);

        $sql = "INSERT INTO forum_edited (FEDIT_forum_id, FEDIT_before, FEDIT_after, FEDIT_by, FEDIT_date) VALUES (?,?,?,?,?)";
        $pdo->prepare($sql)->execute([$id, $arr_before, $arr_after, $_SESSION['ID'], time()]);

        $sql = "UPDATE forum SET FRM_title = ?, FRM_content = ? WHERE FRM_id = ?";
        $pdo->prepare($sql)->execute([$title_new, $content_new, $id]);

        echo feedback("Posten ble endret", "success");
    }

?>
    <div class="col-12">
        <div class="content">
            <div class="header">
                <span><img class="header_icon" src="img/icons/folder.png">Rediger forum-post</span>
            </div>
            <div class="pad_10">
                <form method="post">
                    Tittel (SKAL KUN ENDRES DERSOM DET ER ORIGINAL-INNLEGGET OG IKKE KOMMENTAR)
                    <input name="title" type="text" value="<?php echo $title; ?>">
                    <br>
                    Tekst
                    <textarea name="content" rows="30"><?php echo $content; ?></textarea>
                    <input type="submit" name="edit" value="Endre post">
                </form>
            </div>
        </div>
    </div>
<?php } else {
    echo feedback("Ugyldig ID", "fail");
} ?>