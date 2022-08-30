<div class="right">
    
    <div class="date">
        <a href="?side=online"><?php echo str_replace('{players}', number(players_online($pdo)), $useLang->index->gamersOnline) ?></a><br>
        <span id="date"><?php echo date_to_text_long(time()); ?></span>
        <span id="clock"><?php echo time_to_text(time()); ?></span>
    </div>
    
    <?php 
    
        include 'constructions/right_alerts.php';
        include 'constructions/right_content.php'; 
        
    ?>
</div>