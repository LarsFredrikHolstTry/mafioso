<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $query = $pdo->prepare("SELECT * FROM forum WHERE FRM_id=?");
    $query->execute(array($id));
    $FRM_row = $query->fetch(PDO::FETCH_ASSOC);

    $frm_id = $FRM_row['FRM_id'];
    $topic_id = $FRM_row['FRM_topic_id'];
    $title = $FRM_row['FRM_title'];
    $content = $FRM_row['FRM_content'];

    if (isset($_POST['steng'])) {
        $sql = "UPDATE forum SET FRM_closed = 1 WHERE FRM_id='" . $frm_id . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $page = amount_of_pages($frm_id, $pdo);

        header("Location: ?side=forum&topic=$frm_id&page=$page&close");
    }

?>
    <div class="content">
        <h4>Rediger forum-post</h4>
        <div class="pad_10">
            <span style="color: orange;">Ønsker du å stenge denne tråden?</span>
            <form method="post">
                <table>
                    <tr>
                        <th>Tittel:</th>
                        <th>Tekst</th>
                    </tr>
                    <tr>
                        <td><?php echo $title; ?></td>
                        <td><?php echo $content; ?></td>
                    </tr>
                </table>

                <input type="submit" name="steng" value="Steng post">
            </form>
        </div>
    </div>
<?php } else {
    echo feedback("Ugyldig ID", "fail");
}
