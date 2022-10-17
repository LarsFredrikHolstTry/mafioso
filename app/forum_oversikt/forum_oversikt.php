<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $desc[0] = "Alt som omhandler spillet skal under denne kategorien.";
    $desc[1] = "Alt innen salg og søknad skal inn her.";
    $desc[2] = "Off-topic er ett forum for alt mulig.";
    $desc[3] = "Familieforumet er for de med familier.";
    $desc[4] = "På picmaking kan du legge ut bilder for salg eller søke.";
    $desc[5] = "På hjelp kan du spør om hjelp som er spill-relatert.";
    $desc[6] = "Forslags-posten brukes for å legge ut forslag til å forbedre spillet.";

    $forum_cat_desc[0] = $useLang->forum->generalDesc;
    $forum_cat_desc[1] = $useLang->forum->sellDesc;
    $forum_cat_desc[2] = $useLang->forum->offTopicDesc;
    $forum_cat_desc[3] = $useLang->forum->familyDesc;

    $forum_cat_desc[4] = $useLang->forum->picmakingDesc;
    $forum_cat_desc[5] = $useLang->forum->helpDesc;
    $forum_cat_desc[6] = $useLang->forum->suggestionsDesc;

?>

    <div class="col-10 single">
        <div class="content">
            <h4 style="margin-bottom: 10px;">50 siste innlegg</h4>
            <table>
                <tr>
                    <th>Emne</th>
                    <th>Kategori</th>
                    <th>Opprettet av</th>
                    <th>Svart av</th>
                </tr>

                <?php

                $sql = "SELECT * FROM forum WHERE FRM_topic_id = 0 AND FRM_closed = 0 AND NOT FRM_cat = 3 ORDER BY FRM_last_reply DESC LIMIT 50";
                $stmt = $pdo->query($sql);
                while ($row_forum = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $id_forum =             $row_forum['FRM_id'];
                    $date_forum =           $row_forum['FRM_date'];
                    $last_reply_forum =     $row_forum['FRM_last_reply'];

                    $query = $pdo->prepare("SELECT * FROM forum WHERE FRM_date = ?");
                    $query->execute(array($last_reply_forum));
                    $row_last = $query->fetch(PDO::FETCH_ASSOC);

                    $last_reply_id = $row_last['FRM_acc_id'];

                ?>
                    <tr class="cursor_hover clickable-row" data-href="?side=forum&topic=<?php echo $id_forum ?>&page=<?php echo amount_of_pages($id_forum, $pdo); ?>">
                        <td><?php echo $row_forum['FRM_title']; ?></td>
                        <td><a href="?side=forum&cat=<?php echo $row_forum['FRM_cat']; ?>"><?php echo forum_cat($row_forum['FRM_cat'], $useLang); ?></a></td>
                        <td><?php echo ACC_username($row_forum['FRM_acc_id'], $pdo); ?>
                        <td><?php

                            if ($last_reply_forum == $date_forum) {
                                echo 'Ingen svar';
                            } else {
                                echo "Sist svar: ";
                                echo '<a href="?side=profil&id=' . $last_reply_id . '">' . ACC_username($last_reply_id, $pdo) . '</a>';
                                echo ' - ';
                                echo date_to_text($last_reply_forum);
                            }

                            ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <?php for ($i = 0; $i < 7; $i++) { ?>
            <div class="col-3">
                <div class="content">
                    <a href="?side=forum&cat=<?php echo $i ?>">
                        <h4 style="margin-bottom: 10px;"><?php echo forum_cat($i, $useLang); ?></h4>
                    </a>
                    <a class="description" href="?side=forum&cat=<?php echo $i ?>">
                        <?= $forum_cat_desc[$i]; ?>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });
    </script>
<?php } ?>