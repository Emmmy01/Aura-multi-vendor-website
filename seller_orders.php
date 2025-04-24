<?php
session_start();
include 'db.php';

if (!isset($_SESSION['seller_name'])) {
    header("Location: login.php");
    exit;
}

$seller_id = $_SESSION['seller_name'];

$stmt = $conn->prepare("
    SELECT 
        oi.order_id, oi.product_name, oi.quantity, oi.color, oi.size, oi.price, oi.image,
        o.name AS buyer_name, o.phone AS buyer_phone, o.address AS buyer_address, o.payment_method, o.created_at
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
    WHERE p.seller_name = ?
    ORDER BY oi.order_id DESC
");
$stmt->execute([$seller_id]);
$orders = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Orders</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 40px 0;
            font-weight: 600;
            font-size: 24px;
        }

        .order-box {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .order-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .order-box img {
            width:220px;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            margin-left: 20px;
            margin-top: 10px;
        }

        .order-details {
            flex: 1;
            padding-right: 20px;
            margin-top: 10px;
        }

        .order-details h3 {
            margin: 0 0 15px;
            color:rgb(96, 96, 96);
            font-size: 18px;
        }

        .order-details p {
            margin: 8px 0;
            font-size: 16px;
            line-height: 1.6;
            color:rgb(80, 78, 78);
        }

        .order-details p strong {
            color: #111;
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 15px 0;
        }

        .no-orders {
            text-align: center;
            font-size: 18px;
            color: #888;
        }

        @media (max-width: 768px) {
            .order-box {
                flex-direction: column;
                align-items: left;
            }

            .order-box img {
                width: 100%;
                max-width: 150px;
                margin: 0;
            }

            .order-details {
                padding-right: 0;
                margin-top: 15px;
            }
        }

        .order-details p a {
            text-decoration: none;
            color: #1d4ed8;
        }
        
#notification-toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #1d4ed8;
    color: white;
    padding: 15px 25px;
    border-radius: 10px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    font-weight: 500;
    z-index: 999;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.4s ease, transform 0.4s ease;
    transform: translateY(20px);
}
#notification-toast.show {
    opacity: 1;
    pointer-events: auto;
    transform: translateY(0);
}
#notification-toast {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: black;
  color: white;
  padding: 15px 25px;
  border-radius: 5px;
  z-index: 9999;
  font-size: 16px;
}

#notification-toast.show {
  display: block;
  animation: fadeInOut 5s;
}

@keyframes fadeInOut {
  0% {opacity: 0;}
  10% {opacity: 1;}
  90% {opacity: 1;}
  100% {opacity: 0;}
}



    </style>
</head>
<body>

<h1>Your Orders</h1>
<div id="notification-toast">🔔 New order received!</div>
<audio id="notify-sound" src="https://assets.mixkit.co/sfx/preview/mixkit-bell-notification-933.mp3" preload="auto"></audio>

<?php if (!$orders): ?>
    <p class="no-orders">No orders for your products yet.</p>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <div class="order-box">
            <div class="order-details">
                <h3>Order #<?= $order['order_id'] ?> (<?= htmlspecialchars($order['payment_method']) ?>)</h3>
                <p><strong>Product:</strong> <?= htmlspecialchars($order['product_name']) ?></p>
                <p><strong>Quantity:</strong> <?= $order['quantity'] ?> | <strong>Size:</strong> <?= htmlspecialchars($order['size']) ?> | <strong>Color:</strong> <?= htmlspecialchars($order['color']) ?></p>
                <p><strong>Total Price:</strong> ₦<?= number_format($order['price'] * $order['quantity'], 2) ?></p>

                <hr>

                <p><strong>Buyer:</strong> <?= htmlspecialchars($order['buyer_name']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($order['buyer_phone']) ?></p>
                <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['buyer_address'])) ?></p>
                <p><strong>Ordered At:</strong> <?= $order['created_at'] ?></p>
            </div>
            <img src="<?= $order['image'] ?>" alt="<?= htmlspecialchars($order['product_name']) ?>">
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
let lastOrderId = <?php echo isset($orders[0]['order_id']) ? (int)$orders[0]['order_id'] : 0; ?>;

function showNotification() {
    const toast = document.getElementById("notification-toast");
    toast.classList.add("show");

    const sound = document.getElementById("notify-sound");
    if (sound) sound.play();

    setTimeout(() => {
        toast.classList.remove("show");
    }, 5000);
}

function checkNewOrders() {
    fetch('check_new_orders.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.latest_order_id > lastOrderId) {
                lastOrderId = data.latest_order_id;
                showNotification();
            }
        })
        .catch(error => console.error('Error checking orders:', error));
}

setInterval(checkNewOrders, 10000); // every 10 seconds
</script>
</body>
</html>
