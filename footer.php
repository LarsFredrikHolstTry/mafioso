<div class="someDiv" style="color: rgba(255,255,255,.5);">
    <?php

    $endtime = microtime(true); // Bottom of page
    printf("Nettsiden lastet inn på %f sekunder", $endtime - $starttime);

    ?><br>
    Copyright © <?php echo date("Y"); ?> Mafioso. All rights reserved. Laget med <span style='color: hotpink;'>♥</span><br>
    <!-- Org nr. 925 084 611 - Holst-Try Webdesign<br> -->
    Utviklet av <a href="?side=profil&id=1">Skitzo</a>

</div>
<style>
    .someDiv {
        font-size: 11px;
        margin: 0 auto;
        text-align: center;
        width: 100%;
        padding: 125px 0;
        bottom: 0px;
        clear: both;
    }
</style>