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

    #background-type {
        display: grid;
        grid-template-columns: min-content min-content;
        justify-content: space-evenly;
        align-items: center;
    }

    #background-options {
        margin-top: 1.5em;
        display: grid;
        grid-template-columns: max-content auto;
        min-height: 125px;
        gap: .5em;
    }
</style>
<div class="tab">
    <button class="tablinks" onclick="openCity(event, 'hovedkvarter')" <?php if (empty($_GET)) {  ?> id="defaultOpen" <?php } ?>>Hovedkvarter</button>
    <button class="tablinks" onclick="openCity(event, 'profile')">Rediger profil</button>
    <button class="tablinks" onclick="openCity(event, 'tema')" <?php if (isset($_GET['theme'])) { ?> id="defaultOpen" <?php } ?>>Tema</button>
    <button class="tablinks" onclick="openCity(event, 'ikon')" <?php if (isset($_GET['icon'])) { ?> id="defaultOpen" <?php } ?>>Profil-ikon</button>
    <button class="tablinks" onclick="openCity(event, 'avatar')">Rediger avatar</button>
    <button class="tablinks" onclick="openCity(event, 'password')" <?php if (isset($_GET['password'])) { ?> id="defaultOpen" <?php } ?>>Endre passord</button>
    <button class="tablinks" onclick="openCity(event, 'verv')">Verv en venn</button>
    <button class="tablinks" onclick="openCity(event, 'blokker')" <?php if (isset($_GET['blocked'])) { ?> id="defaultOpen" <?php } ?>>Blokker</button>
</div>

