<?php

function get_card($value)
{
  switch ($value) {
    case '0':
      return 'Ace of Hearts';
      break;
    case '1':
      return 'Ace of Clubs';
      break;
    case '2':
      return 'Ace of Diamonds';
      break;
    case '3':
      return 'Ace of Spades';
      break;
    case '4':
      return 'Two of Hearts';
      break;
    case '5':
      return 'Two of Clubs';
      break;
    case '6':
      return 'Two of Diamonds';
      break;
    case '7':
      return 'Two of Spades';
      break;
    case '8':
      return 'Three of Hearts';
      break;
    case '9':
      return 'Three of Clubs';
      break;
    case '10':
      return 'Three of Diamonds';
      break;
    case '11':
      return 'Three of Spades';
      break;
    case '12':
      return 'Four of Hearts';
      break;
    case '13':
      return 'Four of Clubs';
      break;
    case '14':
      return 'Four of Diamonds';
      break;
    case '15':
      return 'Four of Spades';
      break;
    case '16':
      return 'Five of Hearts';
      break;
    case '17':
      return 'Five of Clubs';
      break;
    case '18':
      return 'Five of Diamonds';
      break;
    case '19':
      return 'Five of Spades';
      break;
    case '20':
      return 'Six of Hearts';
      break;
    case '21':
      return 'Six of Clubs';
      break;
    case '22':
      return 'Six of Diamonds';
      break;
    case '23':
      return 'Six of Spades';
      break;
    case '24':
      return 'Seven of Hearts';
      break;
    case '25':
      return 'Seven of Clubs';
      break;
    case '26':
      return 'Seven of Diamonds';
      break;
    case '27':
      return 'Seven of Spades';
      break;
    case '28':
      return 'Eight of Hearts';
      break;
    case '29':
      return 'Eight of Clubs';
      break;
    case '30':
      return 'Eight of Diamonds';
      break;
    case '31':
      return 'Eight of Spades';
      break;
    case '32':
      return 'Nine of Hearts';
      break;
    case '33':
      return 'Nine of Clubs';
      break;
    case '34':
      return 'Nine of Diamonds';
      break;
    case '35':
      return 'Nine of Spades';
      break;
    case '36':
      return 'Ten of Hearts';
      break;
    case '37':
      return 'Ten of Clubs';
      break;
    case '38':
      return 'Ten of Diamonds';
      break;
    case '39':
      return 'Ten of Spades';
      break;
    case '40':
      return 'Jack of Hearts';
      break;
    case '41':
      return 'Jack of Clubs';
      break;
    case '42':
      return 'Jack of Diamonds';
      break;
    case '43':
      return 'Jack of Spades';
      break;
    case '44':
      return 'Queen of Hearts';
      break;
    case '45':
      return 'Queen of Clubs';
      break;
    case '46':
      return 'Queen of Diamonds';
      break;
    case '47':
      return 'Queen of Spades';
      break;
    case '48':
      return 'King of Hearts';
      break;
    case '49':
      return 'King of Clubs';
      break;
    case '50':
      return 'King of Diamonds';
      break;
    case '51':
      return 'King of Spades';
  }
}

$cards = array(
  "AH", "AC", "AD", "AS",
  "2H", "2C", "2D", "2S",
  "3H", "3C", "3D", "3S",
  "4H", "4C", "4D", "4S",
  "5H", "5C", "5D", "5S",
  "6H", "6C", "6D", "6S",
  "7H", "7C", "7D", "7S",
  "8H", "8C", "8D", "8S",
  "9H", "9C", "9D", "9S",
  "10H", "10C", "10D", "10S",
  "JH", "JC", "JD", "JS",
  "QH", "QC", "QD", "QS",
  "KH", "KC", "KD", "KS"
);

$cards_v2 = array(
  "AH", "AC", "AD", "AS",
  "2H", "2C", "2D", "2S",
  "3H", "3C", "3D", "3S",
  "4H", "4C", "4D", "4S",
  "5H", "5C", "5D", "5S",
  "6H", "6C", "6D", "6S",
  "7H", "7C", "7D", "7S",
  "8H", "8C", "8D", "8S",
  "9H", "9C", "9D", "9S",
  "TH", "TC", "TD", "TS",
  "JH", "JC", "JD", "JS",
  "QH", "QC", "QD", "QS",
  "KH", "KC", "KD", "KS"
);

$card_value = array(
  11, 11, 11, 11,
  2, 2, 2, 2,
  3, 3, 3, 3,
  4, 4, 4, 4,
  5, 5, 5, 5,
  6, 6, 6, 6,
  7, 7, 7, 7,
  8, 8, 8, 8,
  9, 9, 9, 9,
  10, 10, 10, 10,
  10, 10, 10, 10,
  10, 10, 10, 10,
  10, 10, 10, 10
);

$card_value_with_ace = array(
  1, 1, 1, 1,
  2, 2, 2, 2,
  3, 3, 3, 3,
  4, 4, 4, 4,
  5, 5, 5, 5,
  6, 6, 6, 6,
  7, 7, 7, 7,
  8, 8, 8, 8,
  9, 9, 9, 9,
  10, 10, 10, 10,
  10, 10, 10, 10,
  10, 10, 10, 10,
  10, 10, 10, 10
);

