<?php
if(!isset($_GET['side'])){
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

if(isset($_POST['send'])){
    $title = htmlspecialchars($_POST['title']);
    $text = htmlspecialchars($_POST['text']);
    $date = time();
    
    $sql = "INSERT INTO support (SP_text, SP_from, SP_title, SP_date) VALUES (?,?,?, ?)";
    $pdo -> prepare($sql)->execute([$text, $_SESSION['ID'], $title, $date]);
    
    echo feedback("Support er sendt! Du kan forvente svar innen 24 timer 🚀", "success");
}

?>

<div class="row">
    
    <div class="col-7">
        <div class="content">
            <h3>Support</h3>
            <form method="post">
                <p>Tittel</p>
                <input type="text" name="title" placeholder="Tittel" required>
                <p>Tekst</p>
                <textarea rows="12" name="text" required></textarea>
                <input type="submit" name="send" value="Send support" style="margin-top: 10px;">
            </form>
        </div>
    </div>
    <div class="col-5">
        <div class="content">
            <h3>Mafioso</h3>
            <p class="description">På <a href="?side=wiki">wiki</a> vil du finne det meste du trenger av hjelp til spillet. Dersom du trenger hjelp eller har generelle spørsmål, ta kontakt på support så vil noen i ledelsen besvare deg på det du lurer på. Dersom du skulle finne feil og mangler på spillet vil vi gjerne ha det på support.</p>
            <h4 style="margin-bottom: 10px;">Ledelsen av Mafioso</h4>
            <table>
                <tr>
                    <th>Bruker</th>
                    <th>Status</th>
                </tr>
                <?php 

                    $sql = "SELECT * FROM accounts WHERE ACC_type = 1 OR ACC_type = 2 OR ACC_type = 3 OR ACC_type = 6 ORDER BY ACC_type DESC";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                ?>
                <tr>
                    <td><?php echo ACC_username($row['ACC_id'], $pdo); ?></td>
                    <td><?php echo roles($row['ACC_type']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<?php } ?>