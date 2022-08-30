<style>
    .tab {
        width: auto;
        overflow: hidden;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
    }

    .tablinks {
        border-top-left-radius: 1px;
        border-top-right-radius: 1px;
        color: grey;
    }

    .tablinks:hover {
        color: white;
    }

    .tabcontent {
        display: none;
    }
</style>

<?php

if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    if (!family_member_exist($_SESSION['ID'], $pdo)) {

        if (isset($_GET['nedlagt'])) {
            echo feedback("Familien ble lagt ned", "success");
        }

        if (isset($_GET['forlat'])) {
            echo feedback("Du forlat familien", "success");
        }

        if (isset($_GET['send_applicant'])) {
            if (family_exist_id($_GET['send_applicant'], $pdo)) {
                if (isset($_POST['send_soknad'])) {
                    if (isset($_POST['text']) && $_POST['text'] != null) {

                        $stmt = $pdo->prepare("SELECT count(*) FROM family_applicant WHERE FAMAP_fam_id = ? AND FAMAP_acc_id = ?");
                        $stmt->execute([$_GET['send_applicant'], $_SESSION['ID']]);
                        $count = $stmt->fetchColumn();

                        if ($count > 0) {
                            echo feedback("Du har allerde en aktiv søknad hos " . get_familyname($_GET['send_applicant'], $pdo), "fail");
                        } else {
                            $date = time();

                            if (isset($_POST['formDefence']) && $_POST['formDefence'] == "yes") {
                                $string = $_POST['text'] . "

Forsvar: " . number(AS_session_row($_SESSION['ID'], 'AS_def', $pdo)) . "
Exp: " . number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo));
                            } else {
                                $string = $_POST['text'];
                            }

                            $sql = "INSERT INTO family_applicant (FAMAP_fam_id, FAMAP_acc_id, FAMAP_text, FAMAP_date) VALUES (?,?,?,?)";
                            $pdo->prepare($sql)->execute([$_GET['send_applicant'], $_SESSION['ID'], $string, time()]);

                            echo feedback("Søknad sendt!", "success");
                        }
                    } else {
                        echo feedback("Søknadsteksten kan ikke være tom", "fail");
                    }
                }

?>

                <div class="col-6 single">
                    <div class="content">
                        <h4>Send søknad til <?php echo get_familyname($_GET['send_applicant'], $pdo) ?></h4>
                        <form method="post">
                            <p>Søknadstekst</p>
                            <textarea style="margin-bottom: 10px;" name="text" rows="10"></textarea>
                            <input type="checkbox" name="formDefence" value="yes" /> Send forsvar og exp <br>
                            <input style="margin-top: 10px;" type="submit" name="send_soknad" value="Send søknad">
                        </form>
                    </div>
                </div>

            <?php
            } else {
                echo feedback("Familie ID: " . $_GET['send_applicant'] . " eksisterer ikke", "fail");
            }
        } else {

            $create_family_price = 5000000;
            $max_families = 5;
            $max_members = 20;

            if (isset($_POST['make_family'])) {
                if ($create_family_price > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
                    echo feedback("Du har ikke nok penger til å opprette en familie", "fail");
                } else {
                    if (isset($_POST['make_family']) && $_POST['family_name'] != null) {
                        if (preg_match('/[\'^£$%&*()}!{@#~?><>,|=_+¬-]/', $_POST['family_name'])) {
                            echo feedback("Ugyldig familienavn", "fail");
                        } elseif (family_member_exist($_SESSION['ID'], $pdo)) {
                            echo feedback("Du er allerede med i en familie", "error");
                        } elseif (family_exist($_POST['family_name'], $pdo)) {
                            echo feedback("Familienavnet eksisterer allerede", "fail");
                        } elseif (total_families($pdo) >= $max_families) {
                            echo feedback("Det er allerede " . $max_families . " familier i spillet", "fail");
                        } else {
                            if ($create_family_price <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
                                $text = "opprettet familien " . $_POST['family_name'] . "";
                                $sql = "INSERT INTO last_events (LAEV_user, LAEV_text, LAEV_date) VALUES (?,?,?)";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$_SESSION['ID'], $text, time()]);

                                make_family($_POST['family_name'], $_SESSION['ID'], $pdo);
                                take_money($_SESSION['ID'], $create_family_price, $pdo);

                                header("Location: ?side=familie&opprettet");
                            }
                        }
                    } else {
                        echo feedback("Ugyldig familienavn", "fail");
                    }
                }
            }


            if (isset($_GET['remove'])) {
                $remove_id = $_GET['remove'];
                if (is_numeric($remove_id)) {
                    $query = $pdo->prepare("SELECT FAMAP_acc_id FROM family_applicant WHERE FAMAP_id = ?");
                    $query->execute(array($remove_id));
                    $family_row = $query->fetch(PDO::FETCH_ASSOC);

                    if (!$family_row) {
                        echo feedback('Ugyldig ID', 'error');
                    } else {
                        $sql = "DELETE FROM family_applicant WHERE FAMAP_id = " . $remove_id . " AND FAMAP_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                        $pdo->exec($sql);

                        echo feedback('Søknaden er fjernet', 'success');
                    }
                } else {
                    echo feedback('Ugyldig ID', 'errro');
                }
            }

            ?>

            <div class="col-4">
                <div class="content">
                    <h4 style="margin-bottom: 10px;">Opprett familie</h4>
                    <form method="post">
                        <p class="description">For å opprette familie må du ha ranken Konsulent eller høyere.
                            Prisen for å opprette familie er <?php echo number($create_family_price); ?> kr</p>
                        <input type="text" name="family_name" placeholder="Familienavn...">
                        <input type="submit" name="make_family" value="Opprett familie">
                    </form>
                </div>
            </div>
            <div class="col-8">
                <div class="content">
                    <h4 style="margin-bottom: 10px;">Oversikt over familier</h4>
                    <table>
                        <tr>
                            <!--<th>Plassering</th>-->
                            <th>Familie</th>
                            <th>Medlemmer</th>
                            <th>Sjef</th>
                            <th>Søk</th>
                        </tr>
                        <?php

                        $sql = "SELECT * FROM family LIMIT 10";
                        $stmt = $pdo->query($sql);
                        while ($row_fam = $stmt->fetch(PDO::FETCH_ASSOC)) {


                            $query = $pdo->prepare("SELECT * FROM family_member WHERE FAMMEM_fam_id=? AND FAMMEM_role = ?");
                            $query->execute(array($row_fam['FAM_id'], 0));
                            $ACC_leader = $query->fetch(PDO::FETCH_ASSOC);

                            $query = $pdo->prepare("SELECT * FROM family_member WHERE FAMMEM_fam_id=? AND FAMMEM_role = ?");
                            $query->execute(array($row_fam['FAM_id'], 1));
                            $ACC_direktor = $query->fetch(PDO::FETCH_ASSOC);

                        ?>
                            <tr>
                                <!--<td><?php // echo "#"; 
                                        ?></td>-->
                                <td><a href="?side=familieprofil&id=<?php echo $row_fam['FAM_id']; ?>"><?php echo $row_fam['FAM_name']; ?></a></td>
                                <td><?php echo total_family_members($row_fam['FAM_id'], $pdo); ?> / <?php echo $max_members; ?></td>
                                <td><?php echo ACC_username($ACC_leader['FAMMEM_acc_id'], $pdo); ?></td>
                                <td><a href="?side=familie&send_applicant=<?php echo $row_fam['FAM_id'] ?>">Send søknad</a></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="content">
                    <h4 style="margin-bottom: 10px;">Mine sendte søknader</h4>
                    <table>
                        <tr>
                            <th>Familie</th>
                            <th>Dato</th>
                            <th>Handling</th>
                        </tr>
                        <?php

                        $query = $pdo->prepare('SELECT * FROM family_applicant WHERE FAMAP_acc_id = :id');
                        $query->execute(array(':id' => $_SESSION['ID']));

                        foreach ($query as $row) { ?>
                            <tr>
                                <td><?php echo get_familyname($row['FAMAP_fam_id'], $pdo); ?></td>
                                <td><?php echo date_to_text($row['FAMAP_date']); ?></td>
                                <td><a href="?side=familie&remove=<?php echo $row['FAMAP_id']; ?>">Angre søknad</a></td>
                            </tr>
                        <?php }

                        ?>
                    </table>
                </div>
            </div>
        <?php
        }
    } else {

        if (isset($_GET['opprettet'])) {
            echo feedback("Familie ble opprettet", "success");
        }

        $mod_access = true;

        if (get_my_familyrole($_SESSION['ID'], $pdo) == 3) {
            $mod_access = false;
        }

        ?>

        <h4 style="margin: 1rem 0;">Familie: <?php echo get_my_familyname($_SESSION['ID'], $pdo); ?></h4>

        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'medlemmer')" <?php if (empty($_GET['p']) || isset($_GET['p']) && $_GET['p'] == 'mbm') { ?> id="defaultOpen" <?php } ?>>Medlemmer </button>
            <button class="tablinks" onclick="openTab(event, 'donasjon')" <?php if (isset($_GET['p']) && $_GET['p'] == 'donasjon') { ?> id="defaultOpen" <?php } ?>>Donasjon </button>
            <button class="tablinks" onclick="openTab(event, 'territorium')" <?php if (isset($_GET['p']) && $_GET['p'] == 'territorium') { ?> id="defaultOpen" <?php } ?>>Territorium</button>
            <button class="tablinks" onclick="openTab(event, 'redigerprofil')" <?php if (isset($_GET['p']) && $_GET['p'] == 'rp') { ?> id="defaultOpen" <?php } ?>>Rediger profil </button>
            <button class="tablinks" onclick="openTab(event, 'avatar')" <?php if (isset($_GET['p']) && $_GET['p'] == 'avatar') { ?> id="defaultOpen" <?php } ?>>Rediger avatar </button>
            <button class="tablinks" onclick="openTab(event, 'kulelager')" <?php if (isset($_GET['p']) && $_GET['p'] == 'kulelager') { ?> id="defaultOpen" <?php } ?>>Kulelager </button>
            <button class="tablinks" onclick="openTab(event, 'forsvar')" <?php if (isset($_GET['p']) && $_GET['p'] == 'forsvar') { ?> id="defaultOpen" <?php } ?>>Forsvar </button>
            <button class="tablinks" onclick="openTab(event, 'soknader')" <?php if (isset($_GET['p']) && $_GET['p'] == 'soknader') { ?> id="defaultOpen" <?php } ?>>Søknader
                <?php

                $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);

                if (get_my_familyrole($_SESSION['ID'], $pdo) != 3) {
                    if (total_applicants($my_family_id, $pdo) > 0) {
                        echo "<span style='color: var(--ready-color)'>(" . total_applicants($my_family_id, $pdo) . ")</span>";
                    }
                }

                ?>
            </button>
            <button class="tablinks" onclick="openTab(event, 'fellesmelding')" <?php if (isset($_GET['p']) && $_GET['p'] == 'fellesmeld') { ?> id="defaultOpen" <?php } ?>>Fellesmelding </button>
            <button class="tablinks" onclick="openTab(event, 'forlat')">Forlat</button>
        </div>

        <div id="medlemmer" class="tabcontent">
            <?php include 'app/familie/medlemmer.php'; ?>
        </div>

        <div id="donasjon" class="tabcontent">
            <?php include 'app/familie/donasjon.php'; ?>
        </div>

        <div id="territorium" class="tabcontent">
            <?php include 'app/familie/territorium.php'; ?>
        </div>

        <div id="redigerprofil" class="tabcontent">
            <?php include 'app/familie/rediger_fam_profil.php'; ?>
        </div>

        <div id="avatar" class="tabcontent">
            <?php include 'app/familie/avatar.php'; ?>
        </div>

        <div id="kulelager" class="tabcontent">
            <?php include 'app/familie/kulelager.php'; ?>
        </div>

        <div id="forsvar" class="tabcontent">
            <?php include 'app/familie/forsvar.php'; ?>
        </div>

        <div id="soknader" class="tabcontent">
            <?php include 'app/familie/soknader.php'; ?>
        </div>

        <div id="fellesmelding" class="tabcontent">
            <?php include 'app/familie/fellesmeld.php'; ?>
        </div>

        <div id="forlat" class="tabcontent">
            <?php include 'app/familie/forlat.php'; ?>
        </div>

<?php }
} ?>



<script>
    document.getElementById("defaultOpen").click();

    function openTab(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>