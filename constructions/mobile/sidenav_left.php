<?php

if(isset($_GET['side'])){
    $side = $_GET['side'];
} else {
    $side = null;
}

?>
<div id="mySidenav_left" class="sidenav_left">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNavLeft()">&times;</a>
    <?php include 'constructions/left_content.php'; ?>
</div>

<style>
    
    .active {
        background-color: var(--container-color);
    }

    #mySidenav_left ul li {
        color: white;
    }
    
    .sidenav_left {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: var(--main-bg-color);
        overflow-x: hidden;
        padding-top: 60px;
        transition: 0.5s;
    }

    .sidenav_left .closebtn {
        position: absolute;
        top: 0;
        font-size: 36px;
        left: 15px;
    }

    /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
    @media screen and (max-height: 450px) {
        .sidenav_left {
            padding-top: 15px;
        }
    }
    
</style>

<script>
    /* Set the width of the side navigation to 250px */
    function openNavLeft() {
        document.getElementById("mySidenav_left").style.width = "100%";
    }

    /* Set the width of the side navigation to 0 */
    function closeNavLeft() {
        document.getElementById("mySidenav_left").style.width = "0";
    }
</script>