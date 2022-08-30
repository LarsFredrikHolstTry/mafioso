<div class="col-8 single">
    <div class="content">
        <h4>Varsler</h4>
        <table id="datatable" data-order="[[1]]" class="display" style="padding-top: 10px;">
            <thead>
                <tr>
                    <th>Varsel</th>
                    <th style="width: 20%;">Dato</th>
                </tr>
            </thead>
            <tbody>
                <?php                

                $statement = $pdo->prepare("SELECT * FROM notification WHERE NO_acc_id = :id ORDER BY NO_date DESC");
                $statement->execute(array(':id' => $_SESSION['ID']));
                while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $text = $row['NO_text'];
                    $date = $row['NO_date'];
                ?>
                    <tr>
                        <td><?php echo $text ?></td>
                        <td data-order="<?php echo $date ?>"><?php echo date_to_text($date) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
    <script>

$(document).ready(function() {
    $('#datatable').DataTable();
} );

</script>
<script type="text/javascript" src="includes/datatable/datatables.min.js"></script>

<?php 

$read = 1;

$sql = "UPDATE notification SET NO_new = ? WHERE NO_acc_id = ? ";
$stmt= $pdo->prepare($sql);
$stmt->execute([$read, $_SESSION['ID']]);

?>