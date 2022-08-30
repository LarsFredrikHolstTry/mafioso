<?php

if(isset($_GET['brukernavn'])){
    if($_GET['brukernavn'] == "Vito"){
?>
<div class="col-9 single">
    <div class="content">
        <div class="col-6" style="display: flex; justify-content: center;">
            <img style="max-width: 300px; max-width: 300px"; src="img/avatar/vito.png">
        </div>
        <div class="col-6">
            <div class="col-10">
            <ul>
                <li>Brukernavn: <span style="float: right;">Vito</span></li>
                <li>Status: <span style="float: right;">Levende</span></li>
                <li>Rank: <span style="float: right;">Gangster</span></li>
                <li>Pengerank: <span style="float: right;">Rik</span></li>
                <li>Familie: <span style="float: right;">Ingen</span></li>
            </ul>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>

<?php } } ?>