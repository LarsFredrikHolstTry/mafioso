<div id="mySidenav_right" class="sidenav_right">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNavRight()">&times;</a>
    <?php

    include 'constructions/right_alerts.php';

    ?>

    <div class="right_content margin_bottom">
        <h4><a href="?side=forum_oversikt">Mafioso</a></h4>
        <ul>
            <a href="?side=varsel">
                <li>Varsler</li>
            </a>
            <a href="?side=innboks">
                <li>Innboks</li>
            </a>
            <a href="?side=online">
                <li><?php echo number(players_online($pdo)); ?> spillere pålogget</li>
            </a>
        </ul>
    </div>

    <?php include 'constructions/right_content.php'; ?>

    <style>
        .active {
            background-color: var(--container-color);
        }

        #mySidenav_right ul li {
            color: white;
        }

        .sidenav_right {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            right: 0;
            background-color: var(--main-bg-color);
            overflow-x: hidden;
            padding-top: 60px;
            transition: 0.5s;
        }

        .sidenav_right .closebtn {
            position: absolute;
            top: 0;
            font-size: 36px;
            right: 15px;
        }

        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
        @media screen and (max-height: 450px) {
            .sidenav_right {
                padding-top: 15px;
            }
        }
    </style>

    <script>
        /* Set the width of the side navigation to 250px */
        function openNavRight() {
            document.getElementById("mySidenav_right").style.width = "100%";
        }

        /* Set the width of the side navigation to 0 */
        function closeNavRight() {
            document.getElementById("mySidenav_right").style.width = "0";
        }
    </script>