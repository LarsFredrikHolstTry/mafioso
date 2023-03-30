<?php

include 'check_everytime.php';
$diamondHeistEnd = $diamondEnd - time();

function seconds2human($seconds)
{
    $dt1 = new DateTime("@0");
    $dt2 = new DateTime("@$seconds");
    return $dt1->diff($dt2)->format('%ad %ht %im %ss');
}


?>

<div class="top_bar">
    <div class="upper">
        <div class="logo">
            <?php if(!$isEaster){
               echo '<img src="img/header.png">';
            } else {
                echo '<img src="img/easter_logo.png">';
            }
            ?>
        </div>
        <div class="rankbar">
            <center>
                <span>
                    <?php
                    if (AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) < 15) {
                        echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)); ?> av
                        <?php echo number(exp_to(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo))); ?> EXP til
                        <b class="rank">
                        <?php echo rank_list(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) + 1);
                    } else {
                        echo number(AS_session_row($_SESSION['ID'], 'AS_exp', $pdo)) . ' EXP';
                    }
                        ?>
                        </b></span>
                <div style="margin-bottom: 5px;" id="progress"></div>

                <?php

                $utrop = newest_shout($pdo);
                $utrop_user = newest_shout_user($pdo);
                $date = newest_shout_date($pdo);

                ?>
                <span class="description">
                    <?php echo '<a href="?side=utrop">Utrop</a> fra ' . ACC_username($utrop_user, $pdo) . ' 
                    ' . $utrop . ' 
                    ' . date_to_text($date); ?>
                </span>

            </center>
        </div>
        <div class="date_and_alert">
            <div class="alert">
                <a href="?side=innboks">
                    <div class="new_msg 
                    <?php if (unread_pm($_SESSION['ID'], $pdo) > 0) {
                        echo "blink";
                    } ?>">
                        <i class="fi fi-rr-envelope"></i>
                    </div>
                </a>
                <a href="#" class="new_alert_container">
                    <div class="new_alert <?php if (new_notifications($_SESSION['ID'], $pdo) > 0) { ?> alert_unread <?php } ?>">
                        <i class="fi fi-rr-bell"></i>
                    </div>
                </a>
                <div class="alerts" tabindex="-1">
                    <h3>Varsler <a href="?side=varsel" class="new_page_alert">Åpne i ny side</a></h3>
                    <span class="description">Du har <?php echo new_notifications($_SESSION['ID'], $pdo); ?> uleste varsler</span>
                    <?php

                    if (total_notifications($_SESSION['ID'], $pdo) == 0) {
                        echo '<br>';
                        echo feedback("Du har ingen varsler", "blue");
                    } else {

                        $statement = $pdo->prepare("SELECT * FROM notification WHERE NO_acc_id = :id ORDER BY NO_date DESC LIMIT 10");
                        $statement->execute(array(':id' => $_SESSION['ID']));
                        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                            <ul>
                                <li><?php if ($row['NO_new'] == 0) { ?>
                                        <span class="new">NY - </span>
                                    <?php }
                                    echo $row['NO_text']; ?><br><span class="ago"><?php echo date_to_text($row['NO_date']); ?></span>
                                </li>
                            </ul>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <?php

    $stmt_ = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
    $stmt_->execute(array(
        ':cd_id' => $_SESSION['ID']
    ));
    $row_ = $stmt_->fetch(PDO::FETCH_ASSOC);

    $time_left_rc_ = $row_['CD_rc'] - time();
    $time_left_race_ = $row_['CD_race'] - time();
    $time_left_krim_ = $row_['CD_crime'] - time();
    $time_left_gta_ = $row_['CD_gta'] - time();
    $time_left_brekk_ = $row_['CD_brekk'] - time();
    $time_left_stjel_ = $row_['CD_steal'] - time();
    $time_left_event_ = $row_['CD_event'] - time();

    ?>

    <div class="lower">

        <div style="<?php
                    if ($time_left_event_ <= 0) {
                        echo 'background-color: var(--ready-color);';
                    } else {
                        echo 'background-color: var(--not-ready-color);';
                    }
                    ?>" id="event_timer" class="action_container">
            <a href="?side=pengeinnkreving"></a>
            <div class="action_icon">
                <img src="img/topHeaderIcons/pengeinnkreving.svg">
            </div>
            <div class="action_text">
                <span>Pengeinnkreving</span><br>
                <?php

                if ($time_left_event_ <= 0) {
                    echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                } else {
                    echo '<span class="cooldown_top_bar" id="countdowntimer_event">' . $time_left_event_ . 's</span>';
                ?> <script>
                        timer(<?php echo $time_left_event_; ?>, "countdowntimer_event");
                    </script>
                <?php
                }
                ?>
            </div>
        </div>

        <div style="<?php
                    if ($time_left_rc_ <= 0 || $time_left_race_ <= 0) {
                        echo 'background-color: var(--ready-color);';
                    } else {
                        echo 'background-color: var(--not-ready-color);';
                    }
                    ?>" id="rc_timer" class="action_container">
            <a href="?side=rc"></a>
            <div class="action_icon">
                <img src="img/topHeaderIcons/streetrace.svg">
            </div>
            <div class="action_text">
                <span>Streetrace</span><br>
                <?php

                if ($time_left_rc_ <= 0 || $time_left_race_ <= 0) {
                    echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                } else {
                    if ($time_left_rc_ < $time_left_race_) {
                        echo '<span class="cooldown_top_bar" id="countdowntimer_rc">';
                        echo $time_left_rc_;
                        echo 's</span>';
                ?> <script>
                            timer(<?php echo $time_left_rc_; ?>, "countdowntimer_rc");
                        </script> <?php
                                } else {
                                    echo '<span class="cooldown_top_bar" id="countdowntimer_race">';
                                    echo $time_left_race_;
                                    echo 's</span>';
                                    ?> <script>
                            timer(<?php echo $time_left_race_; ?>, "countdowntimer_race");
                        </script>
                <?php
                                }
                            }
                ?>
            </div>
        </div>

        <div style="<?php
                    if ($time_left_krim_ <= 0) {
                        echo 'background-color: var(--ready-color);';
                    } else {
                        echo 'background-color: var(--not-ready-color);';
                    }
                    ?>" id="krim_timer" class="action_container">
            <a href="?side=kriminalitet"></a>
            <div class="action_icon">
                <img src="img/topHeaderIcons/kriminalitet.svg">
            </div>
            <div class="action_text">
                <span>Kriminalitet</span><br>
                <?php

                if ($time_left_krim_ <= 0) {
                    echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                } else {
                    echo '<span class="cooldown_top_bar" id="countdowntimer_krim">' . $time_left_krim_ . 's</span>';
                ?> <script>
                        timer(<?php echo $time_left_krim_; ?>, "countdowntimer_krim");
                    </script>
                <?php
                }
                ?>
            </div>
        </div>

        <div style="<?php
                    if ($time_left_gta_ <= 0) {
                        echo 'background-color: var(--ready-color);';
                    } else {
                        echo 'background-color: var(--not-ready-color);';
                    }
                    ?>" id="gta_timer" class="action_container">
            <a href="?side=biltyveri"></a>
            <div class="action_icon">
                <img src="img/topHeaderIcons/biltyveri.svg">
            </div>
            <div class="action_text">
                <span>Biltyveri</span><br>
                <?php

                if ($time_left_gta_ <= 0) {
                    echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                } else {

                    echo '<span class="cooldown_top_bar" id="countdowntimer_gta">';
                    echo $time_left_gta_;
                    echo 's</span>';
                ?> <script>
                        timer(<?php echo $time_left_gta_; ?>, "countdowntimer_gta");
                    </script>
                <?php
                }
                ?>
            </div>
        </div>

        <div style="<?php
                    if ($time_left_brekk_ <= 0) {
                        echo 'background-color: var(--ready-color);';
                    } else {
                        echo 'background-color: var(--not-ready-color);';
                    }
                    ?>" id="brekk_timer" class="action_container">
            <a href="?side=brekk"></a>
            <div class="action_icon">
                <img src="img/topHeaderIcons/brekk.svg">
            </div>
            <div class="action_text">
                <span>Brekk</span><br>
                <?php

                if ($time_left_brekk_ <= 0) {
                    echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                } else {

                    echo '<span class="cooldown_top_bar" id="countdowntimer_brekk">';
                    echo $time_left_brekk_;
                    echo 's</span>';
                ?> <script>
                        timer(<?php echo $time_left_brekk_; ?>, "countdowntimer_brekk");
                    </script>
                <?php
                }
                ?>
            </div>
        </div>
        <div style="<?php
                    if ($time_left_stjel_ <= 0) {
                        echo 'background-color: var(--ready-color);';
                    } else {
                        echo 'background-color: var(--not-ready-color);';
                    }
                    ?>" id="stjel_timer" class="action_container">
            <a href="?side=stjel"></a>
            <div class="action_icon">
                <img src="img/topHeaderIcons/stjel.svg">
            </div>
            <div class="action_text">
                <span>Stjel</span><br>
                <?php

                if ($time_left_stjel_ <= 0) {
                    echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                } else {

                    echo '<span class="cooldown_top_bar" id="countdowntimer_stjel">';
                    echo $time_left_stjel_;
                    echo 's</span>';
                ?> <script>
                        timer(<?php echo $time_left_stjel_; ?>, "countdowntimer_stjel");
                    </script>
                <?php
                }
                ?>
            </div>
        </div>
        <div <?php if ($side == "fengsel") {
                    echo 'style="background-color: rgba(255,255,255,.1);"';
                } ?> class="action_container">
            <a href="?side=fengsel"></a>
            <div class="action_icon">
                <img src="img/topHeaderIcons/fengsel.svg">
            </div>
            <div class="action_text">
                <span style="color: white;">Fengsel</span><br>
                <span class="timeleft"><span id="people_amount_in_jail">
                        <?php
                        if (people_in_jail($pdo) > 0) {
                            echo people_in_jail($pdo);
                        } else {
                            echo "0";
                        } ?>
                    </span>
                    <?php if (people_in_jail($pdo) == 1) {
                        echo " spiller";
                    } elseif (people_in_jail($pdo) > 1 || people_in_jail($pdo) == 0) {
                        echo " spillere";
                    } ?></span>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
<style>
    <?php

    if (AS_session_row($_SESSION['ID'], 'AS_rank', $pdo) < 15) {
        $rank_progress = rank_progress(AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo));
    } else {
        $rank_progress = 100;
    }

    if ($rank_progress > 100) {
        $rank_progress = 100;
    }

    ?>#progress:after {
        width: <?php echo $rank_progress . '%'; ?>;
    }
