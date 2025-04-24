<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['product_id'] ?? null;

if (!$product_id) {
    die("Product not specified.");
}

// Try to find or create a chat
$stmt = $conn->prepare("SELECT id FROM chats WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user_id, $product_id]);
$chat = $stmt->fetch();

if ($chat) {
    $chat_id = $chat['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO chats (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $product_id]);
    $chat_id = $conn->lastInsertId();
}

// Get messages
$stmt = $conn->prepare("SELECT * FROM chat_messages WHERE chat_id = ? ORDER BY created_at ASC");
$stmt->execute([$chat_id]);
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Chat</title>
  <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
  <style>
    body { font-family: sans-serif; max-width: 600px; margin: 20px auto; }
    #chatBox { border: 1px solid #ccc; height: 300px; overflow-y: auto; padding: 10px; }
    .msg { margin: 6px 0; }
    form { display: flex; margin-top: 10px; }
    input { flex: 1; padding: 8px; }
    button { padding: 8px 12px; }
  </style>
</head>
<body>
  <h2>Chat</h2>
  <div id="chatBox">
    <?php foreach ($messages as $m): ?>
      <div class="msg">
        <strong><?= htmlspecialchars($m['sender']) ?>:</strong>
        <?= htmlspecialchars($m['message']) ?>
      </div>
    <?php endforeach; ?>
  </div>

  <form id="chatForm" method="POST" action="send-message.php">
    <input type="text" id="message" placeholder="Type your message…" required>
    <button type="submit">Send</button>
  </form>

  <script>
    const chatId = <?= $chat_id ?>;
    
    // Initialize Pusher
    const pusher = new Pusher('4cc374d32f9266493ece', {
      cluster: 'mt1',
      forceTLS: true
    });

    // Subscribe to the chat channel
    const channel = pusher.subscribe('chat-' + chatId);

    // Listen for new messages
    channel.bind('new-message', function(data) {
      const div = document.createElement('div');
      div.className = 'msg';
      div.innerHTML = `<strong>${data.sender}:</strong> ${data.message}`;
      document.getElementById('chatBox').appendChild(div);
      document.getElementById('chatBox').scrollTop = document.getElementById('chatBox').scrollHeight;
    });

    // Handle form submission (send message)
    document.getElementById('chatForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const message = document.getElementById('message').value.trim();
      if (!message) return;

      fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `chat_id=${chatId}&message=${encodeURIComponent(message)}`
      })
      .then(r => r.json())
      .then(data => {
        if (data.status === 'ok') {
          document.getElementById('message').value = '';
        } else {
          alert(data.message || 'Error sending message');
        }
      });
    });
  </script>
  
</body>
</html>
