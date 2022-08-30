<?php

if(isset($_GET['side'])){
    $side = $_GET['side'];
} else {
    $side = null;
}

?>
<div class="left_container">
    <?php include 'left_content.php'; ?>
</div>