</style>

<script>
    $(document).ready(function() {
        $(".new_alert").click(function() {
            $('.alerts').toggle();
            $.post("constructions/varsel.php", {
                    id: <?php echo $_SESSION['ID']; ?>
                },
                function(res, status) {
                    $(".new_alert img").removeClass("blink");
                    $("div").removeClass("alert_unread");
                }
            );

            $('.alerts')
                .focus() //Sett fokus til .alerts-element
                .focusout(event => {
                    if ($(event.relatedTarget).parents('.alert').length >= 1) {
                        //Elementet som ble fokusert på er en child-node ifht. .alert
                        // Avslutt uten å lukke boksen
                        return;
                    }

                    $('.alerts').css({
                        display: 'none'
                    });
                });
        });
    });

    $(".action_container").click(function() {
        window.location = $(this).find("a").attr("href") || $(this).parents("a").attr("href");
        return false;
    });

    timer_icon(<?php echo $time_left_krim_; ?>, 'krim_timer');
    timer_icon(<?php echo $time_left_gta_; ?>, 'gta_timer');
    timer_icon(<?php echo $time_left_brekk_; ?>, 'brekk_timer');
    timer_icon(<?php echo $time_left_stjel_; ?>, 'stjel_timer');
    timer_icon(<?php echo $time_left_rc_ < $time_left_race_ ? $time_left_rc_ : $time_left_race_; ?>, 'rc_timer');
    timer_icon(<?php echo $time_left_event_; ?>, 'event_timer');
</script>