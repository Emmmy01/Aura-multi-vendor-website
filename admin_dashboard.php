<?php
include 'db.php';
session_start();

// Fetch all sellers
$sellers = $conn->query("SELECT id, name, email FROM sellers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
  <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Seller Management & Notifications</h1>

  <!-- Sellers Table -->
  <div class="overflow-x-auto mb-8">
    <table class="w-full bg-white shadow-md rounded table-auto">
      <thead class="bg-gray-200 text-gray-700">
        <tr>
          <th class="p-3">ID</th>
          <th class="p-3">Name</th>
          <th class="p-3">Email</th>
          <th class="p-3">Status</th>
          <th class="p-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($sellers as $seller): ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-3"><?= $seller['id'] ?></td>
            <td class="p-3"><?= htmlspecialchars($seller['name']) ?></td>
            <td class="p-3"><?= htmlspecialchars($seller['email']) ?></td>
            <td class="p-3"><?= ucfirst($seller['status'] ?? 'pending') ?></td>
            <td class="p-3 space-x-2">
              <?php if (($seller['status'] ?? '') === 'pending'): ?>
                <form method="POST" action="approve_seller.php" class="inline">
                  <input type="hidden" name="seller_id" value="<?= $seller['id'] ?>">
                  <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Approve</button>
                </form>
              <?php else: ?>
                <span class="text-green-600 font-medium">Approved</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>

  <!-- Notification Form -->
  <form action="send-notification.php" method="POST" class="space-y-4 bg-white p-6 rounded shadow-md max-w-lg mx-auto">
    <label class="flex items-center">
      <input type="checkbox" name="send_to_all" id="send_to_all" onchange="toggleSellerSelect(this)" class="form-checkbox h-5 w-5 text-red-600">
      <span class="ml-2 text-gray-700">Send to All Sellers</span>
    </label>

    <div id="sellerSelect">
      <label class="block">
        <span class="text-gray-700">Select Seller</span>
        <select name="seller_id" required class="w-full border-gray-300 rounded p-2 mt-1">
          <?php foreach ($sellers as $seller): ?>
            <option value="<?= $seller['id'] ?>">
              <?= htmlspecialchars($seller['name']) ?> (ID: <?= $seller['id'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>

    <label class="block">
      <span class="text-gray-700">Message</span>
      <textarea name="message" required class="w-full border-gray-300 rounded p-2 mt-1"></textarea>
    </label>

    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
      Send Notification
    </button>
  </form>

  <script>
  function toggleSellerSelect(checkbox) {
    document.getElementById("sellerSelect").style.display = checkbox.checked ? "none" : "block";
  }
  </script>
</body>
</html>
