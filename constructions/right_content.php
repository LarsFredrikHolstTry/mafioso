<div class="right_content margin_bottom">
    <h4>Siste handlinger<a href="?side=siste_handlinger" class="new_page_alert">Åpne i ny side</a></h4>
    <ul>
        <?php

        $statement = $pdo->prepare("SELECT * FROM last_events ORDER BY LAEV_date DESC LIMIT 5");
        $statement->execute();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

        ?>
            <li><?php echo '<a href="?side=profil&id=' . $row['LAEV_user'] . '">' . ACC_username($row['LAEV_user'], $pdo) . "</a> " . $row['LAEV_text']; ?><br><span class="ago"><?php echo date_to_text($row['LAEV_date']); ?></span></li>

        <?php } ?>
    </ul>
</div>
<div class="right_content margin_bottom">
    <h4>Forum <a href="?side=forum_oversikt" class="new_page_alert">Se alle innlegg</a></h4>
    <ul>
        <?php

        for ($i = 0; $i < 7; $i++) {
            if ($i == 3 && !family_member_exist($_SESSION['ID'], $pdo)) {
            } else {
                if ($i == 3) {
                    echo '<a href="?side=forum&cat=' . $i . '"><li>' . forum_cat($i, $useLang) . ' for ' . get_my_familyname($_SESSION['ID'], $pdo) . '</li></a>';
                } else {
                    echo '<a href="?side=forum&cat=' . $i . '"><li>' . forum_cat($i, $useLang) . '</li></a>';
                }
            }
        }

        ?>
    </ul>
</div>
<div class="right_content margin_bottom" style="margin-bottom: 150px;">
    <h4>Annet</h4>
    <div class="col-6">
        <ul>
            <a href="index.php">
                <li>Hovedkvarter</li>
            </a>
            <a href="?side=nyheter">
                <li>Nyheter</li>
            </a>
            <a href="?side=statistikk">
                <li>Statistikk</li>
            </a>
            <a href="?side=wiki">
                <li>Wiki</li>
            </a>
            <a href="?side=support">
                <li>Support</li>
            </a>
            <a href="loggut.php">
                <li>Logg ut</li>
            </a>
        </ul>
    </div>
    <div class="col-6">
        <ul>
            <a href="?side=konk">
                <li>Konkurranser</li>
            </a>
            <a href="?side=poeng">
                <li>Poeng</li>
            </a>
            <a target="_blank" href="https://discord.com/invite/YD9Pxyu">
                <li>Discord</li>
            </a>
            <a target="_blank" href="https://www.facebook.com/mafiosonorge">
                <li>Facebook</li>
            </a>
            <?php if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) >= 1) {
                echo '<a href="?side=admin"><li>adminpanel</li></a>';
            } ?>
        </ul>
    </div>
    <div style="clear: both;"></div>
</div>
</div>