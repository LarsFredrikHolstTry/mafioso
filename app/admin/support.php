<?php

if (!isset($_GET['side'])) {
    die();
}

if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) == 0) {
    echo feedback('Ingen tilgang', "error");
} else {

?><?php

    $status[0] = "<span style='color: orange'>Ubesvart</span>";
    $status[1] = "<span style='color: green'>Besvart</span>";

    if (isset($_GET['mark_as_read'])) {
        $sql = "UPDATE support SET SP_status = '1' WHERE SP_id='" . $_GET['mark_as_read'] . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        echo feedback("Valgt support er markert som lest!", "success");
    }

    if (isset($_GET['id'])) {

        $sql = "SELECT * FROM support WHERE SP_id = " . $_GET['id'] . "";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {



            if (isset($_POST['answer'])) {

                $to = $row['SP_from'];
                $title = "SUPPORT SVAR: " . $row['SP_text'];;
                $text = htmlspecialchars($_POST['text']);

                $pm_ID = uniqid();
                $time = time();

                send_pm_main($pm_ID, $_SESSION['ID'], $to, $title, $pdo);
                send_pm_text($pm_ID, $_SESSION['ID'], $text, $pdo);
                set_pm_new($pm_ID, $_SESSION['ID'], $to, $pdo);

                $sql_update = "UPDATE support SET SP_status = '1' WHERE SP_id='" . $_GET['id'] . "'";
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->execute();

                echo feedback("Support sendt!", "success");
            }

    ?>

<form method="post">
    <div class="content">
        <h4>Tittel: <?php echo $row['SP_title']; ?></h4>
        Fra: <?php echo ACC_username($row['SP_from'], $pdo); ?><br>
        Dato: <?php echo date_to_text($row['SP_date']) ?>
        <textarea rows="22" name="text" id="txtarea">
<?php echo $row['SP_text']; ?>


---------------------------------------------

Hei, <?php echo username_plain($row['SP_from'], $pdo); ?>


**SVAR HER**

Ha en ellers fin dag videre, med vennlig hilsen <?php echo username_plain($_SESSION['ID'], $pdo); ?>
            </textarea>
        <input type="submit" name="answer" value="svar">
    </div>
</form>

<?php
        }
    } else {

?>

<div class="content">
    <h4 style="margin-bottom: 10px;">Support</h4>
    <table>
        <tr>
            <th>#ID</th>
            <th>Tittel</th>
            <th>Fra</th>
            <th>Status</th>
            <th>Dato</th>
            <th>Merk som besvart</th>
        </tr>
        <?php

        $sql = "SELECT * FROM support WHERE SP_status = 0 ORDER BY SP_date DESC";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        ?>
            <tr class="cursor_hover clickable-row" data-href="?side=admin&adminside=support&id=<?php echo $row['SP_id']; ?>">
                <td><?php echo $row['SP_id']; ?></td>
                <td><?php echo $row['SP_title']; ?></td>
                <td><?php echo ACC_username($row['SP_from'], $pdo); ?></td>
                <td><?php echo $status[$row['SP_status']]; ?></td>
                <td><?php echo date_to_text($row['SP_date']) ?></td>
                <td><a href="?side=admin&adminside=support&mark_as_read=<?php echo $row['SP_id']; ?>">Merk som besvart</a></td>
            </tr>
        <?php } ?>
    </table>
    <?php if (total_support_unread($pdo) == 0) {
            echo "<center style='margin: 15 0; color: orange;'>Ingen support</center>";
        } ?>
</div>
<?php } ?>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>

<?php } ?>