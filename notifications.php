<?php
include 'db.php';
session_start();

// Simulated logged-in seller ID (replace with session logic in real app)
$seller_id = $_SESSION['seller_name'] ?? 1;

// Get unread notifications count
$stmt = $conn->prepare("SELECT COUNT(*) FROM notifications2 WHERE seller_name = ? AND is_read = 0");
$stmt->execute([$seller_id]);
$unread_count = $stmt->fetchColumn();

// Mark all as read (optional - can be a button instead)
$conn->prepare("UPDATE notifications2 SET is_read = 1 WHERE seller_name = ? AND is_read = 0")->execute([$seller_id]);

// Fetch notifications
$stmt = $conn->prepare("SELECT * FROM notifications2 WHERE seller_name = ? ORDER BY created_at DESC");
$stmt->execute([$seller_id]);
$notifications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen p-4">

  <!-- Notification Icon and Badge -->
  <div class="flex justify-end mb-6">
    <div class="relative inline-block">
      <i class="fa fa-bell text-3xl text-gray-800"></i>
      <?php if ($unread_count > 0): ?>
        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">
          <?= $unread_count ?>
        </span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Notification List -->
  <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Your Notifications</h2>

    <?php if (count($notifications) > 0): ?>
      <ul class="space-y-4">
        <?php foreach ($notifications as $note): ?>
          <li class="p-4 border rounded-lg <?= $note['is_read'] ? 'bg-white' : 'bg-red-50' ?>">
            <p class="text-gray-700"><?= htmlspecialchars($note['message']) ?></p>
            <small class="text-gray-500 block mt-1"><?= date('M d, Y h:i A', strtotime($note['created_at'])) ?></small>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p class="text-gray-500">You have no notifications at this time.</p>
    <?php endif; ?>
  </div>

</body>
</html>
