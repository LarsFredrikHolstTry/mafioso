<?php
if(!isset($_GET['side'])){
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

$legal = array(0, 1, 2, 3, 4, 5, 6);

if(isset($_GET['ny_post']) && in_array($_GET['ny_post'], $legal)){
     $cat = $_GET['ny_post'];
   
if(isset($_POST['post'])){
    $subject = htmlspecialchars($_POST['subject']);
    $text = htmlspecialchars($_POST['text']);
    if(strlen($subject) > 50){
        echo feedback("Max 50 tegn i tittelen", "fail");
    } else {
        if($cat != 3){
            $sql = "
            INSERT INTO 
                forum 
            (FRM_cat, FRM_title, FRM_content, FRM_date, FRM_acc_id, FRM_last_reply) 
                VALUES 
            (?,?,?,?,?,?)";
                $pdo -> prepare($sql)->execute(
            [$cat, $subject, $text, time(), $_SESSION['ID'], time()]
                );
        } elseif($cat == 3) {
            if(get_my_familyID($_SESSION['ID'], $pdo)){
                
                $sql = "
                INSERT INTO 
                    forum 
                (FRM_cat, FRM_title, FRM_content, FRM_date, FRM_acc_id, FRM_last_reply, FRM_fam_id) 
                    VALUES 
                (?,?,?,?,?,?,?)";
                    $pdo -> prepare($sql)->execute(
                [$cat, $subject, $text, time(), $_SESSION['ID'], time(), get_my_familyID($_SESSION['ID'], $pdo)]
                    );
                
            } else {
                echo feedback("Du kan ikke poste på familieforumet uten å være med i en familie", "fail");
            }
        }
        
        header("Location: ?side=forum&cat=$cat");
    }
}
        
?>

<div class="col-9 single">
    <div class="content">
        <h4 style="margin-bottom: 20px;">Ny post i <?php echo forum_cat($cat, $useLang); ?></h4>
        <form method="post">
            <div class="col-12" style="padding: 0 5;">
                <p>Tittel på post</p>
                <input style="width: 40%;" type="text" name="subject" placeholder="Tittel..." required>
            </div>
            <div class="col-12">
                <p>Tekst</p>
                <?php include_once '././textarea_style.php'; ?>
                <textarea name="text" rows="10" placeholder="Tekst..." id="txtarea" required></textarea>
                <p class="description">Husk å bruk <a href="?side=wiki#bb">BB-Koder</a> for ett ekstra preg på innlegget ditt!</p>
                <input type="submit" name="post" value="Post innlegg"> 
                <input type="button" value="Forhåndsvisning" onclick="open_preview()">
            </div>
            <div style="clear: both;"></div>
        </form>
    </div>
</div>

<?php include 'live_preview.php'; ?>

<?php } else { echo feedback("Ugyldig side", "fail"); } } ?>