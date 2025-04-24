<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user delivery info
$stmt = $conn->prepare("SELECT address, city, state, postal_code, phone FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$has_address = !empty($user['address']) || !empty($user['city']) || !empty($user['state']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delivery Address</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f8f9fa, #e0e0e0);
      padding: 40px 20px;
      margin: 0;
    }

    .container {
      max-width: 500px;
      background: #fff;
      margin: auto;
      padding: 30px 25px;
      border-radius: 14px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      margin-bottom: 25px;
      font-size: 1.6rem;
      color: #333;
    }

    p {
      text-align: left;
      margin: 10px 0;
      line-height: 1.6;
      color: #444;
    }

    .label {
      font-weight: bold;
      color: #222;
    }

    .btn {
      display: inline-block;
      margin-top: 30px;
      padding: 12px 25px;
      background:rgb(4, 4, 4);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-size: 0.95rem;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background:rgb(19, 19, 19);
    }

    .no-address {
      font-style: italic;
      color: #777;
    }

    @media (max-width: 600px) {
      .container {
        padding: 25px 20px;
      }

      h2 {
        font-size: 1.4rem;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Your Delivery Address</h2>

    <?php if (!$has_address): ?>
      <p class="no-address">No delivery address saved yet.</p>
      <a href="edit_delivery_address.php" class="btn">Save an Address</a>
    <?php else: ?>
      <p><span class="label">Phone:</span> <?= htmlspecialchars($user['phone']) ?></p>
      <p><span class="label">Address:</span><br><?= nl2br(htmlspecialchars($user['address'])) ?></p>
      <p><span class="label">City:</span> <?= htmlspecialchars($user['city']) ?></p>
      <p><span class="label">State:</span> <?= htmlspecialchars($user['state']) ?></p>
      <p><span class="label">Postal Code:</span> <?= htmlspecialchars($user['postal_code']) ?></p>

      <a href="edit_delivery_address.php" class="btn">Edit Address</a>
    <?php endif; ?>
  </div>

</body>
</html>
