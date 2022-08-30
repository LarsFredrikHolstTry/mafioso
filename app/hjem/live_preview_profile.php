
<?php

    switch(AS_session_row($_SESSION['ID'], 'AS_bio_bg_active', $pdo)) {
        case 'url':  
            $profile_background = "url(" . AS_session_row($_SESSION['ID'], 'AS_bio_bg_image', $pdo) . ")";
            break;
        case 'color':
            $profile_background = AS_session_row($_SESSION['ID'], 'AS_bio_bg_color', $pdo);
            break;
        default:
            $profile_background = "var(--container-color)";
    }

?>

<div id="preview" class="col-9 single" style="margin-top: 20px; display:none;">
    <div class="content profile-container" style="<?php echo "background:" . $profile_background ?>">

        <img class="profile-image"
            src="<?php echo AS_session_row($_SESSION['ID'], 'AS_avatar', $pdo); ?>"
            alt="<?php echo ACC_session_row($_SESSION['ID'], 'ACC_username', $pdo) . "'s avatar" ?>">
        <section class="profile-info" aria-label="Profilinformasjon"
            <?php
            if (AS_session_row($_SESSION['ID'], 'AS_bio_bg_active', $pdo) != NULL) {
                echo 'style="background: hsla(0, 0%, 0%, .3);"';
            } ?>
        >
            <div class="profile-row">
                <label for="username" class="profile-row-label">Brukernavn:</label>
                <div id="username">
                    <a href="?side=innboks&p=ny&to=<?php echo ACC_session_row($_SESSION['ID'], 'ACC_id', $pdo); ?>">
                        <?php echo ACC_session_row($_SESSION['ID'], 'ACC_username', $pdo); ?>
                    </a>
                </div>
            </div>

            <div class="profile-row">
                <label for="status" class="profile-row-label">Status:</label>
                <div id="status">
                    <span style="color: <?php echo role_colors(ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo)); ?>;"><?php echo roles(ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo)); ?></span>
                </div>
            </div>

            <div class="profile-row">
                <label for="rank" class="profile-row-label">Rank:</label>
                <div class="<?php echo 'rank-' . strtolower(rank_list(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo))) ?>"><?php echo rank_list(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo)); ?></div>
            </div>

            <div class="profile-row">
                <label for="moneyrank" class="profile-row-label">Pengerank:</label>
                <div id="moneyrank">
                    <?php
                    
                    echo money_rank(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) + AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo)); 
                    
                    ?>
                </div>
            </div>

            <div class="profile-row">
                <label for="kills" class="profile-row-label">Drap:</label>
                <div>
                    <?php echo number(US_session_row(AS_session_row($_SESSION['ID'], 'AS_id', $pdo), 'US_kills', $pdo)) ?>
                </div>
            </div>

            <div class="profile-row">
                <label for="family" class="profile-row-label">Familie:</label>
                <div>
                    <?php
                    if (family_member_exist(ACC_session_row($_SESSION['ID'], 'ACC_id', $pdo), $pdo)) {
                        echo family_roles(get_my_familyrole(ACC_session_row($_SESSION['ID'], 'ACC_id', $pdo), $pdo)) . ' i ';
                        echo '<a href="?side=familieprofil&id=' . get_familyID(get_my_familyname(ACC_session_row($_SESSION['ID'], 'ACC_id', $pdo), $pdo), $pdo) . '">' . get_my_familyname(ACC_session_row($_SESSION['ID'], 'ACC_id', $pdo), $pdo) . '</a>';
                    } else {
                        echo 'Ingen';
                    }
                    ?>
                </div>
            </div>

            <div class="profile-row">
                <label for="mission" class="profile-row-label">Oppdrag:</label>
                <div id="mission">
                    <?php echo AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) + 1; ?>
                </div>
            </div>

            <div class="profile-row">
                <label for="registered" class="profile-row-label">Registrert: </label>
                <div id="registered">
                    <time datetime="<?php echo date("Y-m-d H:i:s", ACC_session_row($_SESSION['ID'], 'ACC_register_date', $pdo)); ?>"><?php echo date_to_text(ACC_session_row($_SESSION['ID'], 'ACC_register_date', $pdo)); ?></time>
                </div>
            </div>
            
            <div class="profile-row">
                <label for="last-active" class="profile-row-label">Sist aktiv:</label>
                <div id="last-active">
                    <?php echo date_to_text(ACC_session_row($_SESSION['ID'], 'ACC_last_active', $pdo)); ?>
                </div>
            </div>
        </section>

        <h3 class="profile-header">Profil
            <?php if (is_numeric(get_active_charm(ACC_session_row($_SESSION['ID'], 'ACC_id', $pdo), $pdo))) { ?>
                <img style="margin: 0px 0px 0px 7px; width: 25px; height: auto;" title="<?php echo charm_desc(get_active_charm(ACC_session_row($_SESSION['ID'], 'ACC_id', $pdo), $pdo)); ?>" src="<?php echo charm(get_active_charm(ACC_session_row($_SESSION['ID'], 'ACC_id', $pdo), $pdo)); ?>">
            <?php } ?>
        </h3>
        
        <section class="profile-bio" id="preview_text" aria-label="Profil bio">
        </section>
        
    </div>
</div>

<script src='includes/jquery.bbcode.js' type='text/javascript'></script>
<script type="text/javascript">

function open_preview() {
   document.getElementById('preview').style.display = "block";
}

$(document).ready(function(){
$("#txtarea").bbcode({tag_bold:true,tag_italic:true,tag_underline:true,tag_link:true,tag_image:true,button_image:true});
process();
});

var bbcode="";
function process(){
    if (bbcode != $("#txtarea").val()){
        bbcode = $("#txtarea").val();
        $.get('includes/bb_code.php',{
            bbcode: bbcode
        },
        function(txt){
            $("#preview_text").html(txt);
        })

    }
    setTimeout("process()", 0);
}

</script>