<div id="hovedkvarter" class="tabcontent">
    <div class="col-4">
        <h3>Velkommen tilbake <?php echo ACC_username($_SESSION['ID'], $pdo); ?></h3>
        <br>
        <div class="rankbar">
            <?php if (AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) < 15) { ?>
                <span><?php echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)); ?> av <?php echo number(exp_to(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo))); ?> EXP til <b class="rank"><?php echo rank_list(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) + 1); ?> </b></span>
                <p class="description">Framgang: <?php echo round(rank_progress(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)), 2); ?>%</p>
                <p class="description">Dagens EXP: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_daily_exp', $pdo)); ?></p>
            <?php } else {
                echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)) . ' EXP';
            } ?>
            <div id="progress"></div>
        </div>
        <div style="clear: both;"></div>
        <h3 style="margin-top: 20px;">Dersom du dør</h3>
        <p class="description">
            Dersom du dør vil du få en liten boost for å opprette en ny bruker:
        </p>
        <?php if (AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) >= 5) { ?>
            <ul class="description">
                <li><?php echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo) * 0.1) ?> EXP</li>
                <li><?php echo number(250000000) ?> kroner</li>
                <li>50 poeng</li>
                <li>5 energidrikker</li>
                <li>1 hemmelig kiste</li>
                <li>M4A4 som startvåpen</li>
            </ul>

        <?php } else {
            echo feedback("Du er ikke høy nok rank for å få boost ved dødsfall. Du må være minst ranken " . rank_list(5) . " for å få boost", "error");
        } ?>
    </div>
    <div class="col-4">
        <h3>Statistikk</h3>
        <ul class="description">
            <li>EXP: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)); ?></li>

            <li>EXP i dag: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_daily_exp', $pdo)); ?></li>

            <li>Kriminalitet: <?php echo US_session_row($_SESSION['ID'], 'US_krim_v', $pdo) + US_session_row($_SESSION['ID'], 'US_krim_m', $pdo); ?> (<span style="color: #19bc63;"><?php echo US_session_row($_SESSION['ID'], 'US_krim_v', $pdo); ?></span>/<span style="color: orange;"><?php echo US_session_row($_SESSION['ID'], 'US_krim_m', $pdo); ?></span>)</li>

            <li>Biltyveri: <?php echo US_session_row($_SESSION['ID'], 'US_gta_v', $pdo) + US_session_row($_SESSION['ID'], 'US_gta_m', $pdo); ?> (<span style="color: #19bc63;"><?php echo US_session_row($_SESSION['ID'], 'US_gta_v', $pdo); ?></span>/<span style="color: orange;"><?php echo US_session_row($_SESSION['ID'], 'US_gta_m', $pdo); ?></span>)</li>

            <li>Brekk: <?php echo US_session_row($_SESSION['ID'], 'US_brekk_v', $pdo) + US_session_row($_SESSION['ID'], 'US_brekk_m', $pdo); ?> (<span style="color: #19bc63;"><?php echo US_session_row($_SESSION['ID'], 'US_brekk_v', $pdo); ?></span>/<span style="color: orange;"><?php echo US_session_row($_SESSION['ID'], 'US_brekk_m', $pdo); ?></span>)</li>

            <li>Stjel: <?php echo US_session_row($_SESSION['ID'], 'US_stjel_v', $pdo) + US_session_row($_SESSION['ID'], 'US_stjel_m', $pdo); ?> (<span style="color: #19bc63;"><?php echo US_session_row($_SESSION['ID'], 'US_stjel_v', $pdo); ?></span>/<span style="color: orange;"><?php echo US_session_row($_SESSION['ID'], 'US_stjel_m', $pdo); ?></span>)</li>

            <li>Hurtige oppdrag utført: <?php echo number(US_session_row($_SESSION['ID'], 'US_hurtig_oppdrag', $pdo)); ?></li>

            <li>Utbrytninger: <?php echo number(US_session_row($_SESSION['ID'], 'US_jail', $pdo)); ?></li>

            <?php $profit = total_sold_stocks($_SESSION['ID'], $pdo) - total_bought_stocks($_SESSION['ID'], $pdo); ?>
            <li>Aksjemarked profitt:
                <?php if ($profit < 0) { ?>
                    <span style="color: orange;"><?php echo number($profit); ?></span>
                <?php } else { ?>
                    <span style="color: #19bc63;"><?php echo number($profit); ?></span>
                <?php } ?>
                kr
            </li>

            <li>Gambling:
                <?php if (US_session_row($_SESSION['ID'], 'US_gambling', $pdo) < 0) { ?>
                    <span style="color: orange;"><?php echo number(US_session_row($_SESSION['ID'], 'US_gambling', $pdo)); ?></span>
                <?php } else { ?>
                    <span style="color: #19bc63;"><?php echo number(US_session_row($_SESSION['ID'], 'US_gambling', $pdo)); ?></span>
                <?php } ?>
                kr
            </li>

        </ul>
    </div>
    <div class="col-4">

        <h3>Dagens utfordring</h3>
        <br>
        <?php echo get_todays_utfordring($_SESSION['ID'], $pdo); ?>

    </div>
    <div style="clear: both;"></div>
</div>


<form onsubmit="updateProfile(this)" action="javascript:void(0)" id="profile" class="tabcontent">
    <?php include_once '././textarea_style.php'; ?>
    <textarea id="txtarea" class="profile-bio" style="margin-top: 0px; margin-bottom: 1em; border-top: none; border-radius: 0px;" rows="20"><?php echo AS_session_row($_SESSION['ID'], 'AS_bio', $pdo); ?></textarea>

    <fieldset id="use-custom-background">
        <legend>Bruk egendefinert bakgrunn på profilen:
            <input type="checkbox" name="profile_bg_active" id="active_bg"
            <?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_active', $pdo) != NULL ? 'checked' : ''; ?>
            onchange="toggleBackgroundSettings()">
        </legend>

        <div id="background-options">
            <fieldset id="background-type">
                <legend>Bakgrunnstype:</legend>
                <input type="radio" name="background_type" id="background_type_color" value="color"
                    <?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_active', $pdo) != 'url' ? 'checked' : ''; ?>
                    onchange="toggleBackgroundType()">
                <label for="background_type_color">Farge</label>

                <input type="radio" name="background_type" id="background_type_url" value="url"
                    <?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_active', $pdo) == 'url' ? 'checked' : ''; ?>
                    onchange="toggleBackgroundType()">
                    <label for="background_type_url">Bilde</label>
            </fieldset>
            
            <fieldset id="profile-background-color">
                <legend>Bakgrunnsfarge:</legend>
                <input type="color"
                    id="profileBgColor"
                    value="<?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_color', $pdo) ?? "#0b7ef1"; ?>">
            </fieldset>
            
            <fieldset id="profile-background-image">
                <legend>Bakgrunnsbilde (URL):</legend>
                <input type="hidden"
                    id="profileBgURL"
                    value="<?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_image', $pdo); ?>"
                    pattern="https://(imma.gr|imgur.com)/.*"
                    title="Bildelenke til imma.gr eller imgur.com">
                <p>
                    <i>
                        NB: Kun bilder opplastet på
                        <a target="_blank" href="//imma.gr">imma.gr</a> eller
                        <a target="_blank" href="//imgur.com/">imgur.com</a> er tillat
                    </i>
                </p>
            </fieldset>
        </fieldset>
    <button type="submit" id="update_profile" style="margin-top: 10px;">Oppdater profil</button>
    <input type="button" value="Forhåndsvisning" onclick="open_preview()">
    
