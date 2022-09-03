<?php
if (isset($_GET['getNewMessages'], $_GET['heistID'], $_GET['lastRecievedMessage'])) {
    include_once '../../env.php';
    header('Content-type: application/json');

    $sql = "SELECT
                ACC_username AS sender,
                AS_avatar AS avatar,
                HEICHAT_acc_id AS userID,
                HEICHAT_msg_id AS messageID,
                HEICHAT_msg AS message,
                HEICHAT_timestamp AS timestamp
            FROM 
                heist_chat
            INNER JOIN accounts
                ON ACC_id = HEICHAT_acc_id
            INNER JOIN accounts_stat
                ON AS_ID = ACC_ID
            WHERE 
                HEICHAT_heist_id = ?
            AND
                HEICHAT_msg_id > ?";

    $query = $pdo->prepare($sql);
    $query->execute([$_GET['heistID'], $_GET['lastRecievedMessage']]);
    $messages = $query->fetchAll(PDO::FETCH_OBJ) ?? [];

    if ($messages) {
        http_response_code(200); // OK
        $response = [
            'status' => 'Recieved messages',
            'messages' => $messages
        ];
    } else {
        http_response_code(200); // OK - No content
        $response = [
            'status' => 'No new messages',
            'messages' => NULL
        ];
    }

    echo json_encode($response);
    die();
}

$json = file_get_contents('php://input');
$data = json_decode($json);

if (isset($data->message, $data->heistID)) {
    include_once '../../env.php';
    include_once '../../functions/functions.php';

    header('Content-type: application/json');

    // Sanitize message to avoid XSS
    $data->message = htmlspecialchars($data->message);

    // Allow for BB-codes
    $data->message = showBBcodes($data->message);

    // Add linebreaks
    $data->message = nl2br($data->message);

    $sql = "INSERT INTO heist_chat(HEICHAT_acc_id, HEICHAT_heist_id, HEICHAT_msg) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$_SESSION['ID'], $data->heistID, $data->message]);
    } catch (PDOException $e) {
        http_response_code(400); // Bad request - Not created
        die("Ugyldig heist ID, eller bruker ID.\nHeistet er antakelig avsluttet.");
    }

    if ($stmt) {
        http_response_code(201); // OK - Created
        $response = ['status' => 'Message sent'];
        echo json_encode($response);
        die();
    } else {
        http_response_code(400); // Bad request - Not created
        die("Kunne ikke sette sende inn chatmelding..");
    }
}
?>

<div class="chat-container">
    <h4 style="margin-bottom: 10px;">Chat</h4>
    <div id="message-container">
        <span class="loading">
            <noscript style="text-align: center;">
                JavaScript må være påskrudd for å bruke denne chatten..
            </noscript>
        </span>
    </div>
    <div id="postmessage-feedback"></div>
    <form id="post-chat-message" onsubmit="javascript:postMessage(this, event)" method="POST">
        <input type="text" id="message" placeholder="Skriv en melding til gruppa..." autocomplete="off" disabled requred>
        <input type="hidden" name="heistID" id="heistID" value="<?php echo $heist_id ?>">
        <input type="submit" name="sendMessage" value="Send" disabled>
    </form>
</div>

