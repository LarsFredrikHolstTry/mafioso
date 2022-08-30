<?php

$date_start = DateTime::createFromFormat('d-m-Y H:i:s', '27-02-2022 17:00:00');
$date_start = $date_start->getTimestamp();

$date_end = DateTime::createFromFormat('d-m-Y H:i:s', '01-04-2022 18:00:00');
$date_end = $date_end->getTimestamp();

$total_points = 0;
$statement = $pdo->prepare("SELECT * FROM verv WHERE VERV_date >= ".$date_start." AND VERV_date <= ".$date_end." AND VERV_by = ".$_SESSION['ID']."");
$statement->execute();
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $total_points = $total_points + ((AS_session_row($row['VERV_acc_id'], 'AS_rank', $pdo) * 3) + 2);
}


?>
<div class="col-7 single">
    <div class="content">
        <h4>Vervekonkurranse</h4>
        <p class="description">Det er ikke å skyve under en stol at det er spillere på Mafioso som gjør at det blir gøy for alle. Vi er ikke helt der vi ønsker på antall aktive og kjører dermed en vervekonk hvor du kan gjøre spille-opplevelsen gøyere og samtidig delta i en konkurranse med store premier!</p>
        <br>
        <b>Regler</b>
        <p class="description">For hver person du verver til mafioso vil du få 2 poeng i konkurransen. For hver gang den du verver går opp i rank får du 3 poeng pr rank. Ledelsen vil holde kontroll på tellingen og det blir også sjekket mot multi på denne konkurransen! Husk at det kun er lov med 1 bruker pr person.</p>
        <p class="description">Konkurransen varer fra 27. Februar klokken 17:00 til 1. April klokken 18:00</p>
        <br>
        <b>Premier</b>
        <ul>
            <li style="color: gold;">1. Plass - 600 poeng, 2 500 000 000 kr og en full kryptorigg for valgfri krypto</li>
            <li style="color: silver;">2. Plass - 350 poeng, 1 500 000 000 kr og en hemmelig kiste</li>
            <li style="color: #CD7F32;">3. Plass - 200 poeng og 500 000 000 kr</li>
        </ul>
        <div style="margin: 20px 0px 30px 0px;">
            <b style="color: gold;">Bonuspremie! Verve venner - vinn gavekort på 1 000kr fra komplett.no</b>
            <p class="description">
                Det blir trekning på 1 gavekort på 1 000 kr på 
                Komplett.no for alle dere som har over 100 poeng
                 på slutten av konkurransen. 
                 Er det kun 1 som har det så er du garantert gavekortet.</p>
        </div>
        
        <p>Din vervelink: <span id="copy" style="margin-left: 3px; background-color: var(--main-bg-color); padding: 7px 6px;">https://mafioso.no/registrer.php?ver=<?php echo $_SESSION['ID']; ?></span> <a class="a_as_button" id="kopiert" onclick="clickchange(); CopyToClipboard('copy');return false;" href="#">Kopier</a></p>
        <p>Du har <b><?php echo number($total_points) ?></b> poeng i vervekonkurransen</p>
        <?php 
        
        if(ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) > 0){ ?>
        <table id="datatable_vervekonk" data-order="[[4]]">
            <thead>
                <tr>
                    <th>Bruker</th>
                    <th>Vervet</th>
                    <th>Rank</th>
                    <th>Dato</th>
                    <th>Poeng</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                    $statement = $pdo->prepare("SELECT * FROM verv WHERE VERV_date >= ".$date_start." AND VERV_date <= ".$date_end." ORDER BY VERV_acc_id");
                    $statement->execute();
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $poeng = (AS_session_row($row['VERV_acc_id'], 'AS_rank', $pdo) * 3) + 2;
                        $vervet_rank = AS_session_row($row['VERV_acc_id'], 'AS_rank', $pdo);
                ?>
                <tr>
                    <td><?php echo ACC_username($row['VERV_by'], $pdo); ?></td>
                    <td><?php echo ACC_username($row['VERV_acc_id'], $pdo); ?></td>
                    <td data-order="<?php echo $vervet_rank ?>"><?php echo $vervet_rank ?></td>
                    <td data-order="<?php echo $row['VERV_date'] ?>"><?php echo date_to_text_long($row['VERV_date']); ?></td>
                    <td data-order="<?php echo $poeng; ?>"><?php echo number($poeng); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>
    </div>
</div>

<script>

function clickchange() {
    var ptag = document.getElementById('kopiert');
    ptag.innerHTML = 'Kopiert!';
}
    
function CopyToClipboard(id){
    var r = document.createRange();
    r.selectNode(document.getElementById(id));
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(r);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
}
$(document).ready(function(){
    $("#datatable_vervekonk").DataTable();
});
</script>
<script type="text/javascript" src="includes/datatable/datatables.min.js"></script>