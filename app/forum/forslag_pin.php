<?php
if(!isset($_GET['side'])){
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

$date = 1597601471;
$title = "Slik poster du forslag";
$creator_id = 1;
$content = "Hei,

Har du et forslag, en idé o.l.? Da må du følge disse retningslinjene.

Bruk denne malen når du poster forslag, bruker du ikke malen er det stor sannsynlighet for at posten blir slettet/forum ban.

[size=14][b]Hva er mitt forslag?[/b][/size]
Mitt forslag er..

[size=14][b]Hvilke fordeler vil dette forslaget ha for spillet sin del?[/b][/size]
Fordelene med mitt forslag er..

[size=14][b]Hvilken negative sider vil dette forslaget ha for spillet sin del?[/b][/size]
De negative sidene ved mitt forslag er..


Med vennlig hilsen,
Ledelsen
";
?>

<div class="col-9 single">
    <div class="content">
        <h4 style="margin-bottom: 10px;"><?php echo $title; ?></h4>
        <div class="forum_ts">
            <div class="forum_name">
                <?php echo '<a style="color: '.role_colors(3).';" href="?side=profil&id=1">'.ACC_username(1, $pdo).'</a>'; ?>
            </div>
            <div class="forum_name">
                <a href="?side=profil&id=<?php echo $creator_id; ?>">
                <img style="max-height: 100px; max-width: 100%;" src="<?php echo AS_session_row($creator_id, 'AS_avatar', $pdo); ?>">
                </a>
            </div>
        </div>
        <div class="forum_content">
            <p class="description">Opprettet: <?php echo date_to_text($date); ?></p>
            <div style="display: block; padding: 3px 0px 8px 0px;">
                <div class="text">
                    <?php 

                        $htmltext = $content;
                        $htmltext = htmlspecialchars($htmltext);
                        $htmltext = showBBcodes($htmltext);
                        $htmltext = nl2br($htmltext);

                        echo $htmltext;

                    ?>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>

<?php } ?>