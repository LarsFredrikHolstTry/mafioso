<?php

if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT * FROM accounts WHERE ACC_id = ?');
        $stmt->bindParam(1, $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $ACC_profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ACC_profile && user_exist($ACC_profile['ACC_username'], $pdo)) {

            $stmt = $pdo->prepare('SELECT * FROM accounts_stat WHERE AS_id = ?');
            $stmt->bindParam(1, $_GET['id'], PDO::PARAM_INT);
            $stmt->execute();
            $AS_profile = $stmt->fetch(PDO::FETCH_ASSOC);

            if (half_fp($AS_profile['AS_id'], $pdo)) {
                echo feedback("Brukeren har nylig utført et angrep og har halvt forsvar i 15 minutter", "error");
            }

            switch ($AS_profile['AS_bio_bg_active']) {
                case 'url':
                    $profile_background = "url(" . $AS_profile['AS_bio_bg_image'] . ")";
                    break;
                case 'color':
                    $profile_background = $AS_profile['AS_bio_bg_color'];
                    break;
                default:
                    $profile_background = "var(--container-color)";
            }
?>

            <div class="col-9 single">
                <div class="content profile-container" style="<?php echo "background:" . $profile_background ?>">

                    <img class="profile-image" src="<?php echo $AS_profile['AS_avatar']; ?>" alt="<?php echo $ACC_profile['ACC_username'] . "'s avatar" ?>">
                    <section class="profile-info" aria-label="Profilinformasjon" <?php
                                                                                    //Nødvendig for å sørge for at brukerinformasjonen er synlig, uavhengig av fargen på bakgrunnen
                                                                                    if ($AS_profile['AS_bio_bg_active'] != NULL) {
                                                                                        echo 'style="background: hsla(0, 0%, 0%, .3);"';
                                                                                    } ?>>
                        <div class="profile-row">
                            <label for="username" class="profile-row-label">Brukernavn:</label>
                            <div id="username">
                                <a href="?side=innboks&p=ny&to=<?php echo $ACC_profile['ACC_id']; ?>">
                                    <?php echo $ACC_profile['ACC_username']; ?>
                                </a>
                            </div>
                        </div>

                        <div class="profile-row">
                            <label for="status" class="profile-row-label">Status:</label>
                            <div id="status">
                                <span style="color: <?php echo role_colors($ACC_profile['ACC_type']); ?>;"><?php echo roles($ACC_profile['ACC_type']); ?></span>
                            </div>
                        </div>

                        <div class="profile-row">
                            <label for="rank" class="profile-row-label">Rank:</label>
                            <div class="<?php echo 'rank-' . strtolower(rank_list($AS_profile['AS_rank'])) ?>"><?php echo rank_list($AS_profile['AS_rank']); ?></div>
                        </div>

                        <div class="profile-row">
                            <label for="moneyrank" class="profile-row-label">Pengerank:</label>
                            <div id="moneyrank">
                                <?php

                                echo money_rank($AS_profile['AS_money'] + $AS_profile['AS_bankmoney']);

                                ?>
                            </div>
                        </div>

                        <div class="profile-row">
                            <label for="kills" class="profile-row-label">Drap:</label>
                            <div>
                                <?php echo number(US_session_row($AS_profile['AS_id'], 'US_kills', $pdo)) ?>
                            </div>
                        </div>

                        <div class="profile-row">
                            <label for="family" class="profile-row-label">Familie:</label>
                            <div>
                                <?php
                                if (family_member_exist($ACC_profile['ACC_id'], $pdo)) {
                                    echo family_roles(get_my_familyrole($ACC_profile['ACC_id'], $pdo)) . ' i ';
                                    echo '<a href="?side=familieprofil&id=' . get_familyID(get_my_familyname($ACC_profile['ACC_id'], $pdo), $pdo) . '">' . get_my_familyname($ACC_profile['ACC_id'], $pdo) . '</a>';
                                } else {
                                    echo 'Ingen';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="profile-row">
                            <label for="mission" class="profile-row-label">Oppdrag:</label>
                            <div id="mission">
                                <?php echo $AS_profile['AS_mission'] + 1; ?>
                            </div>
                        </div>

                        <div class="profile-row">
                            <label for="registered" class="profile-row-label">Registrert: </label>
                            <div id="registered">
                                <time datetime="<?php echo date("Y-m-d H:i:s", $ACC_profile['ACC_register_date']); ?>"><?php echo date_to_text($ACC_profile['ACC_register_date']); ?></time>
                            </div>
                        </div>

                        <div class="profile-row">
                            <label for="last-active" class="profile-row-label">Sist aktiv:</label>
                            <div id="last-active">
                                <?php echo date_to_text($ACC_profile['ACC_last_active']); ?>
                            </div>
                        </div>
                    </section>

                    <h3 class="profile-header">Profil
                        <?php if (is_numeric(get_active_charm($ACC_profile['ACC_id'], $pdo))) { ?>
                            <img style="margin: 0px 0px 0px 7px; width: 25px; height: auto;" title="<?php echo charm_desc(get_active_charm($ACC_profile['ACC_id'], $pdo)); ?>" src="<?php echo charm(get_active_charm($ACC_profile['ACC_id'], $pdo)); ?>">
                        <?php } ?>
                    </h3>

                    <section class="profile-bio" aria-label="Profil bio">
                        <?php
                        $profile_text = $AS_profile['AS_bio'];
                        $profile_text = htmlspecialchars($profile_text);
                        $profile_text = showBBcodes($profile_text);
                        $profile_text = nl2br($profile_text);

                        if (strpos($profile_text, '[visitor]') !== false) {
                            $profile_text = str_replace("[visitor]", username_plain($_SESSION['ID'], $pdo), $profile_text);
                        }
                        if (strpos($profile_text, '[exp]') !== false) {
                            $profile_text = str_replace("[exp]", number($AS_profile['AS_exp']), $profile_text);
                        }
                        if (strpos($profile_text, '[money]') !== false) {
                            $profile_text = str_replace("[money]", number($AS_profile['AS_money']), $profile_text);
                        }
                        if (strpos($profile_text, '[money_bank]') !== false) {
                            $profile_text = str_replace("[money_bank]", number($AS_profile['AS_bank_money']), $profile_text);
                        }

                        echo $profile_text;
                        ?>
                    </section>

                </div>
            </div>
<?php } else {
            echo feedback("Ugyldig bruker", "blue");
        }
} ?>