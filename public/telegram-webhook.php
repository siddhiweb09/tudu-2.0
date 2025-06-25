<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kolkata');

// Get JSON input
$jsonData = file_get_contents('php://input');

$data = json_decode($jsonData, true);

// Check if the JSON is valid
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid JSON input',
        'error' => json_last_error_msg(),
    ]);
    exit;
}

// Log the incoming data for debugging (optional)
error_log(print_r($data, true));

$dbHost = "localhost";
$dbUser = "todouser_user1";
$dbPass = "SJ=;bGRb!ip@";
$dbName = "todouser_db1";

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$messageId = $data['message']['message_id'];
$chatId = $data['message']['chat']['id']; 
$username = isset($data['message']['from']['username']) ? $data['message']['from']['username'] : 'Unknown';
$firstName = isset($data['message']['from']['first_name']) ? $data['message']['from']['first_name'] : 'Unknown';
$lastName = isset($data['message']['from']['last_name']) ? $data['message']['from']['last_name'] : 'Unknown';
$messageText = isset($data['message']['text']) ? $data['message']['text'] : '';
$messageDate = isset($data['message']['date']) ? date('Y-m-d H:i:s', $data['message']['date']) : '';
$isReply = isset($data['message']['reply_to_message']);
$isPinned = isset($data['message']['pinned_message']);
$replyFlag = $isReply ? 1 : 0;
$pinnedFlag = $isPinned ? 1 : 0;

// Check if reply message exists
$replyMessageId = isset($data['message']['reply_to_message']['message_id']) ? $data['message']['reply_to_message']['message_id'] : null;
$replyChatId = isset($data['message']['reply_to_message']['chat']['id']) ? $data['message']['reply_to_message']['chat']['id'] : null;
$replyUsername = isset($data['message']['reply_to_message']['from']['username']) ? $data['message']['reply_to_message']['from']['username'] : null;
$replyMessageText = isset($data['message']['reply_to_message']['text']) ? $data['message']['reply_to_message']['text'] : null;
$replyFirstName = isset($data['message']['reply_to_message']['from']['first_name']) ? $data['message']['reply_to_message']['from']['first_name'] : null;
$replyLastName = isset($data['message']['reply_to_message']['from']['last_name']) ? $data['message']['reply_to_message']['from']['last_name'] : null;

$record_data = json_encode($data);

// Initialize media type and file ID
$mediaType = null;
$mediaFileId = null;

// Check for different media types
if (isset($data['message']['photo'])) {
    $mediaType = 'photo';
    $mediaFileId = end($data['message']['photo'])['file_id']; // Get the highest resolution photo
} elseif (isset($data['message']['video'])) {
    $mediaType = 'video';
    $mediaFileId = $data['message']['video']['file_id'];
} elseif (isset($data['message']['audio'])) {
    $mediaType = 'audio';
    $mediaFileId = $data['message']['audio']['file_id'];
} elseif (isset($data['message']['document'])) {
    $mediaType = 'document';
    $mediaFileId = $data['message']['document']['file_id'];
}

if ($mediaFileId) {
    $telegrambotsql = "SELECT botId FROM telegram_rn WHERE name = 'task'";
    $telegrambotresult = $conn->query($telegrambotsql);
    $botToken = '';
    if ($telegrambotresult->num_rows > 0) {
        $telegrambotrow = $telegrambotresult->fetch_assoc();
        $botToken = $telegrambotrow['bot_id'];
        echo "Bot ID: " . $botToken;
    } else {
        echo "No result found";
    }
    // $botToken = '7921587338:AAHSrjhHRohXEUHESn8dagi5SEdXZvnoMUA'; // Replace with your actual bot token
    $apiUrl = "https://api.telegram.org/bot{$botToken}/getFile?file_id={$mediaFileId}";

    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if (isset($data['ok']) && $data['ok'] && isset($data['result']['file_path'])) {
        $filePath = $data['result']['file_path'];
        $downloadUrl = "https://api.telegram.org/file/bot{$botToken}/{$filePath}";

        // Define upload directory
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        $localFileName = uniqid('telegram_') . "." . $fileExtension;
        $localFilePath = $uploadDir . $localFileName;

        // Download and save file
        file_put_contents($localFilePath, file_get_contents($downloadUrl));

        // Save local file path in database instead of Telegram file ID
        $mediaFileId = "uploads/" . $localFileName;
    }
}



if (strpos($messageText, "/start login") === 0) {
    $replyMessage = "Your Chat ID is $chatId.";
    sendTelegramMessage($chatId, $replyMessage, $conn);
}

// Insert data into the database
$sql = "INSERT INTO telegram_webhook_messages (`chat_id`, `message_id`, `name`, `username`, `message_text`, `reply_flag`, `pinned_flag`, `reply_message_id`, `reply_chat_id`, `reply_username`, `reply_name`, `reply_message_text`, `media_type`, `media_file_id`, `message_array_data`, `messageDate`) 
        VALUES ('$chatId', '$messageId', '$firstName', '$username', '$messageText', '$replyFlag', '$pinnedFlag', '$replyMessageId', '$replyChatId', '$replyUsername', '$replyFirstName', '$replyMessageText', '$mediaType', '$mediaFileId', '$record_data', '$messageDate')";

if ($conn->query($sql) === FALSE) {
    // Log the error in the database for debugging
    $error = $conn->error;
    $mysqli_errorQuery = "INSERT INTO `mysqli_error_logs`(`mysqli_error`, `page`) VALUES ('$error','webhook-API')";
    mysqli_query($conn, $mysqli_errorQuery);

    echo json_encode([
        'status' => 'error',
        'message' => 'Database error',
        'error' => $error,
    ]);
    exit;
}