function get_card_value($card, $total_value)
{
  if ($total_value >= 21) {
    $card_val[0] = 1;
    $card_val[1] = 1;
    $card_val[2] = 1;
    $card_val[3] = 1;
  } else {
    $card_val[0] = 11;
    $card_val[1] = 11;
    $card_val[2] = 11;
    $card_val[3] = 11;
  }

  $card_val[4] = 2;
  $card_val[5] = 2;
  $card_val[6] = 2;
  $card_val[7] = 2;

  $card_val[8] = 3;
  $card_val[9] = 3;
  $card_val[10] = 3;
  $card_val[11] = 3;

  $card_val[12] = 4;
  $card_val[13] = 4;
  $card_val[14] = 4;
  $card_val[15] = 4;

  $card_val[16] = 5;
  $card_val[17] = 5;
  $card_val[18] = 5;
  $card_val[19] = 5;

  $card_val[20] = 6;
  $card_val[21] = 6;
  $card_val[22] = 6;
  $card_val[23] = 6;

  $card_val[24] = 7;
  $card_val[25] = 7;
  $card_val[26] = 7;
  $card_val[27] = 7;

  $card_val[28] = 8;
  $card_val[29] = 8;
  $card_val[30] = 8;
  $card_val[31] = 8;

  $card_val[32] = 9;
  $card_val[33] = 9;
  $card_val[34] = 9;
  $card_val[35] = 9;

  $card_val[36] = 10;
  $card_val[37] = 10;
  $card_val[38] = 10;
  $card_val[39] = 10;
  $card_val[40] = 10;
  $card_val[41] = 10;
  $card_val[42] = 10;
  $card_val[43] = 10;
  $card_val[44] = 10;
  $card_val[45] = 10;
  $card_val[46] = 10;
  $card_val[47] = 10;
  $card_val[48] = 10;
  $card_val[49] = 10;
  $card_val[50] = 10;
  $card_val[51] = 10;
  $card_val[52] = 10;

  return $card_val[$card];
}

function end_game()
{
  $_SESSION['user_hand'] = [];
  $_SESSION['dealer_hand'] = [];
  $_SESSION['used_cards'] = [];
}


function start_blackjack($id, $bet, $pdo)
{
  $sql = "INSERT INTO blackjack_active (BJ_acc_id, BJ_status, BJ_bet) VALUES (?,?,?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id, 0, $bet]);
}

function end_blackjack($id, $pdo)
{
  $sql = "UPDATE blackjack_active SET BJ_status = 1 WHERE BJ_acc_id='" . $id . "'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
}

function get_bj_status($id, $pdo)
{
  $query = $pdo->prepare("SELECT BJ_status FROM blackjack_active WHERE BJ_acc_id = ?");
  $query->execute(array($id));
  $BJ_status = $query->fetch(PDO::FETCH_ASSOC);

  return $BJ_status['BJ_status'] ?? NULL;
}

function get_bj_bet($id, $pdo)
{
  $query = $pdo->prepare("SELECT BJ_bet FROM blackjack_active WHERE BJ_acc_id = ?");
  $query->execute(array($id));
  $BJ_status = $query->fetch(PDO::FETCH_ASSOC);

  return $BJ_status['BJ_bet'] ?? NULL;
}

function delete_active_bj($id, $pdo)
{
  $sql = "DELETE FROM blackjack_active WHERE BJ_acc_id = $id";
  $pdo->exec($sql);
}

function blackjack_exist($id, $pdo)
{
  $query = $pdo->prepare("SELECT * FROM blackjack_active WHERE BJ_acc_id = ?");
  $query->execute(array($id));
  $BJ_status = $query->fetch(PDO::FETCH_ASSOC);

  if ($BJ_status) {
    return true;
  } else {
    return false;
  }
}


function blackjack_owner_exist($city, $pdo)
{
  $stmt = $pdo->prepare("SELECT count(*) FROM blackjack_owner WHERE BJO_city = ?");
  $stmt->execute([$city]);
  $count = $stmt->fetchColumn();

  if ($count == 0) {
    return false;
  } else {
    return true;
  }
}

function money_in_blackjack($city, $pdo)
{
  $query = $pdo->prepare("SELECT BJO_bank FROM blackjack_owner WHERE BJO_city = ?");
  $query->execute(array($city));
  $row = $query->fetch(PDO::FETCH_ASSOC);

  return $row['BJO_bank'] ?? NULL;
}

function get_maxbet($city, $pdo)
{
  $query = $pdo->prepare("SELECT BJO_maksbet FROM blackjack_owner WHERE BJO_city = ?");
  $query->execute(array($city));
  $row = $query->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    return $row['BJO_maksbet'] ?? NULL;
  } else {
    return false;
  }
}

function blackjack_owner_amount($id, $pdo)
{
  $stmt = $pdo->prepare("SELECT count(*) FROM blackjack_owner WHERE BJO_owner = ?");
  $stmt->execute([$id]);
  $count = $stmt->fetchColumn();

  if ($count > 0) {
    return true;
  } else {
    return false;
  }
}

function blackjack_owner_incity($city, $pdo)
{
  $query = $pdo->prepare("SELECT BJO_owner FROM blackjack_owner WHERE BJO_city = ?");
  $query->execute(array($city));
  $row = $query->fetch(PDO::FETCH_ASSOC);

  return $row['BJO_owner'] ?? NULL;
}
