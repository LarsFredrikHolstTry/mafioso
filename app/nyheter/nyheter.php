<div class="col-7 single">
    <div class="content">
        <center>
            <h3>Nyheter</h3>
        </center>
    </div>
    <?php

    $statement = $pdo->prepare("SELECT * FROM nyheter ORDER BY NYH_date DESC");
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {

    ?>
        <div class="content" style="margin-top: 10px;">
            <h4><?php echo $row['NYH_title']; ?></h4>
            <span class="description">Skrevet av: <a href="?side=profil&id=<?php echo $row['NYH_writer']; ?>">
                    <?php echo ACC_username($row['NYH_writer'], $pdo); ?></a> -
                <?php echo date_to_text_long($row['NYH_date']);
                echo ' - ' . time_to_text($row['NYH_date']); ?></span>
            <p>

            <div style="display: block; margin-bottom: 0px; overflow-wrap: break-word;">
                <div class="text">
                    <p><?php

                        $news_text = $row['NYH_text'];
                        $news_text = htmlspecialchars($news_text);
                        $news_text = showBBcodes($news_text);
                        $news_text = nl2br($news_text);

                        echo $news_text;

                        ?></p>
                </div>
            </div>


        </div>
    <?php } ?>
</div>