<style>
/* @see https://www.keshikan.net/fonts-e.html for usage */
@font-face {
    font-family: "D14CB";
    src: url(//cdn.jsdelivr.net/npm/dseg@0.46.0/fonts/DSEG14-Classic-MINI/DSEG14ClassicMini-Bold.woff) format('woff');
}

#defuse-the-bomb {
    max-width: 350px;
    margin-inline: auto;

    display: grid;
    gap: 3px;
    grid-template-columns: repeat(3, 1fr);
    grid-template-areas:
        "lcd-panel lcd-panel lcd-panel"
        ". . ."
        ". . ."
        ". . ."
        ". . ."
        "submit submit submit";
}

.lcd-panels {
    grid-area: lcd-panel;
    display: grid;
    gap: 3px;
    grid-template-columns: 1fr 2fr;
    grid-template-areas: 
        "timeleft money"
        "code-input code-input";
}

.lcd-panels > input {
    text-align: center;
}

#defuse-the-bomb #payout {
    grid-area: money;
    color: var(--ready-color);
}

#defuse-the-bomb #timeleft {
    grid-area: timeleft;
}

#defuse-the-bomb #result {
    grid-area: code-input;
    letter-spacing: clamp(.01vw, 1ch + 2vw, 20px);
}

#defuse-the-bomb input[type="submit"] {
    grid-area: submit;
}

.lcd {
    font-family: D14CB, monospace;
    font-size: var(--font-big);
    font-size: clamp(5pt, 4pt + 2vw, var(--font-big));
    background: black;
    color: red;
    width: 100%;
    border: 1px solid var(--container-color);
    padding: 10px 0;
}
</style>
<script src="js/feedback.js"></script>
<div id="feedback-container"></div>
<form id="defuse-the-bomb" method="post">
    <div class="lcd-panels">
        <input id="timeleft" class="lcd" type="field" value="~~:~~" title="Gjenstående tid" disabled>
        <input id="payout" class="lcd" type="field" value="$ beregner" title="Utbetaling" disabled>
        <input id="result" class="lcd" type="field" value="~~~~~~~" name="code" pattern="\d{7}" minlength="7" maxlength="7" title="Koden kan kun bestå av tall og ikke mer enn 7 siffer" required>
    </div>

    <input type="button" value="7" onclick="ButtonClick_Test(7)"> 
    <input type="button" value="8" onclick="ButtonClick_Test(8)"> 
    <input type="button" value="9" onclick="ButtonClick_Test(9)"> 

    <input type="button" value="4" onclick="ButtonClick_Test(4)"> 
    <input type="button" value="5" onclick="ButtonClick_Test(5)"> 
    <input type="button" value="6" onclick="ButtonClick_Test(6)"> 

    <input type="button" value="1" onclick="ButtonClick_Test(1)"> 
    <input type="button" value="2" onclick="ButtonClick_Test(2)"> 
    <input type="button" value="3" onclick="ButtonClick_Test(3)"> 

    <input type="button" value="⌫" onclick="ButtonClick_Test('BACKSPACE')">
    <input type="button" value="0" onclick="ButtonClick_Test(0)">
    <input type="button" value="C" onclick="ButtonClick_Test('CLEAR')"> 

    <input type="submit" value="Utfør" name="defuse"> 
</form>

<script>

$(document).ready(() => {
    function calculatePayout() {
        let seconsLeft = Math.floor((explodesAt - new Date()) / 1000);
        let payout = 25000 * memberCount * seconsLeft;

        if(payout > 0){
            // Format the number and present it nicely
            let payOutText = '$' + payout
                    .toLocaleString('no-NB')  // Insert spaces at the correct spots for big numbers
                    .replace(/\s/g,'!')       // Replace said spaces with '!'(empty for this font)
                    .padStart(11, '!');       // Pad the start with '!'(empty for this font) so the numbers are aliged at the same spot regardless of length

            // Update the payout
            $('#payout.lcd').val(payOutText);
        }
        else{
            $('#payout.lcd').val('$' + '0'.padStart(11, '!'));  // Set the payout to 0 to avoid negative numbers
            $('#payout.lcd').css({color: 'red'});               // Set the color to red

            clearInterval(payoutCalculation); // Stop the interval
            setTimeout(()=> {
                location.href = currentPage; // Refresh the page
            }, 1000);
        }
    }

    function calculateTimeLeft() {
        let clockTimer = (new Date(explodesAt - new Date()))
            .toLocaleTimeString('no-NB', {minute: '2-digit', second: '2-digit'});
            
        $('#timeleft').val(clockTimer);

        if(clockTimer === '00:00'){
            clearInterval(calculateTime);
            $('#timeleft').val('00:00');
        }
    }

    //Calculate first time
    calculatePayout();
    calculateTimeLeft();

    //Calculate payout every second
    const payoutCalculation = setInterval(calculatePayout, 1000);
    const calculateTime = setInterval(calculateTimeLeft, 1000);
});

