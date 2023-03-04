<?php include_once '././textarea_style.php'; ?>
    <textarea id="txtarea" class="profile-bio" style="margin-top: 0px; margin-bottom: 1em; border-top: none; border-radius: 0px;" rows="20"><?php echo AS_session_row($_SESSION['ID'], 'AS_bio', $pdo); ?></textarea>

    <fieldset id="use-custom-background">
        <legend>Bruk egendefinert bakgrunn på profilen:
            <input type="checkbox" name="profile_bg_active" id="active_bg" <?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_active', $pdo) != NULL ? 'checked' : ''; ?> onchange="toggleBackgroundSettings()">
        </legend>

        <div id="background-options">
            <fieldset id="background-type">
                <legend>Bakgrunnstype:</legend>
                <input type="radio" name="background_type" id="background_type_color" value="color" <?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_active', $pdo) != 'url' ? 'checked' : ''; ?> onchange="toggleBackgroundType()">
                <label for="background_type_color">Farge</label>

                <input type="radio" name="background_type" id="background_type_url" value="url" <?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_active', $pdo) == 'url' ? 'checked' : ''; ?> onchange="toggleBackgroundType()">
                <label for="background_type_url">Bilde</label>
            </fieldset>

            <fieldset id="profile-background-color">
                <legend>Bakgrunnsfarge:</legend>
                <input type="color" id="profileBgColor" value="<?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_color', $pdo) ?? "#0b7ef1"; ?>">
            </fieldset>

            <fieldset id="profile-background-image">
                <legend>Bakgrunnsbilde (URL):</legend>
                <input type="hidden" id="profileBgURL" value="<?php echo AS_session_row($_SESSION['ID'], 'AS_bio_bg_image', $pdo); ?>" pattern="https://(imma.gr|imgur.com)/.*" title="Bildelenke til imma.gr eller imgur.com">
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


    <template id="colorOptionsTemplate">

</template>
<script>
    function toggleBackgroundSettings() {
        if ($('#active_bg').is(':checked')) {
            $('#background-options').show();
        } else {
            $('#background-options').hide();
        }

        toggleBackgroundType();
    }

    function toggleBackgroundType() {
        switch ($('[name=background_type]:checked').val()) {
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