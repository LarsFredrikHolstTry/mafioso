<?php

if (!isset($_GET['side'])) {
    die();
}

?>

<div class="content">
    <h3 style="margin-bottom: 10px;">IP Sjekk</h3>
    <table id="datatable_ip_sjekk" data-order='[[2]]'>
        <thead>
            <tr>
                <th>Bruker</th>
                <th>E-post</th>
                <th>IP registrer (antall)</th>
                <th>IP sist aktiv (antall)</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if (isset($_GET['register_ip'])) {
                $stmt = $pdo->prepare('SELECT * FROM accounts WHERE ACC_ip_register = ?');
                $stmt->execute(array($_GET['register_ip']));
            } elseif (isset($_GET['latest_ip'])) {
                $stmt = $pdo->prepare('SELECT * FROM accounts WHERE ACC_ip_latest = ?');
                $stmt->execute(array($_GET['latest_ip']));
            } else {
                $stmt = $pdo->prepare('SELECT * FROM accounts ORDER BY ACC_ip_register');
                $stmt->execute();
            }

            foreach ($stmt as $row) {
                $amount_of_ip_register = amount_of_ip_register($row['ACC_ip_register'], $pdo);
                $amount_of_ip_last_active = amount_of_ip_last_active($row['ACC_ip_latest'], $pdo);
            ?>
                <tr>
                    <td><?php echo $row['ACC_username']; ?> <a href="?side=admin&adminside=spillere&id=<?php echo $row['ACC_id']; ?>">(Sjekk bruker)</a></td>
                    <td><?php echo $row['ACC_mail']; ?></td>
                    <td order="<?php echo $amount_of_ip_register; ?>"><?php echo $row['ACC_ip_register']; ?> <a href="?side=admin&adminside=ip_sjekk&register_ip=<?php echo $row['ACC_ip_register']; ?>">(<?php echo $amount_of_ip_register; ?>)</a></td>
                    <td order="<?php echo $amount_of_ip_last_active; ?>"><?php echo $row['ACC_ip_latest']; ?> <a href="?side=admin&adminside=ip_sjekk&latest_ip=<?php echo $row['ACC_ip_latest']; ?>">(<?php echo $amount_of_ip_last_active; ?>)</a></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot></tfoot>
    </table>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#datatable_ip_sjekk").DataTable();
});
</script>
<script type="text/javascript" src="includes/datatable/datatables.min.js"></script>