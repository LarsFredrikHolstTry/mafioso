
<div class="col-12 single">
    <div class="col-6" style="padding-top: 0px;">
        <div class="content">
            <h4>Innholdsfortegnelse</h4>
            <?php

            $sql = "SELECT * FROM wiki ORDER BY WIKI_title";
            $stmt = $pdo->query($sql);
            while ($row_wiki = $stmt->fetch(PDO::FETCH_ASSOC)) {

            ?>
            <div class="col-4 description">
                <a href="#<?php echo $row_wiki['WIKI_ID'] ?>"><?php echo $row_wiki['WIKI_title']; ?></a>
            </div>
            <?php } ?>
            <div style="clear: both;"></div>
        </div>
    </div>
    <div class="col-6" style="padding-top: 0px;">
        <div class="content">
            <h4>Wiki</h4>
            <p class="description">Her vil du finne all relevant info om spillet. Om det er noe du ikke finner i wiki'en, ta kontakt på support.</p>
        </div>
    </div>
<?php

$sql = "SELECT * FROM wiki ORDER BY WIKI_title";
$stmt = $pdo -> query($sql);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

?>

<div id="<?php echo $row['WIKI_ID']; ?>" class="col-12">
    <div class="content">
        <h4><?php echo $row['WIKI_title']; ?></h4>
        <p class="description"><?php echo $row['WIKI_desc']; ?></p>
        <p class="description">
        <?php if(ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) != 0){ ?>
            <a href="?side=admin&adminside=wiki_endre&wiki_edit_id=<?php echo $row['WIKI_ID']; ?>" style="padding-right: 10px;">Endre</a> 
            <a href="?side=admin&adminside=wiki_slett&wiki_delete_id=<?php echo $row['WIKI_ID']; ?>" style="padding-right: 10px;">Slett</a>
        <?php } ?>
        Sist oppdatert:  <?php echo date_to_text($row['WIKI_date']); ?>
        </p>
    </div>
</div>


<?php } ?>
</div>