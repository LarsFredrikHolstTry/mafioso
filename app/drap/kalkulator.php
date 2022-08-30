<?php

if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $weapon_add[0] = 1;
    $weapon_add[1] = 2;
    $weapon_add[2] = 4;
    $weapon_add[3] = 7;
    $weapon_add[4] = 9;
    $weapon_add[5] = 11;
    $weapon_add[6] = 13;
    $weapon_add[7] = 16;
    $weapon_add[8] = 20;
    $weapon_add[9] = 24;
    $weapon_add[10] = 27;
    $weapon_add[11] = 29;
    $weapon_add[12] = 31;
    $weapon_add[13] = 33;
    $weapon_add[14] = 35;
    $weapon_add[15] = 36;
    $weapon_add[16] = 37;
    $weapon_add[17] = 50;
    $weapon_add[18] = 75;
    $weapon_add[19] = 100;

    if (isset($_POST['submit'])) {

        $exp = remove_space($_POST['exp']);
        $forsvar = remove_space($_POST['forsvar']);

        if (is_numeric($exp) && is_numeric($forsvar)) {
            if (isset($_POST['weapon']) && $_POST['weapon'] == 'null') {
                $weapon = AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo);
            } else {
                $weapon = $_POST['weapon'];
            }

            $exp = $exp * 100;
            $total_points = $exp + $forsvar;

            // Antall kuler som trengs for å drepe
            $kulekrav = ($total_points / points_taken_pr_bullet($weapon)) * 3.55;
            $kulekrav = ($kulekrav / 100);

            $take_health = 0;

            for ($i = 0; $take_health <= 100; $i++) {
                // Hvor mye helse som skal fjernes
                $take_health = $i / $kulekrav * $weapon_add[$weapon];
            }

            echo feedback("Antall kuler som trengs for å ta ned " . number($forsvar) . " forsvar og " . number($exp / 100) . " exp med " . weapons($weapon) . " er: " . number($i), "blue");
        } else {
            echo feedback("Ugyldig input", "fail");
        }
    }

?>
    <div class="col-6 single">
        <div class="content">
            <h3>Kule-kalkulator</h3>
            <form method="post">
                <div class="col-4">
                    <div class="pad_5">
                        <p>Antall forsvar</p>
                        <input id="number" style="width: 90%" type="text" name="forsvar" placeholder="Antall forsvar">
                    </div>
                </div>
                <div class="col-4">
                    <div class="pad_5">
                        <p>Antall EXP</p>
                        <input id="number2" style="width: 90%" type="text" name="exp" placeholder="Antall exp">
                    </div>
                </div>
                <div class="col-4">
                    <div class="pad_5">
                        <p>Velg våpen</p>
                        <div class="custom-select" style="width:100%;">
                            <select style="width: 100%;" name="weapon" required>
                                <option value="null">Mitt våpen</option>
                                <?php for ($i = 0; $i < count($weapon_add); $i++) { ?>
                                    <option value="<?php echo $i; ?>">
                                        <?php echo weapons($i); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <input type="submit" name="submit" value="Regn ut">
                </div>
                <p class="description">
                    Ditt våpen <?php echo weapons(AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo)); ?> tar <?php echo $weapon_add[AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo)]; ?> i boost. <?php if (AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo) < 16) { ?> Ditt neste våpen tar <?php echo $weapon_add[AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo) + 1] . " i boost ";
                                                                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                                                                    echo 'Du har det beste våpenet';
                                                                                                                                                                                                                                                                                                } ?>
                </p>
            </form>
        </div>
    </div>

    <script>
        number_space("#number");
        number_space("#number2");
    </script>
    <script src="js/select_custom.js"></script>
<?php } ?>