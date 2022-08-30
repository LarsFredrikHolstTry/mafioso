<?php
if (!isset($_GET['side'])) {
    die();
}
?>
<div class="col-10 single">
    <div class="content">
        <table>
            <tr>
                <th style="width: 10px;">#</th>
                <th>Oppdrag</th>
                <th>Utbetaling</th>
            </tr>
            <?php for($i = 0; $i < $total_missions; $i++){ ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo mission($i); ?></td>
                <td><?php echo number(mission_exp($i)).' exp og '.number(mission_money($i)).' kr'; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>