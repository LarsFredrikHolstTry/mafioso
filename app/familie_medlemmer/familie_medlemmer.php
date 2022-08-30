<div class="col-8 single">
    <div class="content">

<?php

if(isset($_GET['id']) && family_exist_id($_GET['id'], $pdo) && is_numeric($_GET['id'])){

    $query = $pdo->prepare("SELECT FAM_name FROM family WHERE FAM_id = ?");
    $query->execute(array($_GET['id']));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    echo '<h3>Familien: '.$row['FAM_name'].'</h3>';

    $query = $pdo->prepare('SELECT FAMMEM_acc_id, FAMMEM_role, FAMMEM_date FROM family_member WHERE FAMMEM_fam_id = :fam_id ORDER BY FAMMEM_role');
    $query->execute(array(':fam_id' => $_GET['id']));
    
    echo '<table><tr><th>Bruker</th><th>Rank</th><th>Ble medlem</th><tr>';
    foreach ($query as $row) {
        echo '<tr><td>'.ACC_username($row['FAMMEM_acc_id'], $pdo).'</td><td>'.family_roles($row['FAMMEM_role']).'</td><td>'.date_to_text($row['FAMMEM_date']).'</td></tr>';
    }
    echo '</table>';
} else {
    echo feedback($useLang->error->voidID, 'error');
}

?>

</div>
</div>