<?php include 'live_preview_profile.php'; ?>


</form>

<template id="colorOptionsTemplate">
    
</template>
<script>
    function toggleBackgroundSettings() {
        if($('#active_bg').is(':checked')){
            $('#background-options').show();
        }
        else {
            $('#background-options').hide();
        }

        toggleBackgroundType();
    }

    function toggleBackgroundType () {
        switch($('[name=background_type]:checked').val()) {
            case 'color':
                $('#profile-background-image').hide();
                $('#profile-background-color').show();
                $('#profileBgColor').attr('type', 'color');
                $('#profileBgURL').attr('type', 'hidden');
                break;
            case 'url':
                $('#profile-background-image').show();
                $('#profile-background-color').hide();
                $('#profileBgColor').attr('type', 'hidden');
                $('#profileBgURL').attr('type', 'text');
                break;
        }
    }
    toggleBackgroundSettings();
</script>

<div id="avatar" class="tabcontent">
    <div class="col-6">
        <div id="avatar_feedback" style="display: none;"><?php echo feedback("Avataret ble endret", "success"); ?></div>
        <h4>Last opp avatar</h4>
        <p class="description">Lovlige filtyper: <b>.jpg .jpeg .png .gif</b></p>
        <input class="btn" id="sortpicture" type="file" name="sortpic" />
        <button class="btn" id="upload">Last opp avatar</button>
    </div>
    <div class="col-6">
        <div id="avatar_feedback2" style="display: none;"><?php echo feedback("Avataret ble endret", "success"); ?></div>
        <h4>Mine avatarer</h4>
        <p class="description">Under vil du se en samling av avatarer du har lastet opp på Mafioso. Dersom du ønsker å bytte til et tidligere avatar kan du trykke på ønsket avatar.</p>
        <?php

        $query = $pdo->prepare('SELECT * FROM uploaded_avatar WHERE UOA_acc_id=:id');
        $query->execute(array(':id' => $_SESSION['ID']));
        foreach ($query as $row) {

        ?>
            <img class="change_avatar" data-textval="<?php echo $row['UOA_avatar']; ?>" style="max-height: 80px; max-width: 80px; margin: 2px 2px 2px 0px;" src="<?php echo $row['UOA_avatar']; ?>">
        <?php } ?>
        <img class="change_avatar" data-textval="././img/avatar/standard_avatar.png" style="max-height: 80px; max-width: 80px; margin: 2px 2px 2px 0px;" src="././img/avatar/standard_avatar.png">

    </div>
    <div style="clear: both;"></div>
</div>

