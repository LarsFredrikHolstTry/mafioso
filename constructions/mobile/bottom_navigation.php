<?php

$stmt_ = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
$stmt_->execute(array(
    ':cd_id' => $_SESSION['ID']
));
$row_ = $stmt_->fetch(PDO::FETCH_ASSOC);

$time_left_krim_ = $row_['CD_crime'] - time();
$time_left_gta_ = $row_['CD_gta'] - time();
$time_left_brekk_ = $row_['CD_brekk'] - time();
$time_left_stjel_ = $row_['CD_steal'] - time();
$time_left_rc_ = $row_['CD_rc'] - time();
$time_left_race_ = $row_['CD_race'] - time();
$time_left_event_ = $row_['CD_event'] - time();

?>


<div class="col-12 mobile_top" style="background-color: var(--main-bg-color); bottom: 0px; z-index: 1000; position: absolute; position: fixed;">


        <div style="width:13.3%; float:left;">
            <a href="?side=pengeinnkreving">
                <div id="krim_timer_mobile" class="content" style="<?php
                                                                    if ($time_left_event_ <= 0) {
                                                                        echo 'background-color: var(--ready-color);';
                                                                    } else {
                                                                        echo 'background-color: var(--not-ready-color);';
                                                                    }
                                                                    ?> color: black; padding: 7px; margin-right: 7px;">
                    <img style="display: block; margin-bottom: 0px; margin: 0 auto; width: 25px; height: auto;" src="img/topHeaderIcons/pengeinnkreving.svg">
                    <p style="text-align: center; font-size: 11px; margin: 10px 0px 0px 0px;">
                        <?php

                        if ($time_left_event_ <= 0) {
                            echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                        } else {
                            echo '<span style="font-size: 11px;" id="countdowntimer_event">' . $time_left_event_ . 's</span>';
                        ?> <script>
                                timer(<?php echo $time_left_event_; ?>, "countdowntimer_event");
                            </script> <?php
                                    }

                                        ?></p>
                </div>
            </a>
        </div>

        <div style="width:13.3%; float:left;">
            <a href="?side=rc">
                <div id="rc_timer_mobile" class="content" style="<?php
                                                                    if ($time_left_rc_ <= 0 || $time_left_race_ <= 0) {
                                                                        echo 'background-color: var(--ready-color);';
                                                                    } else {
                                                                        echo 'background-color: var(--not-ready-color);';
                                                                    }
                                                                    ?> color: black; padding: 7px; margin-right: 7px;">
                    <img style="display: block; margin-bottom: 0px; margin: 0 auto; width: 25px; height: auto;" src="img/topHeaderIcons/streetrace.svg">
                    <p style="text-align: center; font-size: 11px; margin: 10px 0px 0px 0px;">
                        <?php

                        if ($time_left_rc_ <= 0 || $time_left_race_ <= 0) {
                            echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                        } else {
                            if ($time_left_rc_ < $time_left_race_) {
                                echo '<span id="countdowntimer_rc">';
                                echo $time_left_rc_;
                                echo 's</span>';
                        ?> <script>
                                    timer(<?php echo $time_left_rc_; ?>, "countdowntimer_rc");
                                </script> <?php
                                        } else {
                                            echo '<span id="countdowntimer_race">';
                                            echo $time_left_race_;
                                            echo 's</span>';
                                            ?> <script>
                                    timer(<?php echo $time_left_race_; ?>, "countdowntimer_race");
                                </script> <?php
                                        }
                                    }

                                            ?>
                    </p>
                </div>
            </a>
        </div>
        <div style="width:13.3%; float:left;">
            <a href="?side=kriminalitet">
                <div id="krim_timer_mobile" class="content" style="<?php
                                                                    if ($time_left_krim_ <= 0) {
                                                                        echo 'background-color: var(--ready-color);';
                                                                    } else {
                                                                        echo 'background-color: var(--not-ready-color);';
                                                                    }
                                                                    ?> color: black; padding: 7px; margin-right: 7px;">
                    <img style="display: block; margin-bottom: 0px; margin: 0 auto; width: 25px; height: auto;" src="img/topHeaderIcons/kriminalitet.svg">
                    <p style="text-align: center; font-size: 11px; margin: 10px 0px 0px 0px;">
                        <?php

                        if ($time_left_krim_ <= 0) {
                            echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                        } else {
                            echo '<span style="font-size: 11px;" id="countdowntimer_krim">' . $time_left_krim_ . 's</span>';
                        ?> <script>
                                timer(<?php echo $time_left_krim_; ?>, "countdowntimer_krim");
                            </script> <?php
                                    }

                                        ?></p>
                </div>
            </a>
        </div>
        <div style="width:13.3%; float:left;">
            <a href="?side=biltyveri">
                <div id="gta_timer_mobile" class="content" style="<?php
                                                                    if ($time_left_gta_ <= 0) {
                                                                        echo 'background-color: var(--ready-color);';
                                                                    } else {
                                                                        echo 'background-color: var(--not-ready-color);';
                                                                    }
                                                                    ?> color: black; padding: 7px; margin-right: 7px;">
                    <img style="display: block; margin-bottom: 0px; margin: 0 auto; width: 25px; height: auto;" src="img/topHeaderIcons/biltyveri.svg">
                    <p style="text-align: center; font-size: 11px; margin: 10px 0px 0px 0px;">
                        <?php

                        if ($time_left_gta_ <= 0) {
                            echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                        } else {

                            echo '<span id="countdowntimer_gta">';
                            echo $time_left_gta_;
                            echo 's</span>';
                        ?> <script>
                                timer(<?php echo $time_left_gta_; ?>, "countdowntimer_gta");
                            </script> <?php
                                    }

                                        ?></p>
                </div>
            </a>
        </div>
        <div style="width:13.3%; float:left;">
            <a href="?side=brekk">
                <div id="brekk_timer_mobile" class="content" style="<?php
                                                                    if ($time_left_brekk_ <= 0) {
                                                                        echo 'background-color: var(--ready-color);';
                                                                    } else {
                                                                        echo 'background-color: var(--not-ready-color);';
                                                                    }
                                                                    ?> color: black; padding: 7px; margin-right: 7px;">
                    <img style="display: block; margin-bottom: 0px; margin: 0 auto; width: 25px; height: auto;" src="img/topHeaderIcons/brekk.svg">
                    <p style="text-align: center; font-size: 11px; margin: 10px 0px 0px 0px;">
                        <?php

                        if ($time_left_brekk_ <= 0) {
                            echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                        } else {

                            echo '<span var(--not-ready-color)id="countdowntimer_brekk">';
                            echo $time_left_brekk_;
                            echo 's</span>';
                        ?> <script>
                                timer(<?php echo $time_left_brekk_; ?>, "countdowntimer_brekk");
                            </script> <?php
                                    }

                                        ?></p>
                </div>
            </a>
        </div>
        <div style="width:13.3%; float:left;">
            <a href="?side=stjel">
                <div id="stjel_timer_mobile" class="content" style="<?php
                                                                    if ($time_left_brekk_ <= 0) {
                                                                        echo 'background-color: var(--ready-color);';
                                                                    } else {
                                                                        echo 'background-color: var(--not-ready-color);';
                                                                    }
                                                                    ?> color: black; padding: 7px; margin-right: 7px;">
                    <img style="display: block; margin-bottom: 0px; margin: 0 auto; width: 25px; height: auto;" src="img/topHeaderIcons/stjel.svg">
                    <p style="text-align: center; font-size: 11px; margin: 10px 0px 0px 0px;">
                        <?php

                        if ($time_left_stjel_ <= 0) {
                            echo '<span class="ready">' . $useLang->misc->ready . '</span>';
                        } else {

                            echo '<span id="countdowntimer_stjel">';
                            echo $time_left_stjel_;
                            echo 's</span>';
                        ?> <script>
                                timer(<?php echo $time_left_stjel_; ?>, "countdowntimer_stjel");
                            </script> <?php
                                    }

                                        ?></p>
                </div>
            </a>
        </div>
        <div style="width:13.3%; float:left; float:left;">
            <a href="?side=fengsel">
                <div class="content" style="padding: 7px; margin-right: 7px;">
                    <img style="display: block; margin-bottom: 0px; margin: 0 auto; width: 25px; height: auto;" src="img/topHeaderIcons/fengsel.svg">
                    <p style="text-align: center; font-size: 11px; margin: 10px 0px 0px 0px;">
                        <?php if (people_in_jail($pdo) > 0) {
                            echo people_in_jail($pdo);
                        } else {
                            echo "Ingen";
                        } ?></p>
                </div>
            </a>
        </div>
</div>

<script>
    <?php if (AS_session_row($_SESSION['ID'], 'AS_city', $pdo) != 5) { ?>
        timer_icon(<?php echo $time_left_krim_; ?>, 'krim_timer_mobile');
        timer_icon(<?php echo $time_left_gta_; ?>, 'gta_timer_mobile');
        timer_icon(<?php echo $time_left_brekk_; ?>, 'brekk_timer_mobile');
        timer_icon(<?php echo $time_left_stjel_; ?>, 'stjel_timer_mobile');
        timer_icon(<?php echo $time_left_rc_ < $time_left_race_ ? $time_left_rc_ : $time_left_race_; ?>, 'rc_timer_mobile');
    <?php } else { ?>
        timer_icon(<?php echo $time_left_event_; ?>, 'stjel_timer_event');
    <?php } ?>
</script>