// Check if chat_id exists in `user` or `student` tables
$checkQuery = "SELECT telegram_chat_id FROM users WHERE chat_id = '$chatId'";
$result = mysqli_query($conn, $checkQuery);



if (mysqli_num_rows($result) == 0) {

    $sql4 = "INSERT INTO jd_data (data) VALUES ('true')";
    if ($conn->query($sql4) === TRUE) {
        echo "New record inserted successfully";
    } else {
        echo "Error: " . $sql4 . "<br>" . $conn->error;
    }
    // If chat_id is NOT found in user or student table, send a reply message
    $webAppUrl = "https://university.isbmerp.co.in/telegram-form.php"; // Your Telegram Web App form URL
    $message = "🚀 Please fill in your details below to continue:";

    sendTelegramMessage($chatId, $message, $conn, $webAppUrl);
} else {
    $sql5 = "INSERT INTO jd_data (data) VALUES ('false')";
    if ($conn->query($sql5) === TRUE) {
        echo "New record inserted successfully";
    } else {
        echo "Error: " . $sql5 . "<br>" . $conn->error;
    }
}


// Close the database connection
mysqli_close($conn);

echo json_encode([
    'status' => 'success',
    'message' => 'Form data submitted successfully',
]);

/**
 * Function to send a reply message via Telegram
 */
function sendTelegramMessage($chatId, $replyMessage, $conn, $webAppUrl = null)
{
    $telegrambotsql = "SELECT botId FROM telegram_rn WHERE name = 'task'";
    $telegrambotresult = $conn->query($telegrambotsql);
    $telegramBotToken = '';
    if ($telegrambotresult->num_rows > 0) {
        $telegrambotrow = $telegrambotresult->fetch_assoc();
        $telegramBotToken = $telegrambotrow['bot_id'];
        echo "Bot ID: " . $telegramBotToken;
    } else {
        echo "No result found";
    }

    // $telegramBotToken = "7921587338:AAHSrjhHRohXEUHESn8dagi5SEdXZvnoMUA"; // Replace with your actual bot token
    $url = "https://api.telegram.org/bot$telegramBotToken/sendMessage";

    $postData = [
        'chat_id' => $chatId,
        'text' => $replyMessage,
    ];

    if ($webAppUrl) {
        $postData['reply_markup'] = json_encode([
            'inline_keyboard' => [
                [
                    [
                        'text' => "📝 Fill Form",
                        'web_app' => ['url' => $webAppUrl]
                    ]
                ]
            ]
        ]);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    curl_close($ch);

    // return $response;

    // Decode response JSON
    $responseData = json_decode($response, true);

    $sql11 = "INSERT INTO jd_data (data) VALUES ('$response')";
    if ($conn->query($sql11) === TRUE) {
        error_log("Bot Message ID inserted successfully.");
    } else {
        error_log("Error in SQL11: " . $conn->error);
    }


    if (isset($responseData['ok']) && $responseData['ok'] == true) {
        // Extract bot's message details
        $botMessageId = $responseData['result']['message_id'];
        $botChatId = $responseData['result']['chat']['id'];
        $botMessageText = isset($responseData['result']['text']) ? mysqli_real_escape_string($conn, $responseData['result']['text']) : '';
        $botMessageDate = date('Y-m-d H:i:s', $responseData['result']['date']);
        $message_array_data = mysqli_real_escape_string($conn, json_encode($responseData));

        // Initialize reply fields
        $replyFlag = 0;
        $replyChatId = null;
        $replyMessageId = null;
        $replyUsername = null;
        $replyName = null;
        $replyMessageText = null;

        // Check if this message is a reply
        if (isset($responseData['result']['reply_to_message'])) {
            $replyFlag = 1;
            $replyMessageData = $responseData['result']['reply_to_message'];

            $replyChatId = $botChatId; // Usually the same chat ID
            $replyMessageId = $replyMessageData['message_id'];
            $replyUsername = isset($replyMessageData['from']['username']) ? $replyMessageData['from']['username'] : 'Unknown';
            $replyName = isset($replyMessageData['from']['first_name']) ? $replyMessageData['from']['first_name'] : 'Unknown';
            $replyMessageText = isset($replyMessageData['text']) ? mysqli_real_escape_string($conn, $replyMessageData['text']) : '';
        }

        // Initialize media fields
        $mediaType = '';
        $mediaFileId = '';

        // Check if the message contains an image
        if (isset($responseData['result']['photo'])) {
            $mediaType = 'photo';
            $photos = $responseData['result']['photo'];
            $mediaFileId = end($photos)['file_id']; // Get the highest resolution image
        }
        // Check if the message contains a video
        elseif (isset($responseData['result']['video'])) {
            $mediaType = 'video';
            $mediaFileId = $responseData['result']['video']['file_id'];
        }
        // Check if the message contains a document
        elseif (isset($responseData['result']['document'])) {
            $mediaType = 'document';
            $mediaFileId = $responseData['result']['document']['file_id'];
        }

        // Insert bot message into telegram_webhook_messages table
        $telesql = "INSERT INTO telegram_webhook_messages 
                    (chat_id, message_id, name, username, message_text, reply_flag, reply_chat_id, reply_message_id, reply_username, reply_name, reply_message_text, media_type, media_file_id, message_array_data, messageDate) 
                    VALUES ('$botChatId', '$botMessageId', 'Bot', 'TelegramBot', '$botMessageText', '$replyFlag', '$replyChatId', '$replyMessageId', '$replyUsername', '$replyName', '$replyMessageText', '$mediaType', '$mediaFileId', '$message_array_data', '$botMessageDate')";

        if ($conn->query($telesql) === TRUE) {
            error_log("Bot Message with reply inserted successfully.");
        } else {
            error_log("Error in telesql: " . $conn->error);
        }
    }

    return $response;
}
