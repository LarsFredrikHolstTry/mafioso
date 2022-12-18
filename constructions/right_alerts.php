<?php

if (ACC_session_row($_SESSION['ID'], 'ACC_type', $pdo) > 0) {
    if (total_support_unread($pdo) == 1) {
        echo '<a href="?side=admin&adminside=support">';
        echo feedback(total_support_unread($pdo) . " ny support!", "fail");
        echo '</a>';
    } elseif (total_support_unread($pdo) > 1) {
        echo '<a href="?side=admin&adminside=support">';
        echo feedback(total_support_unread($pdo) . " nye support!", "fail");
        echo '</a>';
    }
}

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    right_alert("?side=bunker", "Bunker", "Du er i bunker til " . date_to_text(time_left_in_bunker($_SESSION['ID'], $pdo)), "bunker.svg");
}

if (player_in_jail($_SESSION['ID'], $pdo)) {
    right_alert("?side=fengsel", "Fengsel", "Du er i fengsel", "prison.svg");
}

if (unread_pm($_SESSION['ID'], $pdo) > 0) {
    if (unread_pm($_SESSION['ID'], $pdo) == 1) {
        $meld = unread_pm($_SESSION['ID'], $pdo) . ' ny melding';
    } elseif (unread_pm($_SESSION['ID'], $pdo) > 1) {
        $meld = unread_pm($_SESSION['ID'], $pdo) . ' nye meldinger';
    }
    right_alert("?side=innboks", "Ny melding", "Du har " . $meld . "", "speech-bubble.svg");
}

if (new_notifications($_SESSION['ID'], $pdo) > 0) {
    if (new_notifications($_SESSION['ID'], $pdo) == 1) {
        $meld = new_notifications($_SESSION['ID'], $pdo) . ' ny varsel';
    } elseif (new_notifications($_SESSION['ID'], $pdo) > 1) {
        $meld = new_notifications($_SESSION['ID'], $pdo) . ' nye varsler';
    }
    right_alert("?side=varsel", "Ny varsel", "Du har " . $meld . "", "school-bell.svg");
}

// Høy byskatt
if (get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > 50) {
    echo feedback("Byskatten i denne byen er veldig høy! Det anbefales at du reiser til en by med mindre byskatt.", "fail");
}

// Full garasje
if ($full_garage) {
    echo feedback("Garasjen din er full! Du kan enten kjøpe mer plass på <a href='?side=poeng'>poengsiden</a> eller selge biler i <a href='?side=garasje'>garasjen</a>", "fail");
}

// Fullt lager
if ($full_storage) {
    echo feedback("Lageret ditt er fullt! Du kan enten kjøpe mer plass på <a href='?side=poeng'>poengsiden</a> eller selge ting på <a href='?side=lager'>lageret</a>", "fail");
}

// Konkurranse
if (active_konk($pdo)) {
    right_alert("?side=konk", "Konkurranse", "Aktiv konkurranse nå!", "competition.svg");
}

// superhelg
if (active_superhelg($pdo)) {
    right_alert("?side=superhelg", active_superhelg_title($pdo), "Dobbel EXP & verdi", "confetti.png");
}

// Aktiv energidrikk
if (active_energy_drink($_SESSION['ID'], $pdo)) {
    right_alert("#", "Aktiv energidrikk", "2X EXP til " . date_to_text(energy_drink_time($_SESSION['ID'], $pdo)), "energy-drink.svg");
}

if(date('m') == 12){
    right_alert("?side=julekalender", "Julekalender!", "Åpne julekalenderen for store gaver!", "candy-cane.svg");
    right_alert("?side=gavedryss", "Gavedryss!", "Få julegaver via kriminelle handlinger!", "gift-box.svg");
}

// right_alert("?side=worldCup", "VM I Qatar!", "Sats på kamp og vinn penger!", "soccer.svg");

$hour = date('Hi');
$from = 2000;
$to = 2142;
$isKillTime = $hour > $from && $hour <= $to;
if($isKillTime){
    if(active_drapsfri($pdo)){
        right_alert("?side=drep", "Drapstid", "Det er drapsfri i kveld!", "pigeon.svg");
    } else {
        right_alert("?side=drep", "Drapstid", "Det er drapstid fra 20:00 til 21:00", "skull.png");
    }
}

right_alert("?side=vervekonk", "Vervekonkurranse!", "Vinn gavekort fra komplett på 500kr!", "emoji.svg");

$stmt = $pdo->prepare("SELECT * FROM safe_number");
$stmt->execute();
$row = $stmt->fetch();

$hasWinner = $row['SAFE_winner'] != 0;

if (!$hasWinner) {
    right_alert("?side=dagensSafe", "Dagens safe", "Safen er ikke åpnet for i dag!", "safe.svg");
}
