<?php
session_start();
require 'db.php';
require __DIR__ . '/vendor/autoload.php';

if (!isset($_SESSION['user_id'])) {
    die('User not logged in');
}

$user_id = $_SESSION['user_id'];
$chat_id = $_POST['chat_id'];
$message = $_POST['message'] ?? null;

if (!$message || !$chat_id) {
    die('Message or chat_id not specified');
}

// Insert message into database
$stmt = $conn->prepare("INSERT INTO chat_messages (chat_id, sender, message) VALUES (?, ?, ?)");
$stmt->execute([$chat_id, 'User', $message]); // Use actual sender info

// Pusher initialization
$options = array(
  'cluster' => 'mt1',
  'useTLS' => true
);

$pusher = new Pusher\Pusher(
  '4cc374d32f9266493ece',
  'a8f8be4e9fce987d988d',
  '1978098',
  $options
);

// Trigger a new message event on Pusher channel
$data['sender'] = 'User'; // Change to the sender's name dynamically
$data['message'] = $message;

$pusher->trigger('chat-' . $chat_id, 'new-message', $data);

// Return success response
echo json_encode(['status' => 'ok']);
