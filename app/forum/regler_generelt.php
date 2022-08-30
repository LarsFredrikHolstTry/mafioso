<?php
if(!isset($_GET['side'])){
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

$date = 1586428810;
$title = "Regler i generelt forum";
$creator_id = 1;
$content = "[i]På forumet er det viktig at vi respekterer hverandre og vi må dermed har noen grunnregler for hva som er greit på forumet, og hva som ikke er greit.[/i]

Merk 1. Ved brudd av en eller fler av disse reglene vil det bli utgikk enten fjerning av post / kommentar, utestengelse fra forum eller utestengelse fra spillet i sin helhet.

[size=14]§1 - Ingen spamming, reklame eller selv-reklamering i generelt forum.[/size]
Disse forumene er laget for å diskutere om selve spillet Mafioso.no. Dermed dette ikke kan respekteres kan poster bli slettet, i verstefall utestengelse fra forumet til ubestemt tid.
Ikke del linker som ikke er trygge, ikke trykk på linker du ikke stoler på og ikke del ut personlig informasjon som email addresse, telefonnummer, addresse etc.

[size=14]§2 - Ikke post frekke poster, linker eller bilder[/size]
Poster som kun er ment for å skade andre medspillere vil bli sett på som trakasserende og bli tatt ned umiddelbart

Utennom disse to generelle reglene så håper jeg dere behandler forumet med respekt ♥
";
?>

<div class="col-9 single">
    <div class="content">
        <h4 style="margin-bottom: 10px;"><?php echo $title; ?></h4>
        <div class="forum_ts">
            <div class="forum_name">
                <?php echo '<a style="color: '.role_colors(3).';" href="?side=profil&id=1">'.ACC_username($creator_id, $pdo).'</a>'; ?>
            </div>
            <div class="forum_name">
                <a href="?side=profil&id=<?php echo $creator_id; ?>">
                <img style="max-height: 100px; max-width: 100%;" src="<?php echo AS_session_row($creator_id, 'AS_avatar', $pdo); ?>">
                </a>
            </div>
        </div>
        <div class="forum_content">
            <p class="description">
            Opprettet: <?php echo date_to_text($date); ?>
            </p>
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