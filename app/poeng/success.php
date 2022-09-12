<?php

include '../../env.php';

session_start();
error_reporting(1);

try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}

$stripe_public_key = $stripe_public;
$stripe_private_key = $stripe_private;
\Stripe\Stripe::setApiKey($stripe_private_key);

require(__DIR__ . '/lib/init.php');

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event_json = json_decode($input);

// Verify the event by fetching it from Stripe
$event = \Stripe\Event::retrieve($event_json->id);

// Event on subscription canceled
if ($event->type == 'payment_intent.succeeded') {

    $event->id;                       //  Event ID
    $event->created;                  //  Event Timestamp
    $event->data->object->amount;     //  Amount in cents
    $event->data->object->id;         //  Payment id ID
    $event->data->object->customer;   //  Customer ID
    $event->data->object->created;    //  Payment Timestamp
    $event->data->object->status;     //  succeeded or failed
    // Plan Detail also availabe

    $session = $event->data->object;
    // Fulfill the purchase...
    // handle_checkout_session($session);

}

if ($event->type == 'checkout.session.completed') {
    $event->id;                       //  Event ID
    $event->created;                  //  Event Timestamp
    $event->data->object->amount;     //  Amount in cents
    $event->data->object->id;         //  Payment id ID
    $event->data->object->customer;   //  Customer ID
    $event->data->object->created;    //  Payment Timestamp
    $event->data->object->status;     //  succeeded or failed

    $string = $event->data->object->client_reference_id;
    $values_ary = explode(':', $string);
    $PRO_name =     $values_ary[0];
    $session_id =   $values_ary[1];

    $sql = "UPDATE accounts_stat SET AS_points = (AS_points + ?) WHERE AS_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$PRO_name, $session_id]);

    $id =   $event->id;                       //  Event ID

    $sql = "INSERT INTO poeng_payments (stripe_reference, buyer, amount, payment_info) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $session_id, $PRO_name, time()]);
}

// This code means reponse is success.
// If you have any error in code and below code don't run means response failed.
// Stripe will send response again.
http_response_code(200); // PHP 5.4 or greater
