<?php
if (!isset($_GET['side']) || $ACC_session_row['ACC_type'] != 3) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    if (get_my_familyrole($_SESSION['ID'], $pdo) == 3) {
        echo feedback("Medlemmer har ikke tilgang til forsvarssiden.", "fail");
    } else {

        $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);
        $my_family_money = get_my_familybank($my_family_id, $pdo);

        $price = 10000;
        $price_pr_for = $price;

        if (isset($_POST['post'])) {
            if (isset($_POST['family'])) {
                $family_name_attack = $_POST['family'];
                $family_id_attack = get_familyID($family_name_attack, $pdo);

                if (get_my_familyname($_SESSION['ID'], $pdo) == $family_name_attack) {
                    if (family_exist($family_name_attack, $pdo)) {
                    } else {
                        echo feedback("Familien " . $family_name_attack . " eksisterer ikke.", "fail");
                    }
                } else {
                    echo feedback("Du kan ikke angripe din egen familie.", "fail");
                }
            }
        }

        if (isset($_GET['kjop'])) {
            if (is_numeric($_GET['kjop'])) {
                echo feedback("Du har kjøpt " . number($_GET['kjop']), "success");
            } else {
                echo feedback("Ugyldig input", "error");
            }
        }


?>
        <div class="col-6 single">
            <div class="content">
                <div class="header">
                    <span><img class="header_icon" src="img/icons/shield_add.png">Familieforsvar</span>
                </div>
                <div class="col-12">
                    <a href="?side=familie"><img class="header_icon" src="img/icons/arrow_left.png"> Tilbake til familiepanel</a>
                </div>
                <div class="pad_10">
                    <img src="img/action/familieforsvar.png" style="max-width: 100%; display: block; margin-left: auto; margin-right: auto;"><br>
                    <h3>Antall kuler i familien: <span style="color: #c5c5c5;"><?php echo number(get_family_bullets($my_family_id, $pdo)); ?></span></h3>
                    Hvilken familie ønsker du å angripe?
                    <br><br>
                    <div style="margin: auto; width: 80%;">
                        <form method="post">
                            <input type="text" style="width: 50%;" name="family" placeholder="Familienavn">
                            <input type="text" style="width: 49%;" name="bullet_amt" placeholder="Antall kuler">
                            <input type="submit" style="width: 100%;" name="post" value="Angrip">
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php }
} ?>
<script>
    number_space("#number");
</script>