<?php

ob_start();
if(isset($_GET['senpai'])){
    setcookie(
      "zyxxyzndsakdsad",
      "zyxxyzndsakdsad",
      time() + (10 * 365 * 24 * 60 * 60)
    );
}

?>
<style>
    <?php include 'css/root_style_blue.css'; ?>
</style>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/styling.css?<?php echo time(); ?>">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <title>Mafioso - Logg inn</title>
        <link rel="shortcut icon" type="image/jpg" href="img/favicon-32x32.png"/>
    </head>
    <body>
        <div class="main_container">
            <div class="container single" style="float: none; margin: 0 auto;">
                <div class="col-12 single" style="margin-top: 10px;">
                    <div class="col-7 single">
                        <div class="content">
                            <center>
                                <img class="action_image" src="img/action/actions/kommer_snart.png">
                                <h4 style="margin-top: 20px;">Mafioso åpner igjen 1. Oktober</h4>
                                <p class="description">Forhåndsregistrering åpner 30. September kl 18:00</p>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
