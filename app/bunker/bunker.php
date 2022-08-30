<?php

if (isset($_GET['bunker_status'])) {
    if ($_GET['bunker_status'] == 'in') {

        $query = $pdo->prepare("SELECT BUNCHO_bunkerID FROM bunker_chosen WHERE BUNCHO_city = ? AND BUNCHO_acc_id = ?");
        $query->execute(array(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $_SESSION['ID']));
        $private_bunker_chosen = $query->fetch(PDO::FETCH_ASSOC);

        if ($private_bunker_chosen) {
            $query = $pdo->prepare("SELECT BUNOWN_acc_id FROM bunker_owner WHERE BUNOWN_id = ?");
            $query->execute(array($private_bunker_chosen['BUNCHO_bunkerID']));
            $private_bunker_owner = $query->fetch(PDO::FETCH_ASSOC);

            $owner_string = str_replace(array('{owner}', '{city}'), array(ACC_username($private_bunker_owner['BUNOWN_acc_id'], $pdo), city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo))), $useLang->bunker->enteredBunkerOwner);
        } else {
            $owner_string = '';
        }

        echo feedback($useLang->bunker->enteredBunkerInfo . $owner_string, 'blue');
    } elseif ($_GET['bunker_status'] == 'out') {
        echo feedback($useLang->bunker->leftBunker, 'blue');
    } elseif ($_GET['bunker_status'] == 'notEnoughMoney') {
        echo feedback($useLang->bunker->notEnoughMoney, 'error');
    }
}

?>

<div class="col-7 single">
    <div class="content">
        <img class="action_image" src="img/action/actions/bunker.png">
        <p class="description"><?php echo str_replace('{time}', date_to_text(time_left_in_bunker($_SESSION['ID'], $pdo)), $useLang->bunker->inActiveBunker); ?></p>
        <center>
        <form method="post">
            <input type="submit" name="bunker_out" value="<?php echo $useLang->bunker->leaveBunkerAction ?>">
        </form>
        </center>
    </div>
</div>