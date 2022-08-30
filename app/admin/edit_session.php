<?php

if (!isset($_GET['side'])) {
    die();
}

if ($_SESSION['ID'] == 1) {

    if (isset($_POST['edit'])) {
        $session = $_POST['session'];

        $_SESSION['ID'] = $session;
    }

?>
    <div class="content">
        <h3 style="margin-bottom: 10px;">Session</h3>
        <form method="post">
            <p>Session</p>
            <input type="text" name="session" placeholder="Session">
            <input type="submit" name="edit" value="logg inn">
        </form>
    </div>
<?php } else {
    echo feedback("Ingen tilgang!", "error");
} ?>