<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        oi.order_id, oi.product_name, oi.quantity, oi.color, oi.size, oi.price, oi.image,
        o.payment_method, o.created_at
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    WHERE o.user_id = ?
    ORDER BY oi.order_id DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px 20px;
            color: #333;
        }

        h1 {
            text-align: left;
            font-weight: 600;
            font-size: 32px;
            margin-bottom: 30px;
            color:rgb(39, 31, 17);
        }

        .order-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            padding: 24px;
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap;
            align-items: left;
            transition: transform 0.2s ease;
        }

        .order-box:hover {
            transform: translateY(-4px);
        }

        .order-details {
            flex: 1;
            padding-right: 20px;
        }

        .order-details h3 {
            margin: 0 0 12px;
            font-size: 20px;
            color:rgb(96, 94, 94);
        }

        .order-details p {
            margin: 8px 0;
            font-size: 15px;
            line-height: 1.6;
        }

        .order-details strong {
            color: #111827;
        }

        .order-details .price {
            font-weight: bold;
            color: #047857;
        }

        .order-box img {
            max-width: 140px;
            border-radius: 10px;
            object-fit: cover;
            aspect-ratio: 1/1;
        }

        @media (max-width: 768px) {
            .order-box {
                flex-direction: column;
                padding: 20px;
            }

            .order-details {
                padding-right: 0;
                margin-bottom: 1px;
            }

            .order-box img {
                width: 50%;
                max-width: 100%;
                height: auto;
            }
        }

        .empty-message {
            text-align: center;
            font-size: 18px;
            color: #6b7280;
        }
    </style>
</head>
<body>

<h1>Your Orders</h1>

<?php if (!$orders): ?>
    <p class="empty-message">You have not placed any orders yet.</p>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <div class="order-box">
            <div class="order-details">
                <h3>Order #<?= $order['order_id'] ?> <span style="font-weight: normal;">(<?= htmlspecialchars($order['payment_method']) ?>)</span></h3>
                <p><strong>Product:</strong> <?= htmlspecialchars($order['product_name']) ?></p>
                <p><strong>Quantity:</strong> <?= $order['quantity'] ?> &nbsp;&nbsp; <strong>Size:</strong> <?= htmlspecialchars($order['size']) ?> &nbsp;&nbsp; <strong>Color:</strong> <?= htmlspecialchars($order['color']) ?></p>
                <p class="price">₦<?= number_format($order['price'] * $order['quantity'], 2) ?></p>
                <p><strong>Ordered At:</strong> <?= date('F j, Y h:i A', strtotime($order['created_at'])) ?></p>
            </div>
            <img src="<?= $order['image'] ?>" alt="<?= htmlspecialchars($order['product_name']) ?>">
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<!-- Loader -->
<div id="loader" class="fixed inset-0 bg-white z-[9999] flex items-center justify-center">
  <div class="loader animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-black"></div>
</div>

<script>
  // Wait until everything loads
  window.addEventListener("load", function () {
    const loader = document.getElementById("loader");
    loader.style.display = "none";
  });
</script>

<style>
  .loader {
    border-top-color: #f00;
    border-radius: 50%;
  }
</style>

</body>
</html>
