<div class="col-8 single">
    <div class="content">
        <h3 style="margin-bottom: 10px;">Siste 20 handlinger</h3>
        
        <table id="datatable_last_events" data-order="[[1]]" data-page-length="20" data-paging="false">
            <thead>
                <tr>
                    <th>Hendelse</th>
                    <th>Dato</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                
                
                    $statement = $pdo->prepare("SELECT * FROM last_events ORDER BY LAEV_date DESC LIMIT 20");
                    $statement->execute();
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                
                
                ?>
                <tr>
                    <td><?php echo '<a href="?side=profil&id='.$row['LAEV_user'].'">'.ACC_username($row['LAEV_user'], $pdo)."</a> ".$row['LAEV_text']; ?></td>
                    <td data-sort="<?php echo $row['LAEV_date']; ?>"><?php echo date_to_text($row['LAEV_date']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#datatable_last_events").DataTable();
});
</script>
<script type="text/javascript" src="includes/datatable/datatables.min.js"></script>