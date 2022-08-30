<?php

        ob_start();
        include '../db_cred/db_cred.php';
        include_once 'functions/functions.php';
        include_once 'functions/feedbacks.php';

?>
<style>
    <?php include 'css/root_style_blue.css'; ?>
</style>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="stylesheet" href="css/styling.css?<?php echo time(); ?>">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <title>Mafioso - verifikasjon</title>
        <link rel="shortcut icon" type="image/jpg" href="img/favicon-32x32.png"/>
    </head>
    <body>
        <div class="col-5 single" style="margin-top: 20px;">
            <div class="content">
                <?php

                    if(isset($_GET['v'])){
                        $code = $_GET['v'];

                        verificate_me($code, $pdo);

                        header('Location: index.php?ver');
                    } else {
                        echo 'Ugyldig verfikasjons-kode';
                    }

                ?>
            </div>
        </div>
    </body>
</html>