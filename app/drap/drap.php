<?php

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    if (!isset($_GET['p'])) {
?>

        <div class="col-8 single">
            <div class="content">
                <h3 style="text-align: center;">Drapsorganisering</h3>
                <p style="text-align: center;" class="description">Her finner du alt du trenger innen drapsorganisering</p>
                <div class="drap_modern">
                    <a href="?side=drap&p=drep">
                        <div class="drap_div">
                            <img class="drap_image" src="img/drap/drep.png">
                            <p><b>Drap</b></p>
                            <p class="description">Her dreper du andre spillere</p>
                        </div>
                    </a>
                    <a href="?side=drap&p=kf">
                        <div class="drap_div">
                            <img class="drap_image" src="img/drap/kf.png">
                            <p><b>Kulefabrikk</b></p>
                            <p class="description">Her kan du kjøpe og produsere kuler
                            </p>
                        </div>
                    </a>
                    <a href="?side=drap&p=fp">
                        <div class="drap_div">
                            <img class="drap_image" src="img/drap/fp.png">
                            <p><b>Forsvarspoeng</b></p>
                            <p class="description">Kjøp forsvarspoeng</p>
                        </div>
                    </a>
                </div>
                <div class="drap_modern">
                    <a href="?side=drap&p=detektiv">
                        <div class="drap_div">
                            <img class="drap_image" src="img/drap/detective.png">
                            <p><b>Detektiv</b></p>
                            <p class="description">Søk etter spillere før drap</p>
                        </div>
                    </a>
                    <a href="?side=drap&p=skytetrening">
                        <div class="drap_div">
                            <img class="drap_image" src="img/drap/skytetrening.png">
                            <p><b>Skytetrening</b></p>
                            <p class="description">Tren skyteferdighetene dine her!
                            </p>
                        </div>
                    </a>
                    <a href="?side=drap&p=ransbeskyttelse">
                        <div class="drap_div">
                            <img class="drap_image" src="img/drap/beskyttelse.png">
                            <p><b>Ransbeskyttelse</b></p>
                            <p class="description">Kjøp ransbeskyttelse</p>
                        </div>
                    </a>
                </div>
                <div class="drap_modern">
                    <a href="?side=drap&p=eiendom">
                        <div class="drap_div">
                            <img class="drap_image" src="img/drap/eiendom.png">
                            <p><b>Eiendom</b></p>
                            <p class="description">Kjøp eiendom her</p>
                        </div>
                    </a>
                    <a href="?side=drap&p=bunker">
                        <div class="drap_div">
                            <img class="drap_image" src="img/drap/bunker.png">
                            <p><b>Bunker</b></p>
                            <p class="description">Velg din bunker</p>
                        </div>
                    </a>
                    <a href="?side=drap&p=kalkulator">
                        <div class="drap_div">
                            <img class="drap_image" src="img/drap/kalkulator.png">
                            <p><b>Kalkulator</b></p>
                            <p class="description">Regn ut hvor mange kuler som trengs for å drepe en spiller</p>
                        </div>
                    </a>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
<?php

    } else {
        if (isset($_GET['p'])) {
            $page = $_GET['p'];

            if ($page == "drep") {
                include_once 'drep.php';
            } elseif ($page == "fp") {
                include_once 'fp.php';
            } elseif ($page == "ransbeskyttelse") {
                include_once 'ransbeskyttelse.php';
            } elseif ($page == "skytetrening") {
                include_once 'skytetrening.php';
            } elseif ($page == "kf") {
                include_once 'kulefabrikk.php';
            } elseif ($page == "detektiv") {
                include_once 'detektiv.php';
            } elseif ($page == "fp2") {
                include_once 'fp2.php';
            } elseif ($page == "kalkulator") {
                include_once 'kalkulator.php';
            } elseif ($page == "eiendom") {
                include_once 'eiendom.php';
            } elseif ($page == "bunker") {
                include_once 'bunker.php';
            }
        }
    }
}
