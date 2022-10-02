<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $stmt = $pdo->prepare("SELECT count(*) FROM shout_queue");
    $stmt->execute([]);
    $people_in_queoe = $stmt->fetchColumn();

    $price = 7;
    $max_symb = 40;
    $minutes_pr_person = 10;
    $total_waittime = $minutes_pr_person * $people_in_queoe;

    if (isset($_POST['submit'])) {
        if (isset($_POST['text'])) {
            if (strlen($_POST['text']) > $max_symb) {
                echo feedback("Utropet kan ikke være lengre enn " . $max_symb . " symboler", "error");
            } else {
                if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) >= $price) {
                    take_poeng($_SESSION['ID'], $price, $pdo);
                    $text = htmlspecialchars($_POST['text']);

                    $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
                    $text = htmlentities($text, ENT_QUOTES, 'UTF-8');

                    if (last_shout($pdo) + 600 > time()) {
                        $query = $pdo->prepare('INSERT INTO shout_queue(SHTQUE_acc_id, SHTQUE_message, SHTQUE_date) VALUES (:acc_id, :msg, :date)');
                        $query->execute(array(
                            'acc_id' => $_SESSION['ID'],
                            'msg' => $text,
                            'date' => time()
                        ));
                    } elseif ($total_waittime == 0 || $total_waittime == null) {
                        $query = $pdo->prepare('INSERT INTO shout(SH_acc_id, SH_message, SH_date) VALUES (:acc_id, :msg, :date)');
                        $query->execute(array(
                            'acc_id' => $_SESSION['ID'],
                            'msg' => $text,
                            'date' => time()
                        ));
                    } else {
                        $query = $pdo->prepare('INSERT INTO shout_queue(SHTQUE_acc_id, SHTQUE_message, SHTQUE_date) VALUES (:acc_id, :msg, :date)');
                        $query->execute(array(
                            'acc_id' => $_SESSION['ID'],
                            'msg' => $text,
                            'date' => time()
                        ));
                    }

                    echo feedback("Du har sendt ut et utrop, dersom det er kø vil din bli vist når køen oppdateres!", "success");
                } else {
                    echo feedback("Du har ikke nok penger til å sende et utrop", "error");
                }
            }
        }
    }
?>

    <div class="col-7 single">
        <div class="content">
            <h4>Utrop</h4>
            <p class="description">Et utrop kan bli kalt av hvem som helst. Prisen pr utrop er <?php echo number($price); ?> poeng og varer i 10 minutter eller til noen andre kjøper.
                Utrop oppdateres hvert hele 10 minutt.</p>
            Kø: <?php

                if ($total_waittime == 0 || $total_waittime == null) {
                    echo '<span style="color: green">Ingen kø</span>';
                } else {
                    echo '<span>' . $total_waittime . ' minutter</span>';
                }

                ?>
            <br>
            <p class="description">Melding <span id="counter"></span></p>
            <form method="post">

                <input type="text" id="text" onkeyup="myFunction(this.value)" name="text" style="width: 80%; float: left;" placeholder="Utrop...">
                <input type="submit" name="submit" style="width: 19.5%; float: right;">
            </form>
            <div style="clear:both"></div>
        </div>
    </div>

    <script type="text/javascript">
        function myFunction(word) {
            var str = word;
            var n = str.length;
            document.getElementById("counter").innerHTML = "(Tegn: " + n + " / 40)";
        }
    </script>
<?php } ?>