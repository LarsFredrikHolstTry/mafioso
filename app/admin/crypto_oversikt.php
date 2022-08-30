<?php

if (!isset($_GET['side'])) {
    die();
}


?>

<div class="content">
    <table id="datatable_crypto_overview" data-sort="[[2]]" data-page-length="9999999">
        <thead>
            <tr>
                <th>Brukernavn</th>
                <th>Krypto</th>
                <th style="text-align: right">Antall</th>
                <th style="text-align: right">Verdi</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $stmt = $pdo->prepare('
            SELECT 
                crypto_user.CRU_amount, crypto_user.CRU_acc_id, crypto_user.CRU_crypto
            FROM 
                crypto_user
            INNER JOIN
                accounts
            ON
                crypto_user.CRU_acc_id = accounts.ACC_id
            WHERE NOT
                accounts.ACC_type IN (4, 5)
            ORDER BY 
                CRU_acc_id DESC');
            $stmt->execute(array());

            foreach ($stmt as $row) {

                $query = $pdo->prepare("SELECT * FROM supported_crypto WHERE SUC_id = ?");
                $query->execute(array($row['CRU_crypto']));
                $row_supported = $query->fetch(PDO::FETCH_ASSOC);

            ?>
                <tr>
                    <td><?php echo ACC_username($row['CRU_acc_id'], $pdo); ?></td>
                    <td data-sort="<?php echo $row_supported['SUC_name'] ?? 'Ukjent kryptovaluta'; ?>">
                        <?php echo $row_supported['SUC_name'] ?? 'Ukjent kryptovaluta'; ?>
                    </td>
                    <td style="text-align: right" data-sort="<?php echo $row['CRU_amount'] ?? 0; ?>">
                        <?php echo number($row['CRU_amount'] ?? 0); ?>
                    </td>
                    <td style="text-align: right" data-sort="<?php echo (($row_supported['SUC_price'] ?? 0) * ($row['CRU_amount'] ?? 0)); ?>">
                        <?php echo number(($row_supported['SUC_price'] ?? 0) * ($row['CRU_amount'] ?? 0)); ?> kr
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr style="background: var(--table-th-bg)">
                <th colspan="2">Totalsum:</th>
                <td style="text-align: right"></td>
                <td style="text-align: right"></td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
let d, dy;
$(document).ready(function(){
    $("#datatable_crypto_overview").DataTable( {
        "footerCallback": function( tfoot, data, start, end, display ) {
            var api = this.api();

            //Regn ut totalsum for antall
            $( api.column(2).footer() ).html(
                api.column(2, {page: 'current'}).data().reduce( function ( a, b ) {
                    return parseFloat(a) + parseFloat(b.replaceAll(' ', ''));
                }, 0 )
                .toLocaleString('no-NB')
            );

            //Regn ut totalsum for verdi
            $( api.column(3).footer() ).html(
                api.column(3, {page: 'current'}).data().reduce( function ( a, b ) {
                    return parseFloat(a) + parseFloat(b.replaceAll(' ', ''));
                }, 0 )
                .toLocaleString('no-NB') + ' kr'
            );
        }
    })
});
</script>
<script type="text/javascript" src="includes/datatable/datatables.min.js"></script>