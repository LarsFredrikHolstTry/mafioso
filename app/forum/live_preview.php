
<div id="preview" class="col-9 single" style="padding-top: 5px; display:none;">
    <div class="content">
        <div class="forum_ts">
            <div class="forum_name">
                <?php echo '<a style="color: ' . role_colors(ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo), $_SESSION['ID'], $pdo) . ';" href="?side=profil&id=' . $_SESSION['ID'] . '">' . ACC_username($_SESSION['ID'], $pdo) . '</a>'; ?>
            </div>
            <div class="forum_name">
                <a href="?side=profil&id=<?php echo $_SESSION['ID']; ?>">
                    <img style="max-height: 100px; max-width: 100%;" src="<?php echo AS_session_row($_SESSION['ID'], 'AS_avatar', $pdo); ?>">
                </a>
            </div>
            <div class="forum_name" style="color: grey; font-size: 12px;" title="Totale innlegg og post på forum">
                <?php if (AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) == 15) {
                    echo '<span style="color: #DD6E0F;">';
                } ?>
                <?php echo rank_list(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo)); ?>
                <?php if (AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) == 15) {
                    echo '</span>';
                } ?>
                <br>
                Innlegg: <?php echo number(total_posts($_SESSION['ID'], $pdo)); ?>
            </div>
        </div>
        <div class="forum_content">
            <span class="description">
                Postet: <?php echo date_to_text(time()); ?>
            </span>
            <div style="display: block; padding: 10px 0px 15px 0px;">
                <div id="preview_text" style="margin: 7.5 0; padding: 0 5px 0 0;">
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
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