<div id="password" class="tabcontent">
    <?php

    if (isset($_GET['password'])) {
        if (isset($_GET['empty'])) {
            echo feedback("Vennligst legg inn et passord", "error");
        } elseif (isset($_GET['repeat'])) {
            echo feedback("Vennligst gjenta passordet", "error");
        } elseif (isset($_GET['six'])) {
            echo feedback("Passord må være minst 6 karakterer langt", "error");
        } elseif (isset($_GET['same'])) {
            echo feedback("Passordene må være like", "error");
        } elseif (isset($_GET['new'])) {
            echo feedback("Passordet er oppdatert!", "success");
        }
    }

    if (isset($_POST['update_password'])) {
        $password = $_POST['password'];
        $password_repeat = $_POST['password_repeat'];

        if (empty($password)) {
            header("Location: ?password&empty");
        } else if (empty($password_repeat)) {
            header("Location: ?password&repeat");
        } else if (strlen($password) < 6) {
            header("Location: ?password&six");
        } else if ($password != $password_repeat) {
            header("Location: ?password&same");
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE accounts SET ACC_password = ? WHERE ACC_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$hashed_password, $_SESSION['ID']]);

            header("Location: ?password&new");
        }
    }

    ?>
    <h4>Endre passord</h4>
    <p class="description">Passordet kan endres når du måtte ønske. Det anbefales å bruke spesial-tegn og ÆØÅ i passordet for økt sikkerhet.</p>
    <form method="post">
        <div class="col-4">
            <p>Nytt passord</p>
            <input style="width: 90%;" type="password" name="password" id="password_input" placeholder="Passord">
        </div>
        <div class="col-4">
            <p>Gjenta passord</p>
            <input style="width: 90%;" type="password" name="password_repeat" id="password_repeat" placeholder="Gjenta passord">
        </div>

        <div class="col-12">
            <input type="checkbox" onclick="myFunction()"> Vis passord
            <br><br>
            <input type="submit" name="update_password" value="Oppdater passord">
        </div>
    </form>
    <div style="clear: both;"></div>
</div>

<div id="tema" class="tabcontent">
    <h3>Tema</h3>
    <p class="description">Her har du mulighet for å endre fargetema på Mafioso. Dersom du har ønsker / forslag om farger vi kan legge til så send oss en melding på support.</p>
    <?php

    for ($i = 0; $i < count($colors); $i++) { ?>
        <div class="theme <?php if ($theme_type == $i) { ?> active_theme <?php } ?>" data-textval="<?php echo $i; ?>">
            <div class="theme_color" style="background-color: <?php echo $color_code[$i] ?>;">

            </div>
            <p <?php if ($theme_type == $i) { ?> class="active_theme_p" <?php } ?> style="margin: 0px 0px 5px 0px;"><?php echo $colors[$i]; ?></p>
        </div>
    <?php } ?>
    <div style="clear: both;"></div>
</div>
<div id="ikon" class="tabcontent">
    <h3>Ikon</h3>
    <p class="description">Har du profil-ikoner kan disse benyttes her. Profil-ikoner får du tak i via eventer eller via <a href="?side=bundles">bundles</a></p>
    <p>Mine ikoner:</p>
    <?php

    if (isset($_POST['remove_icon'])) {
        $sql = "DELETE FROM charm WHERE CH_acc_id = " . $_SESSION['ID'] . " AND CH_use = 1 LIMIT 1";
        $pdo->exec($sql);

        header("location: index.php?icon");
    }

    $statement = $pdo->prepare("SELECT CH_charm FROM charm WHERE CH_acc_id = :id");
    $statement->execute(array(':id' => $_SESSION['ID']));
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

    ?>
        <div class="icon <?php if ($row['CH_charm'] == get_active_charm($_SESSION['ID'], $pdo)) { ?> active_theme <?php } ?>" data-textval="<?php echo $row['CH_charm']; ?>">
            <div class="theme_color" style="width: 70px; height: auto;">
                <img style="width: 35px; height: auto;" src="<?php echo charm($row['CH_charm']); ?>">
            </div>
            <p <?php if ($row['CH_charm'] == get_active_charm($_SESSION['ID'], $pdo)) { ?> class="active_theme_p" <?php } ?> style="margin: 0px 0px 5px 0px;"><?php echo charm_desc($row['CH_charm']); ?></p>
        </div>
    <?php } ?>
    <div style="clear: both;"></div>
    <form method="post">
        <input type="submit" name="remove_icon" value="Slett aktivt ikon">
    </form>
