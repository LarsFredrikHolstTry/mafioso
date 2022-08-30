<div class="col-12 mobile_top" style="background-color: var(--main-bg-color); position: sticky; float: none; top: 0;">
    <div class="content">
        <div style="box-sizing: border-box; float: left; width: 20%; height: auto;">
            <img onclick="openNavLeft()" src="constructions/mobile/menu.png" style="float: left; height: 25px; width: 25px;">
        </div>
        <div style="box-sizing: border-box; float: left; width: 60%; height: auto;">
            <center>
                <a href="index.php"><img src="img/favicon-32x32.png"></a>
            </center>
        </div>
        <div style="box-sizing: border-box; float: left; width: 20%; height: auto;">
            <?php if (unread_pm($_SESSION['ID'], $pdo) > 0 || new_notifications($_SESSION['ID'], $pdo) > 0) { ?>
                <img onclick="openNavRight()" class="blink" src="constructions/mobile/menu_alert.png" style="float: right; height: 25px; width: 25px;">
            <?php  } else { ?>
                <img onclick="openNavRight()" src="constructions/mobile/menu.png" style="float: right; height: 25px; width: 25px;">
            <?php } ?>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
<div class="col-12 mobile_top" style="background-color: var(--main-bg-color); float: none;">

    <div class="content" style="padding: 5px !important;">
        <?php

        $utrop = newest_shout($pdo);
        $utrop_user = newest_shout_user($pdo);
        $date = newest_shout_date($pdo);

        ?>
        <span class="description">
            <?php echo 'Utrop fra ' . ACC_username($utrop_user, $pdo) . ' ' . $utrop . ' ' . date_to_text($date) ?>
        </span>
    </div>
</div>
<?php include 'constructions/mobile/sidenav_left.php'; ?>
<?php include 'constructions/mobile/sidenav_right.php'; ?>