<script>
    const selfID = <?php echo $_SESSION['ID'] ?>;
    let recievedMessages = [0];

    $(document).ready(async () => {
        $('.loading').text('Laster..');

        $('[name="sendMessage"],#message')
            .prop('disabled', false); // Enable inputs

        await getNewMessages(); // Check for all messages first

        // Check for new messages every second
        setInterval(getNewMessages, 1e3);
    });

    /**
     * Gets all new messages for this heist lobby
     */
    const getNewMessages = async () => {
        fetch(`app/heist/heist_chat.php?getNewMessages&lastRecievedMessage=${recievedMessages[0]}&heistID=<?php echo $heist_id ?>`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(async data => {
                if (data.messages) {
                    if (recievedMessages !== [0]) {
                        // Only hide this the first time we recieve messages
                        $('#message-container .loading').hide();
                    }
                    addChatMessages(data.messages);
                } else {
                    if (recievedMessages.length === 1) {
                        // Only show this message if it is the first time
                        $('#message-container .loading').text("Ingen chatmeldinger funnet..");
                    }
                }
            });
    };

    /**
     * Posts a new message to the heist-chat
     */
    const postMessage = async (form, event) => {
        event.preventDefault();
        const msg = $('#message').val();
        const heistID = $("#heistID").val();

        if (!msg || msg === "") {
            new Feedback('Chatmeldingen må inneholde en tekst!', '#postmessage-feedback', 'error', 3000);
            return;
        }

        // Disable the submitter untill the request is handled
        event.submitter.disabled = true;

        await fetch('app/heist/heist_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: msg,
                    heistID: heistID
                })
            })
            .then(response => {
                if (response.ok) return response.json();

                return response.text().then(text => {
                    throw new Error(text)
                });
            })
            .then(async data => {
                form.reset(); // Reset form input
                event.submitter.disabled = false; // Re-enable the submitter
            })
            .catch(err => {
                console.error(err);

                new Feedback(err || "Noe gikk galt ved innsendingen av meldingen", '#postmessage-feedback', 'error', 3000);
                event.submitter.disabled = false; // Re-enable submitter
            });
    };

    /**
     * Adds recieved messages to the chat
     */
    const addChatMessages = messages => {
        messages
            .forEach(message => {
                recievedMessages.unshift(message.messageID);

                let $messageElement = $(`
                    <div id="msg_${message.messageID}" class="chat-message${message.userID == selfID ? ' chat-message-owner' : ''}">
                        <img class="chat-avatar" src="${message.avatar}" alt="${message.sender}'s avatar">
                        <a class="chat-message-sender" href="?side=profil&id=${message.userID}">${message.sender}:</a>
                        <time class="chat-message-time" timestamp="${message.timestamp}">${new Date(message.timestamp * 1000).toLocaleTimeString('nb-NO', {hour: '2-digit', minute: '2-digit', second: '2-digit'})}</time>
                        <p class="chat-message-text">${message.message}</p>
                    </div>
                `);

                // Add the chat message
                $('#message-container').append($messageElement);

                let latestChatBubble = document.getElementById(`msg_${message.messageID}`); // Get the latest chat message
                latestChatBubble.parentElement.scrollTop = latestChatBubble.offsetTop;
            });
    };
</script>
<style>
    .chat-container {
        display: flex;
        flex-direction: column;
        gap: 5px;
        height: 370px;
    }

    .chat-container .loading {
        height: 100%;
        display: grid;
        place-items: center;
    }

    #post-chat-message {
        display: flex;
    }

    #post-chat-message #message {
        width: 100%;
    }

    #message-container {
        background-color: rgb(0 0 0 / .3);
        display: flex;
        flex-direction: column;
        height: 300px;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    #message-container::-webkit-scrollbar {
        width: 5px;
    }

    #message-container::-webkit-scrollbar-track {
        background-color: rgb(0 0 0 / .3);
        border-radius: 100vw;
    }

    #message-container::-webkit-scrollbar-thumb {
        background-color: var(--button-bg-color);
        border-radius: 100vw;
    }

    #message-container {
        scrollbar-color: var(--button-bg-color) rgb(0 0 0 / .3);
        scrollbar-width: 5px;
    }

    .chat-message {
        --bg-color: var(--table-even);
        align-self: start;

        margin: 5px;
        margin-inline: clamp(5px, 2%, 50px);

        display: grid;
        grid-template-columns: minmax(30px, 1fr) 3fr 3fr;
        grid-template-areas:
            "avatar sender time"
            "avatar msg msg";
        column-gap: 15px;
        row-gap: 5px;

        background-color: var(--bg-color);
        border-radius: 10px 10px 10px 0;

        padding: 5px;
        width: clamp(25%, 400px, 85%);

        position: relative;
    }

    .chat-message.chat-message-owner {
        --bg-color: var(--button-bg-color);

        align-self: end;
        border-radius: 8px 8px 0 8px;
        background-color: var(--bg-color);
    }

    .chat-avatar {
        place-self: center;

        grid-area: avatar;
        max-width: 28px;
        max-height: 50%;
        margin-left: 5px;
    }

    .chat-message-sender {
        grid-area: sender;
        font-weight: 600;
    }

    .chat-message-time {
        grid-area: time;
        justify-self: end;
        margin-right: 5px;
    }

    .chat-message-text {
        grid-area: msg;
        margin: 0 5px 5px 0;
        overflow-wrap: anywhere;
    }

    #post-chat-message input:disabled {
        opacity: .5;
        cursor: not-allowed !important;
    }
</style>