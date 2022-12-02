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
    
    $orgquery = $pdo->prepare("SELECT FRM_fam_id FROM forum WHERE FRM_id = ?");
    $orgquery->execute(array($topic_id == 0 ? $id : $topic_id));
    $org_row = $orgquery->fetch(PDO::FETCH_ASSOC);
    
    $fam_id = $org_row['FRM_fam_id'];

    if($fam_id && (get_my_familyID($_SESSION['ID'], $pdo) != $fam_id || get_my_familyrole($_SESSION['ID'], $pdo) != 0)){
        echo feedback('Du har ikke tilgang!', 'error');
    } elseif(!$fam_id && ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) == 0) {
        echo feedback('Ingen tilgang!', 'error');
    } else {
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
                <h4>Rediger forum-post</h4>
            </div>
            <br>
            <div class="pad_10">
                <form method="post">
                    Tittel
                    <input name="title" type="text" value="<?php echo $title; ?>">
                    <br><br>
                    Tekst
                    <textarea name="content" rows="30"><?php echo $content; ?></textarea>
                    <input type="submit" name="edit" value="Endre post">
                </form>
            </div>
        </div>
    </div>
<?php } } else {
    echo feedback("Ugyldig ID", "fail");
} ?>