</div>
<div id="verv" class="tabcontent">
    <div class="col-6">
        <h4>Verv en venn!</h4>
        <p class="description">Ved å verve en venn så vil du få poeng hver gang spilleren du vervet ranker opp!</p>
        <p>Din vervelink: <span id="copy" style="margin-left: 3px; background-color: var(--main-bg-color); padding: 7px 6px;">https://mafioso.no/registrer.php?ver=<?php echo $_SESSION['ID']; ?></span> <a class="a_as_button" id="kopiert" onclick="clickchange(); CopyToClipboard('copy');return false;" href="#">Kopier</a></p>
        <h4 style="margin: 10px 0px;">Vervede venner</h4>
        <p class="description">Under vil du se en liste over spillere du har vervet</p>
        <table>
            <tr>
                <th>Brukernavn</th>
                <th>Rank</th>
                <th>Sist aktiv</th>
            </tr>
            <?php

            $statement = $pdo->prepare("SELECT * FROM verv WHERE VERV_by = :id");
            $statement->execute(array(':id' => $_SESSION['ID']));
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

            ?>
                <tr>
                    <td><?php echo ACC_username($row['VERV_acc_id'], $pdo); ?></td>
                    <td><?php echo rank_list(AS_session_row($row['VERV_acc_id'], 'AS_rank', $pdo)); ?></td>
                    <td><?php echo date_to_text(ACC_session_row($row['VERV_acc_id'], 'ACC_last_active', $pdo)); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="col-6">
        <h4 style="margin-bottom: 10px;">Vervepremie</h4>
        <?php

        $premie[0] = 0;
        $premie[1] = 2;
        $premie[2] = 4;
        $premie[3] = 6;
        $premie[4] = 10;
        $premie[5] = 15;
        $premie[6] = 20;
        $premie[7] = 25;
        $premie[8] = 30;
        $premie[9] = 50;
        $premie[10] = 75;
        $premie[11] = 100;
        $premie[12] = 150;
        $premie[13] = 200;
        $premie[14] = 350;
        $premie[15] = 500;

        ?>
        <table>
            <?php for ($i = 1; $i < count($premie); $i++) { ?>
                <tr>
                    <td><?php echo rank_list($i); ?></td>
                    <td><?php echo $premie[$i] . " poeng "; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div style="clear: both;"></div>
</div>

<?php


if (isset($_POST['block'])) {
    if (user_exist($_POST['block_username'], $pdo)) {
        $acc_id_second = get_acc_id($_POST['block_username'], $pdo);

        $stmt = $pdo->prepare("SELECT * FROM block WHERE BL_acc_id = :id AND BL_acc_id_blocked = :id_second");
        $stmt->execute(['id' => $_SESSION['ID'], 'id_second' => $acc_id_second]);
        $row = $stmt->fetch();

        if (!$row) {
            $sql = "INSERT INTO block (BL_acc_id, BL_acc_id_blocked) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$_SESSION['ID'], $acc_id_second]);

            header("Location: ?blocked&blockedConfirm");
        } else {
            echo feedback("Du har allerede blokkert spilleren", "error");
        }
    } else {
        header("Location: ?blocked&nonExist");
    }
}

if (isset($_GET['block_remove'])) {
    $stmt = $pdo->prepare("SELECT BL_id FROM block WHERE BL_id = :id AND BL_acc_id = :acc_id");
    $stmt->execute(['id' => $_GET['block_remove'], 'acc_id' => $_SESSION['ID']]);
    $row = $stmt->fetch();

    if ($row) {
        $sql = "DELETE FROM block WHERE BL_id = " . $_GET['block_remove'] . " LIMIT 1";
        $pdo->exec($sql);

        header("Location: ?blocked&blockedRemove");
    } else {
        echo feedback("Ugyldig handling", "error");
    }
}

?>

<div id="blokker" class="tabcontent">
    <?php

    if (isset($_GET['blockedConfirm'])) {
        echo feedback("Bruker ble blokkert", "success");
    }

    if (isset($_GET['blockedRemove'])) {
        echo feedback('blokkering ble fjernet', "success");
    }

    if (isset($_GET['nonExist'])) {
        echo feedback("Brukeren eksisterer ikke", "error");
    }

    ?>
    <div class="col-6">
        <h4>Blokker spiller</h4>
        <form method="post">
            <input type="text" placeholder="Brukernavn" name="block_username">
            <input type="submit" value="Blokker" name="block">
        </form>
    </div>
    <div class="col-6">
        <h4 style="margin-bottom: 10px;">Brukere jeg har blokert</h4>
        <table>
            <tr>
                <th>Blokkert spiller</th>
                <th>Handling</th>
            </tr>
            <?php

            $stmt = $pdo->prepare('SELECT BL_id, BL_acc_id_blocked, BL_acc_id FROM block WHERE BL_acc_id = ?');
            $stmt->execute(array($_SESSION['ID']));
            foreach ($stmt as $row) {

            ?>
                <tr>
                    <td><?php echo ACC_username($row['BL_acc_id_blocked'], $pdo) ?></td>
                    <td><a href="?block_remove=<?php echo $row['BL_id']; ?>">Fjern blokkering</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div style="clear: both;"></div>
</div>

<style>
    .active_theme_p {
        color: <?php echo $color_code[$theme_type]; ?> !important;
    }

    .active_theme {
        background-color: rgba(255, 255, 255, .2);
    }

    .progress-bar {
        width: 100%;
        background-color: var(--left-container-color);
        border-radius: 1px;
    }

    .progress-bar-fill {
        display: block;
        height: 12px;
        background-color: var(--button-bg-color);
        transition: width 500ms ease-in-out;
    }
</style>

<script>
    function myFunction() {
        var x = document.getElementById("password_input");
        var y = document.getElementById("password_repeat");

        if (x.type === "password") {
            x.type = "text";
            y.type = "text";
        } else {
            x.type = "password";
            y.type = "password";

        }
    }

    document.getElementById("defaultOpen").click();

    function openCity(evt, cityName) {
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

    $('#upload').on('click', function() {
        var file_data = $('#sortpicture').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            url: 'app/hjem/avatar_post.php',
            dataType: 'text', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(php_script_response) {
                $("#avatar_feedback").fadeIn().show().delay(4000).fadeOut();
            }
        });
    });

    function updateProfile() {
        $.post("app/hjem/profil_post.php", {
            profile_text: $("#txtarea.profile-bio").val(),
            background_color: $('#profileBgColor').val(),
            background_image: $('#profileBgURL').val(),
            background_type: () => {
                if(!$('#active_bg').is(':checked')) return null;
                
                return $('[name=background_type]:checked').val();
            },
        })
        .fail(err => new Feedback(err.responseText, '#profile', 'error', 10_000))
        .done(response => new Feedback(response, '#profile', 'success', 4000)); 
    }

    $(document).ready(function() {
        $(".col-6 img").click(function() {

            var option = $(this).data("textval");
            $.post("app/hjem/avatar_edit.php", {
                    option: option
                },
                function(res, status) {
                    $("#left_avatar").attr("src", option);
                    $("#avatar_feedback2").fadeIn().show().delay(4000).fadeOut();
                }
            );
        });
    });

    $(document).ready(function() {
        $(".theme").click(function() {

            var theme = $(this).data("textval");
            $.post("app/hjem/theme_edit.php", {
                    theme: theme
                },
                function(res, status) {
                    location.href = '?theme';
                }
            );
        });
    });

    $(document).ready(function() {
        $(".icon").click(function() {

            var icon = $(this).data("textval");
            $.post("app/hjem/icon_edit.php", {
                    icon: icon
                },
                function(res, status) {
                    location.href = '?icon';
                }
            );
        });
    });

    function clickchange() {
        var ptag = document.getElementById('kopiert');
        ptag.innerHTML = 'Kopiert!';
    }

    function CopyToClipboard(id) {
        var r = document.createRange();
        r.selectNode(document.getElementById(id));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(r);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();
    }
</script>
<!-- TODO: Vurder flytting av scriptet til index.php for å kunne brukes globalt -->
<script src="js/feedback.js"></script>