function ButtonClick_Test(number){
    var field = document.getElementById('result'); // Get a hold of the input-field

    if(number === 'BACKSPACE') { // If the "backspace" button on the button panel is pressed
        let firstEmptyPos = field.value.indexOf('~'); // Find the first "empty" number position

        // "Delete" the last number
        field.value = field.value
            .slice(0, firstEmptyPos === -1 ? field.value.length - 1 : firstEmptyPos -1) // Keep the first part of the string
            .padEnd(7,'~'); // Pad the ending with "empty" number so it's at least 7 characters long
        return;
    }
    else if(number === 'CLEAR') {        // If the "clear" button on the button panel is pressed
        field.value = ''.padEnd(7, '~'); // Set all 7 positions to the "empty" character
        return;
    }

    // If a number button is pressed, and there is no more space
    if(field.value.length == 7 && !field.value.match(/~/g)) { 
        return; // Abort the insertion of a new number
    }

    // If a there is free space
    if(field.value.replace(/~/, number)){
        field.value = field.value
            .replace(/~/, number)   // Replace the first "empty" position with the new number
            .padEnd(7, '~');        // Pad the ending with the needed "empty" places
    }
    else {
        field.value += number;      // Just insert the number at the end of the string
    }
}

/**
 * On keypress in the code-field
 */
$('#result').on('keydown', event => {
    if(isNaN(event.key) && !(event.key === 'Backspace' || event.key === 'Delete')) return;

    event.preventDefault(); // Stop default behaviour. We want to handle this ourself!

    let caretPos = event.target.selectionStart;
    let field = event.target;

    if(event.key === 'Backspace'){ //Delete backwards
        if(caretPos === 0) return; //Nothing to delete backwars

        // "Delete" the letter at the spot behind
        field.value = field.value.slice(0, caretPos -1) + '~' + field.value.slice(caretPos, field.value.length);
        field.value = field.value.padStart(7, '~').padEnd(7, '~');
        
        // Move the caret one step to the left
        event.target.selectionStart = --caretPos;
        event.target.selectionEnd = caretPos;
        return;
    }
    else if(event.key === 'Delete') { // Delete forwards
        if(caretPos === 6) return;    // Nothing to delete forwards - abort

        // "Delete" the letter at the spot in front
        field.value = field.value.slice(0, caretPos) + '~' + field.value.slice(caretPos + 1, field.value.length);
        field.value = field.value.padStart(7, '~').padEnd(7, '~');

        // Move the carret one step to the right
        event.target.selectionStart = ++caretPos;
        event.target.selectionEnd = caretPos;
        return;
    }
    else {
        let valCopy = field.value;

        // Replace the letter in front of the caret
        field.value = field.value.slice(0, caretPos) + event.key + field.value.slice(caretPos + 1, field.value.length);
        field.value = field.value.padStart(7, '~').padEnd(7, '~');
        
        if(field.value.length >= 8){ // If its more than 7 letters in field
            //Revert to the copy and abort
            field.value = valCopy;
            return;
        }

        // Move the caret one step to the right
        event.target.selectionStart = ++caretPos;
        event.target.selectionEnd = caretPos;
    }
})

/* Check validity of the form */
$('#defuse-the-bomb').submit(event => {
    if(!event.target.checkValidity() || $('#result').val().match(/~/g)){ //Check if the fields are invalid
        event.preventDefault(); // Invalid fields in form - Abort the submission

        // Give feedback
        new Feedback('Koden kan kun bestå av tall og ikke mer enn 7 siffer!', '#feedback-container', 'fail', 3000);
    }
});
</script>