<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
            color: #444;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 8px;
            font-size: 15px;
            background-color: #fafafa;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 15px;
            margin-top: 25px;
            background-color:rgb(13, 13, 13);
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color:rgb(19, 19, 19);
        }

        .order-details {
            max-width: 750px;
            margin: 40px auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .product-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            display: flex;
            gap: 15px;
            align-items: center;
            background-color: #fdfdfd;
        }

        .product-box img {
            width: 100px;
            height: auto;
            border-radius: 8px;
        }

        .product-info {
            flex: 1;
        }

        .whatsapp-link {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #25D366;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .whatsapp-link:hover {
            background-color: #1ebd5f;
        }

        .bank-info {
            margin-top: 10px;
            background-color: #fafafa;
            padding: 10px;
            border-radius: 8px;
        }
        @media (min-width: 600px) {
    .product-box {
      flex-direction: row;
    }

    .product-box img {
      width: 40%;
      height: auto;
    }

    .product-info {
      width: 60%;
    }
  }



  .order-details {
    padding: 1rem;
    max-width: 600px;
    margin: 0 auto;
  }

  .order-details h2 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: bold;
    text-align: center;
  }

  .order-details p {
    margin-bottom: 1.5rem;
    text-align: center;
  }

  .product-box {
    background: #f9f9f9;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
  }


  .product-info {
    padding: 1rem;
  }

  .product-info strong {
    display: block;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
  }

  .bank-info {
    margin-top: 0.75rem;
    font-size: 0.95rem;
    color: #333;
  }

  .whatsapp-link {
    display: inline-block;
    margin-top: 1rem;
    background: #25D366;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease;
  }

  .whatsapp-link:hover {
    background: #1ebe5d;
  }

  @media (min-width: 600px) {
    .product-box {
      flex-direction: row;
    }

 

    .product-info {
      width: 60%;
    }
  }
    </style>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $delivery_fee = $_POST['delivery_fee'] ?? 0;

    $stmt = $conn->prepare("SELECT p.id AS product_id, p.name, p.price, p.image, p.delivery_fee, c.color, c.size, c.quantity,
                                   p.seller_bank_name, p.seller_account_name, p.seller_account_number, p.seller_whatsapp
                            FROM cart c 
                            INNER JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll();

    if (!$cartItems) {
        echo "<h3 style='text-align:center;'>Your cart is empty.</h3>";
        exit;
    }

    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $grand_total = $total + $delivery_fee;

    // Save order with delivery_fee
    $stmt = $conn->prepare("INSERT INTO orders (user_id, name, phone, address, payment_method, total_price, delivery_fee)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $name, $phone, $address, $payment_method, $grand_total, $delivery_fee]);
    $order_id = $conn->lastInsertId();

    $insertItem = $conn->prepare("INSERT INTO order_items 
        (order_id, product_id, product_name, quantity, color, size, price, image, 
         seller_bank_name, seller_account_name, seller_account_number, seller_whatsapp, delivery_fee )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($cartItems as $item) {
        $insertItem->execute([
            $order_id,
            $item['product_id'],
            $item['name'],
            $item['quantity'],
            $item['color'],
            $item['size'],
            $item['price'],
            $item['image'],
            $item['seller_bank_name'],
            $item['seller_account_name'],
            $item['seller_account_number'],
            $item['seller_whatsapp'],
            $item['delivery_fee']
        ]);
    }

    $conn->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);

    echo "<div class='order-details'>";
    if ($payment_method === 'cash') {
        $item = $cartItems[0]; // First item for contact
        $amount = $item['price'] * $item['quantity'] + $item['delivery_fee'];
        echo "<h2>Order Placed Successfully!</h2>
         <p>Cash on Delivery selected. Your order has been placed and sellers will be notified.</p>
        <div class='product-box'>
                <img src='{$item['image']}' alt='{$item['name']}'>
                <div class='product-info'>
                    <strong>{$item['name']}</strong><br>
                    Qty: {$item['quantity']} | Size: {$item['size']} | Color: {$item['color']}<br>
                    <div class='bank-info'>
                        Product Total: ₦" . number_format($amount, 2) . "<br>
                    </div>
       
      <a class='whatsapp-link' href='https://wa.me/{$item['seller_whatsapp']}?text=" . urlencode("Hello, I just made an order (Cash on delivery) for {$item['name']}.\nName: $name\nPhone: $phone\nAddress: $address\nTotal (incl. Delivery): ₦" . number_format($amount + $delivery_fee, 2)) . "' target='_blank'>Notify Seller via WhatsApp</a>
      </div>";
    } else {
        echo "<h2>Bank Transfer Details</h2>";
        foreach ($cartItems as $item) {
            $amount = $item['price'] * $item['quantity'] + $item['delivery_fee'];
            echo "<div class='product-box'>
                <img src='{$item['image']}' alt='{$item['name']}'>
                <div class='product-info'>
                    <strong>{$item['name']}</strong><br>
                    Qty: {$item['quantity']} | Size: {$item['size']} | Color: {$item['color']}<br>
                    <div class='bank-info'>
                        Product Total: ₦" . number_format($amount, 2) . "<br>
       
                        <strong>Bank:</strong> {$item['seller_bank_name']}<br>
                        <strong>Account Name:</strong> {$item['seller_account_name']}<br>
                        <strong>Account Number:</strong> {$item['seller_account_number']}
                    </div>
                    <a class='whatsapp-link' href='https://wa.me/{$item['seller_whatsapp']}?text=" . urlencode("Hello, I just made payment for {$item['name']}.\nName: $name\nPhone: $phone\nAddress: $address\nTotal (incl. Delivery): ₦" . number_format($amount + $delivery_fee, 2)) . "' target='_blank'>Send Receipt via WhatsApp</a>
                </div>
            </div>";
        }
    }
    echo "</div>";
    exit;
}
?>

<div class="container">
    <h2>Checkout</h2>
    <form action="checkout.php" method="POST">
        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Phone Number</label>
        <input type="text" name="phone" required>

        <label>Delivery Address</label>
        <textarea name="address" rows="3" required></textarea>

        <label>Payment Method</label>
        <select name="payment_method" required>
            <option value="">Select Payment Method</option>
            <option value="transfer">Bank Transfer</option>
            <option value="cash">Cash on Delivery</option>
        </select>

        <button type="submit">Place Order</button>
    </form>
</div>
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
