<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    function quote($id, $pdo)
    {
        $query = $pdo->prepare("SELECT FRM_content, FRM_acc_id, FRM_date FROM forum WHERE FRM_id = ?");
        $query->execute(array($id));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $text = $row['FRM_content'];
            $acc_id = $row['FRM_acc_id'];
            $date = $row['FRM_date'];

            $text = htmlspecialchars($text);
            $text = showBBcodes($text);
            $text = nl2br($text);

            if (strpos($text, '[quote') !== false) {
                $str = $text;
                $int = (int) filter_var($str, FILTER_SANITIZE_NUMBER_INT);

                $text = str_replace("[quote=" . $int . "]", " ", $text);
            }

            echo '
            <div class="quote_container">
            <div class="quote_header description">Sitat av: ' . ACC_username($acc_id, $pdo) . ' ' . date_to_text($date) . '<i style="float: right;" class="fi fi-rr-quote-right"></i> </div>
            <div class="quote_content">' . $text . '</div>
            </div>';
        }
    }

    if (isset($_GET['topic'])) {
        $topic = $_GET['topic'];

        if ($topic == "regler") {
            include_once 'regler_generelt.php';
        } elseif ($topic == "forslag") {
            include_once 'forslag_pin.php';
        } else {

            $sthandler = $pdo->prepare("SELECT FRM_id FROM forum WHERE FRM_id = :id");
            $sthandler->bindParam(':id', $topic);
            $sthandler->execute();

            if ($sthandler->rowCount() > 0) {

                $query = $pdo->prepare("SELECT * FROM forum WHERE FRM_id = ?");
                $query->execute(array($topic));
                $row = $query->fetch(PDO::FETCH_ASSOC);

                $ID_forum =             $row['FRM_id'];
                $cat =                  $row['FRM_cat'];
                $title_forum =          $row['FRM_title'];
                $content =              $row['FRM_content'];
                $date =                 $row['FRM_date'];
                $acc_id =               $row['FRM_acc_id'];
                $closed =               $row['FRM_closed'];
                $fam_id =               $row['FRM_fam_id'];

                if ($cat == 3 && $fam_id != get_my_familyID($_SESSION['ID'], $pdo)) {
                    echo feedback("Ingen tilgang", "error");
                } else {

                    if ($closed == 1) {
                        $closed = true;
                    } else {
                        $closed = false;
                    }

                    if (isset($_GET['close'])) {
                        echo feedback("Posten ble stengt", "success");
                    }

                    if (isset($_GET['open'])) {
                        echo feedback("Posten ble åpnet", "success");
                    }

                    if (isset($_POST['submit'])) {
                        if ($closed) {
                            echo feedback("Posten er stengt", "fail");
                        } else {
                            $respons = $_POST['respons'];

                            if (strlen($respons) < 10) {
                                echo feedback("Svaret ditt må inneholde minst 10 tegn.", "fail");
                            } else {
                                $sql = "INSERT INTO forum (FRM_topic_id, FRM_cat, FRM_title, FRM_content, FRM_date, FRM_acc_id) VALUES (?,?,?,?,?,?)";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$ID_forum, $cat, $title_forum, $respons, time(), $_SESSION['ID']]);
                                $date = time();

                                $sql = "UPDATE forum SET FRM_last_reply = '" . $date . "' WHERE FRM_id='" . $ID_forum . "'";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                $amnt_page = amount_of_pages($ID_forum, $pdo);

                                header("Location: ?side=forum&topic=$ID_forum&page=$amnt_page");
                            }
                        }
                    }

?>

                    <div class="col-9 single">
                        <div class="content">
                            <h4 style="text-align: center;"><?php if ($closed) {
                                                                echo "<span style='padding: 0; color: orange'>STENGT</span> - ";
                                                            }
                                                            echo $title_forum; ?></h4>
                            <div class="forum_ts">
                                <div class="forum_name">
                                    <?php echo '<a style="color: ' . role_colors(ACC_session_row($acc_id, 'ACC_type', $pdo)) . ';" href="?side=profil&id=' . $acc_id . '">' . ACC_username($acc_id, $pdo) . '</a>'; ?>
                                </div>
                                <div class="forum_name">
                                    <a href="?side=profil&id=<?php echo $acc_id; ?>">
                                        <img style="max-height: 100px; max-width: 100%;" src="<?php echo AS_session_row($acc_id, 'AS_avatar', $pdo); ?>">
                                    </a>
                                </div>
                                <div class="forum_name" style="color: grey; font-size: 12px;" title="Totale innlegg og post på forum">
                                    <?php if (AS_session_row($acc_id, 'AS_rank', $pdo) == 15) {
                                        echo '<span style="color: #DD6E0F;">';
                                    } ?>
                                    <?php echo rank_list(AS_session_row($acc_id, 'AS_rank', $pdo)); ?>
                                    <?php if (AS_session_row($acc_id, 'AS_rank', $pdo) == 15) {
                                        echo '</span>';
                                    } ?>
                                    <br>
                                    Innlegg: <?php echo number(total_posts($acc_id, $pdo)); ?>
                                </div>
                                <?php

                                if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) > 0) {

                                    if (isset($_POST['cats'])) {
                                        $new_category = $_POST['cats'];
                                        $topic_id = $_GET['topic'];

                                        if ($new_category != "false") {
                                            $sql = "UPDATE forum SET FRM_cat = ? WHERE FRM_topic_id = ?";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([$new_category, $topic_id]);

                                            $sql = "UPDATE forum SET FRM_cat = ? WHERE FRM_id = ?";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->execute([$new_category, $topic_id]);

                                            echo feedback("flyttet til " . forum_cat($new_category, $useLang), "blue");
                                        }
                                    }

                                ?>
                                    <form method="post">
                                        <label for="cats">Flytt til:</label>
                                        <select onchange="this.form.submit();" name="cats" id="cats">
                                            <option value="false"></option>
                                            <option value="0">Generelt</option>
                                            <option value="1">Salg/søknad</option>
                                            <option value="2">Off-topic</option>
                                            <option value="4">Picmaking</option>
                                            <option value="5">Hjelp</option>
                                            <option value="6">Forslag</option>
                                        </select>
                                    </form>
                                <?php }  ?>

                            </div>
                            <div class="forum_content">
                                <span class="description">
                                    Opprettet: <?php echo date_to_text($date); ?>
                                    <?php if (edited_before($ID_forum, $pdo) && $cat != 3) {
                                        echo " - Redigert " . date_to_text(edited_date($ID_forum, $pdo)) . " av " . ACC_username(edited_by($ID_forum, $pdo), $pdo);
                                    } ?>

                                    <?php if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) != 0) { ?>
                                        <a href="?side=admin&adminside=forum_rediger&id=<?php echo $ID_forum; ?>">Rediger</a> | <a href="?side=admin&adminside=forum_slett&id=<?php echo $ID_forum; ?>">Slett</a> | <?php if (!$closed) { ?><a href="?side=admin&adminside=forum_steng&id=<?php echo $ID_forum; ?>">Steng</a><?php } else { ?><a href="?side=admin&adminside=forum_apne&id=<?php echo $ID_forum; ?>">Åpne</a><?php } ?>
                                    <?php } ?>

                                    <span style="float: right;">
                                        <i class="fi fi-rr-share"></i>
                                        <i class="fi fi-rr-thumbs-up vote_up 
                                            <?php 
                                            if (has_liked($ID_forum, $_SESSION['ID'], $pdo)) {
                                                echo 'liked';
                                            } 
                                            ?>" id="<?php echo $ID_forum; ?>">
                                        </i>
                                    </span>
                                </span>
                                <div style="display: block; padding: 10px 0px 15px 0px;">
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
                                <?php
                                $amount_of_likes = get_forum_likes($ID_forum, $pdo);

                                if ($amount_of_likes > 0) { ?>
                                    <div class="description" style="display: block; padding: 3px 0px 8px 0px;">
                                        <i class="fi fi-rr-thumbs-up likes"></i>
                                        <?php

                                        $stmt_flikes = $pdo->prepare("SELECT FLIKES_acc_id FROM forum_likes WHERE FLIKES_forum_id = ? AND FLIKES_type = ?");
                                        $stmt_flikes->execute([$ID_forum, 0]);
                                        $row = $stmt_flikes->fetch();

                                        $chosen_username = $row['FLIKES_acc_id'];

                                        if ($amount_of_likes > 1) {
                                            echo ACC_username($row['FLIKES_acc_id'], $pdo) . ' og <span class="tooltip">' . ($amount_of_likes - 1) . ' andre liker';
                                            echo '<span class="tooltiptext">';

                                            $i = 0;

                                            $query = $pdo->prepare('SELECT FLIKES_acc_id FROM forum_likes WHERE FLIKES_forum_id = :id AND FLIKES_type = :type AND NOT FLIKES_acc_id = :exclude');
                                            $query->execute(array(':id' => $ID_forum, ':type' => 0, ':exclude' => $chosen_username));

                                            foreach ($query as $row) {
                                                echo ACC_username($row['FLIKES_acc_id'], $pdo) . ', ';
                                            }

                                            echo '</span></span>';
                                        } elseif ($amount_of_likes == 1) {
                                            echo ACC_username($row['FLIKES_acc_id'], $pdo) . ' liker';
                                        }

                                        ?>
                                    </div>

                                    <script>
                                        $(document).ready(function() {
                                            $(".show_more").click(function() {
                                                $("#all_likes").toggle();
                                            });
                                        });
                                    </script>
                                <?php } ?>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>

                    <?php

                    $sthandler = $pdo->prepare("SELECT FRM_id FROM forum WHERE FRM_id = :id");
                    $sthandler->bindParam(':id', $topic);
                    $sthandler->execute();

                    if ($sthandler->rowCount() > 0) {

                        $limit = 10;
                        $query = "SELECT count(*) FROM forum WHERE FRM_topic_id = $ID_forum";

                        $s = $pdo->query($query);
                        $total_results = $s->fetchColumn();
                        $total_pages = ceil($total_results / $limit);

                        if (!isset($_GET['page'])) {
                            $page = 1;
                        } else {
                            $page = $_GET['page'];
                        }

                        $starting_limit = ($page - 1) * $limit;

                        $sql = "SELECT * FROM forum WHERE FRM_topic_id = $ID_forum ORDER BY FRM_date ASC LIMIT $starting_limit, $limit";
                        $stmt = $pdo->query($sql);

                        $sthandler = $pdo->prepare("SELECT * FROM forum WHERE FRM_topic_id= :id");
                        $sthandler->bindParam(':id', $ID_forum);
                        $sthandler->execute();

                        if ($sthandler->rowCount() > 0) {
                            while ($row_forum = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $id_forum =             $row_forum['FRM_id'];
                                $cat_forum =            $row_forum['FRM_cat'];
                                $title_forum =          $row_forum['FRM_title'];
                                $content_forum =        $row_forum['FRM_content'];
                                $date_forum =           $row_forum['FRM_date'];
                                $acc_id_forum =         $row_forum['FRM_acc_id'];

                    ?>
                                <div class="col-9 single" style="padding-top: 5px;">
                                    <div class="content">
                                        <div class="forum_ts">
                                            <div class="forum_name">
                                                <?php echo '<a style="color: ' . role_colors(ACC_session_row($acc_id_forum, 'ACC_type', $pdo)) . ';" href="?side=profil&id=' . $acc_id . '">' . ACC_username($acc_id_forum, $pdo) . '</a>'; ?>
                                            </div>
                                            <div class="forum_name">
                                                <a href="?side=profil&id=<?php echo $acc_id_forum; ?>">
                                                    <img style="max-height: 100px; max-width: 100%;" src="<?php echo AS_session_row($acc_id_forum, 'AS_avatar', $pdo); ?>">
                                                </a>
                                            </div>
                                            <div class="forum_name" style="color: grey; font-size: 12px;" title="Totale innlegg og post på forum">
                                                <?php if (AS_session_row($acc_id_forum, 'AS_rank', $pdo) == 15) {
                                                    echo '<span style="color: #DD6E0F;">';
                                                } ?>
                                                <?php echo rank_list(AS_session_row($acc_id_forum, 'AS_rank', $pdo)); ?>
                                                <?php if (AS_session_row($acc_id_forum, 'AS_rank', $pdo) == 15) {
                                                    echo '</span>';
                                                } ?>
                                                <br>
                                                Innlegg: <?php echo number(total_posts($acc_id_forum, $pdo)); ?>
                                            </div>
                                        </div>
                                        <div class="forum_content">
                                            <span class="description">
                                                Postet:
                                                <?php echo date_to_text($date_forum); ?>
                                                <?php if (edited_before($id_forum, $pdo)) {
                                                    echo " - Redigert " . date_to_text(edited_date($id_forum, $pdo)) . " av " . ACC_username(edited_by($id_forum, $pdo), $pdo);
                                                } ?>

                                                <?php if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) != 0) { ?>
                                                    <a href="?side=admin&adminside=forum_rediger&id=<?php echo $id_forum; ?>">Rediger</a> | <a href="?side=admin&adminside=forum_slett&id=<?php echo $id_forum; ?>">Slett</a>
                                                <?php } ?>

                                                <span style="float: right;">
                                                    <i onclick="quote_bbcode(<?php echo $id_forum; ?>)" class="fi fi-rr-quote-right quote_btn"></i>

                                                    <i class="fi fi-rr-thumbs-up vote_up 
                                                    <?php if (has_liked($id_forum, $_SESSION['ID'], $pdo)) {
                                                        echo 'liked';
                                                    } ?>" id="<?php echo $id_forum; ?>"></i>

                                                </span>
                                                <script>
                                                    function quote_bbcode(id) {
                                                        var text = "[quote=" + id + "]";
                                                        document.forms.form_forum.respons.value = text;
                                                    }
                                                </script>
                                            </span>
                                            <div style="display: block; padding: 10px 0px 15px 0px;">
                                                <div class="text">
                                                    <?php

                                                    $htmltext = $content_forum;
                                                    $htmltext = htmlspecialchars($htmltext);
                                                    $htmltext = showBBcodes($htmltext);
                                                    $htmltext = nl2br($htmltext);

                                                    if (strpos($htmltext, '[quote') !== false) {
                                                        $str = $htmltext;
                                                        $int = (int) filter_var($str, FILTER_SANITIZE_NUMBER_INT);

                                                        $htmltext = str_replace("[quote=" . $int . "]", " ", $htmltext);

                                                        echo quote($int, $pdo);
                                                    }

                                                    echo $htmltext;

                                                    ?>
                                                </div>
                                            </div>
                                            <?php

                                            $amount_of_likes = get_forum_likes($id_forum, $pdo);

                                            if ($amount_of_likes > 0) { ?>
                                                <div class="description" style="display: block; padding: 3px 0px 8px 0px;">
                                                    <i class="fi fi-rr-thumbs-up likes"></i>
                                                    <?php

                                                    $stmt_flikes = $pdo->prepare("SELECT FLIKES_acc_id FROM forum_likes WHERE FLIKES_forum_id = ? AND FLIKES_type = ?");
                                                    $stmt_flikes->execute([$id_forum, 0]);
                                                    $row = $stmt_flikes->fetch();

                                                    $chosen_username = $row['FLIKES_acc_id'];

                                                    if ($amount_of_likes > 1) {
                                                        echo ACC_username($row['FLIKES_acc_id'], $pdo) . ' og <span class="tooltip">' . ($amount_of_likes - 1) . ' andre liker';
                                                        echo '<span class="tooltiptext">';

                                                        $i = 0;

                                                        $query = $pdo->prepare('SELECT FLIKES_acc_id FROM forum_likes WHERE FLIKES_forum_id = :id AND FLIKES_type = :type AND NOT FLIKES_acc_id = :exclude');
                                                        $query->execute(array(':id' => $id_forum, ':type' => 0, ':exclude' => $chosen_username));

                                                        foreach ($query as $row) {
                                                            echo ACC_username($row['FLIKES_acc_id'], $pdo) . ', ';
                                                        }

                                                        echo '</span></span>';
                                                    } elseif ($amount_of_likes == 1) {
                                                        echo ACC_username($row['FLIKES_acc_id'], $pdo) . ' liker';
                                                    }

                                                    ?>
                                                </div>

                                                <script>
                                                    $(document).ready(function() {
                                                        $(".show_more").click(function() {
                                                            $("#all_likes").toggle();
                                                        });
                                                    });
                                                </script>
                                            <?php } ?>
                                        </div>
                                        <div style="clear: both;"></div>
                                    </div>
                                </div>
                    <?php }
                        }
                    } ?>

                    <?php if (!$closed) { ?>
                        <div class="col-9 single" style="padding-top: 5px;">
                            <div class="content">
                                <div class="forum_ts">
                                    <div class="forum_name">
                                        <?php echo '<a style="color: ' . role_colors(ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo)) . ';" href="?side=profil&id=' . $acc_id . '">' . ACC_username($_SESSION['ID'], $pdo) . '</a>'; ?>
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
                                    <?php include_once '././textarea_style.php'; ?>
                                    <form method="post" name="form_forum">
                                        <textarea name="respons" rows="5" placeholder="Tekst..." id="txtarea" required></textarea>
                                        <input style="margin-top: 10px;" type="submit" name="submit" value="Svar">
                                        <input type="button" value="Forhåndsvisning" onclick="open_preview()">
                                    </form>
                                </div>
                                <div style="clear: both;"></div>
                            </div>
                        </div>

                        <?php include 'live_preview.php'; ?>

                    <?php } ?>
                    <div class="col-9 single" style="padding-top: 5px;">
                        <div class="content">
                            <div style="margin-top: 10px; text-align: center; color: grey; font-size: 12px;">
                                <a style="float: left;" class="page" href='<?php echo "?side=forum&topic=" . $_GET['topic'] . "&page=1"; ?>' class="link" style="padding: 5px;">Første side</a>

                                <?php for ($page = 1; $page <= $total_pages; $page++) { ?>
                                    <a class="page <?php if ($_GET['page'] == $page) {
                                                        echo 'active_page';
                                                    } ?>" href='<?php echo "?side=forum&topic=" . $_GET['topic'] . "&page=$page"; ?>' class="link" style="padding: 5px;"><?php echo $page; ?></a>
                                <?php } ?>

                                <a style="float: right;" class="page" href='<?php echo "?side=forum&topic=" . $_GET['topic'] . "&page=$total_pages"; ?>' class="link" style="padding: 5px;">Siste side</a>
                            </div>
                        </div>
                    </div>

    <?php
                }
            } else {
                echo feedback("Ugyldig ID", "fail");
            }
        }
    } else {
        echo feedback("Ugyldig ID", "fail");
    }
    ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#txtarea").bbcode({
                tag_bold: true,
                tag_italic: true,
                tag_underline: true,
                tag_link: true,
                tag_image: true,
                button_image: true
            });
            process();
        });

        $(document).ready(function() {
            $("i.vote_up").click(function() {
                var forum_id = $(this).attr('id');
                $.post("app/forum/vote_post.php", {
                        forum_id: forum_id,
                        type: 0
                    },
                    function(res, status) {
                        location.reload();
                    }
                );
            });
        });

        var $temp = $("<input>");
        var $url = $(location).attr('href');

        $('.fi-rr-share').on('click', function() {
            $("body").append($temp);
            $temp.val($url).select();
            document.execCommand("copy");
            $temp.remove();
        })

    </script>

<?php } ?>

<style>
    .likes {
        position: relative;
        top: 2px;
    }

    .vote_up,
    .quote_btn,
    .likes,
    .fi-rr-share {
        font-size: 15px;
        border-radius: 5px;
        padding: 5px 5px 2px 5px;
        transition: color .2s;
    }

    .vote_up:hover,
    .quote_btn:hover,
    .fi-rr-share:hover {
        cursor: pointer;
    }

    .vote_up:hover,
    .quote_btn:hover,
    .liked,
    .fi-rr-share:hover {
        color: rgb(184, 231, 251);
        background: rgb(7, 19, 24);
    }

    .tooltip {
        position: relative;
        display: inline-block;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        min-width: 120px;
        background-color: var(--main-bg-color);
        text-align: center;
        border-radius: var(--border-radius);
        padding: 7px;
        box-shadow: 0px 2px 5px var(--left-container-color);

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }

    .tooltip:hover {
        cursor: pointer;
        color: white;
    }
</style>