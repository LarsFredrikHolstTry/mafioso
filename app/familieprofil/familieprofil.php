<?php

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM family WHERE FAM_id = ?');
    $stmt->bindParam(1, $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $family_row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$family_row){
        echo feedback('Ugyldig ID', 'error');
    } else {

?>
    <div class="col-9 single">
        <div class="content">
            <div class="col-6" style="display: flex; justify-content: center;">
                <img style="max-width: 300px; max-width: 300px" ; src="<?php echo $family_row['FAM_avatar']; ?>">
            </div>
            <div class="col-6">
                <div class="col-10">
                    <ul>
                        <li>Familie: <span style="float: right;"><?php echo $family_row['FAM_name']; ?></span></li>
                        <li>Registrert: <span style="float: right;"><?php echo date_to_text($family_row['FAM_date']); ?></span></li>
                        <br>
                        <?php for ($i = 0; $i < 3; $i++) {

                            if (get_family_members($family_row['FAM_id'], $i, $pdo)) {

                        ?>

                                <li><?php echo family_roles($i); ?>: <span style="float: right;"><a href="?side=profil&id=<?php echo get_family_members($family_row['FAM_id'], $i, $pdo); ?>"><?php echo ACC_username(get_family_members($family_row['FAM_id'], $i, $pdo), $pdo); ?></a></span></li>

                        <?php } else {
                                echo '<li>' . family_roles($i) . ': <span style="float: right;">Ingen</span></li>
';
                            }
                        } ?>
                        <li>Antall medlemmer: <a href="?side=familie_medlemmer&id=<?php echo $family_row['FAM_id'] ?>" style="float: right;"><?php echo total_family_members($family_row['FAM_id'], $pdo); ?></a></li>
                    </ul>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
    <div class="col-9 single">
        <div class="content">
            <center>
                <h3>Familieprofil</h3>
            </center>
        </div>
    </div>
    <div class="col-9 single">
        <div class="content">
            <div style="display: block; margin-bottom: 0px; overflow-wrap: break-word;">
                <div class="text">
                    <?php
                    $profile_text = $family_row['FAM_profil'];
                    $profile_text = htmlspecialchars($profile_text);
                    $profile_text = showBBcodes($profile_text);
                    $profile_text = nl2br($profile_text);

                    if (strpos($profile_text, '[visitor]') !== false) {
                        $profile_text = str_replace("[visitor]", ACC_username($ACC_profile['ACC_id'], $pdo), $profile_text);
                    }

                    echo $profile_text;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } } ?>