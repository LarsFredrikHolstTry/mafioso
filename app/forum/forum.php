<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    if (isset($_GET['ny_post'])) {
        include_once 'ny_post.php';
    } elseif (isset($_GET['topic'])) {
        include_once 'topic.php';
    } else {
        if (isset($_GET['cat'])) {

            $cat = $_GET['cat'];
            $legal = array(0, 1, 2, 3, 4, 5, 6);

            if ($cat == 3 && !family_member_exist($_SESSION['ID'], $pdo)) {
                echo feedback("Du er ikke med i en familie", "error");
            } else {

                if (in_array($cat, $legal)) {

?>
                    <!-- CONTENT -->
                    <div class="col-10 single">
                        <div class="content">
                            <h4 style="margin-bottom: 10px;">
                                <?php echo forum_cat($cat, $useLang) ?> 
                                <?php if ($cat == 3 && family_member_exist($_SESSION['ID'], $pdo)) {
                                    echo 'for ' . get_my_familyname($_SESSION['ID'], $pdo);
                                } ?> (<a href="?side=forum&ny_post=<?php echo $cat ?>">Ny post</a>)
                            </h4>
                            <table>
                                <tr>
                                    <th>Post</th>
                                    <th>Opprettet av</th>
                                    <th>Siste svar</th>
                                </tr>
                                <?php if ($cat == 0) { ?>
                                    <tr class="cursor_hover clickable-row" data-href="?side=forum&topic=regler">
                                        <td><span style="padding: 3px 5px; border-radius: 3px; background-color: rgba(6, 191, 252, .1); color: #06bffc;">INFO</span> Regler i generelt forum</td>
                                        <td><?php echo '<a style="color: ' . role_colors(3) . ';" href="?side=profil&id=1">' . ACC_username(1, $pdo) . '</a>'; ?></td>
                                        <td style="color: #5a5a5a;">Ugyldig å svare</td>
                                    </tr>
                                    <tr class="cursor_hover clickable-row" data-href="?side=forum&cat=5">
                                        <td><span style="padding: 3px 5px; border-radius: 3px; background-color: rgba(6, 191, 252, .1); color: #06bffc;">Underforum</span> Hjelp</td>
                                        <td><?php echo '<a style="color: ' . role_colors(3) . ';" href="?side=profil&id=1">' . ACC_username(1, $pdo) . '</a>'; ?></td>
                                        <td style="color: #5a5a5a;">Ugyldig å svare</td>
                                    </tr>
                                    <tr class="cursor_hover clickable-row" data-href="?side=forum&cat=6">
                                        <td><span style="padding: 3px 5px; border-radius: 3px; background-color: rgba(6, 191, 252, .1); color: #06bffc;">Underforum</span> Forslag</td>
                                        <td><?php echo '<a style="color: ' . role_colors(3) . ';" href="?side=profil&id=1">' . ACC_username(1, $pdo) . '</a>'; ?></td>
                                        <td style="color: #5a5a5a;">Ugyldig å svare</td>
                                    </tr>
                                <?php }
                                if ($cat == 1) { ?>
                                    <tr class="cursor_hover clickable-row" data-href="?side=forum&cat=4">
                                        <td><span style="padding: 3px 5px; border-radius: 3px; background-color: rgba(6, 191, 252, .1); color: #06bffc;">Underforum</span> Picmaking</td>
                                        <td><?php echo '<a style="color: ' . role_colors(3) . ';" href="?side=profil&id=1">' . ACC_username(1, $pdo) . '</a>'; ?></td>
                                        <td style="color: #5a5a5a;">Ugyldig å svare</td>
                                    </tr>
                                <?php }

                                if ($cat == 6) { ?>

                                    <tr class="cursor_hover clickable-row" data-href="?side=forum&topic=forslag">
                                        <td><span style="padding: 3px 5px; border-radius: 3px; background-color: rgba(6, 191, 252, .1); color: #06bffc;">INFO</span> Slik poster du forslag</td>
                                        <td><?php echo '<a style="color: ' . role_colors(3) . ';" href="?side=profil&id=1">' . ACC_username(1, $pdo) . '</a>'; ?></td>
                                        <td style="color: #5a5a5a;">Ugyldig å svare</td>
                                    </tr>

                                    <?php }

                                $i = 0;

                                $limit = 15;
                                $query = "SELECT count(*) FROM forum WHERE FRM_cat='" . $_GET['cat'] . "' AND FRM_topic_id = 0";

                                $s = $pdo->query($query);
                                $total_results = $s->fetchColumn();
                                $total_pages = ceil($total_results / $limit);

                                if (!isset($_GET['page'])) {
                                    $page = 1;
                                } else {
                                    $page = $_GET['page'];
                                }

                                $starting_limit = ($page - 1) * $limit;

                                $sql = "
                                SELECT * FROM forum WHERE FRM_cat='" . $_GET['cat'] . "' AND FRM_topic_id = 0 AND FRM_closed = 0 
                                ORDER BY FRM_last_reply DESC LIMIT $starting_limit, $limit";
                                $stmt = $pdo->query($sql);
                                while ($row_forum = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                    $id_forum =             $row_forum['FRM_id'];
                                    $cat_forum =            $row_forum['FRM_cat'];
                                    $title_forum =          $row_forum['FRM_title'];
                                    $content_forum =        $row_forum['FRM_content'];
                                    $date_forum =           $row_forum['FRM_date'];
                                    $acc_id_forum =         $row_forum['FRM_acc_id'];
                                    $last_reply_forum =     $row_forum['FRM_last_reply'];
                                    $family_id =            $row_forum['FRM_fam_id'];

                                    $query = $pdo->prepare("SELECT * FROM forum WHERE FRM_date = ?");
                                    $query->execute(array($last_reply_forum));
                                    $row_last = $query->fetch(PDO::FETCH_ASSOC);

                                    $last_reply_id = $row_last['FRM_acc_id'];

                                    if ($cat_forum == 3 && $family_id != get_my_familyID($_SESSION['ID'], $pdo)) {
                                    } else {

                                    ?>

                                        <tr class="cursor_hover clickable-row" data-href="?side=forum&topic=<?php echo $id_forum ?>&page=<?php echo amount_of_pages($id_forum, $pdo); ?>">
                                            <td><?php echo $title_forum; ?></td>
                                            <td><?php echo ACC_username($acc_id_forum, $pdo); ?> - <?php echo date_to_text($date_forum); ?></td>
                                            <td>
                                                <?php

                                                if ($last_reply_forum == $date_forum) {
                                                    echo 'Ingen svar';
                                                } else {
                                                    echo "Sist svar: ";
                                                    echo ACC_username($last_reply_id, $pdo);
                                                    echo ' - ';
                                                    echo date_to_text($last_reply_forum);
                                                }

                                                ?>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </table>
                            <div style="margin-top: 10px; text-align: center; color: grey; font-size: 12px;">
                                Side: <?php if (!isset($_GET['page']) || $_GET['page'] == null) {
                                            echo '1';
                                            echo " av ";
                                            echo $total_pages;
                                        } else {
                                            echo $_GET['page'];
                                            echo " av ";
                                            echo $total_pages;
                                        } ?>
                                <br>
                                <?php for ($page = 1; $page <= $total_pages; $page++) { ?>
                                    <a class="page" href='<?php echo "?side=forum&cat=" . $_GET['cat'] . "&page=$page"; ?>' class="link" style="padding: 5px;"><?php echo $page; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

<?php
                } else {
                    echo feedback("Ugyldig kategori", "fail");
                }
            }
        }
    }
}
?>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>