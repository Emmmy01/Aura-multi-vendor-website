<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Get total quantity of items
    $stmt = $conn->prepare("SELECT SUM(quantity) AS item_count FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['item_count']) {
        $cart_count = $row['item_count'];
    }
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT p.id AS product_id, p.name, p.price, p.image, p.delivery_fee, p.seller_name, c.color, c.size, c.quantity 
                        FROM cart c 
                        INNER JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll();

// Subtotal
$total = array_sum(array_map(function($item) {
    return $item['price'] * $item['quantity'];
}, $cartItems));

// Collect unique seller delivery fees
$uniqueSellerFees = [];
foreach ($cartItems as $item) {
    $sellerId = $item['seller_name'];
    if (!isset($uniqueSellerFees[$sellerId])) {
        $uniqueSellerFees[$sellerId] = $item['delivery_fee'];
    }
}

$delivery_fee = 0;
$delivery_message = "";

// If total >= 50,000, free delivery
if ($total >= 50000) {
    $delivery_fee = array_sum($uniqueSellerFees);
    $delivery_message = "<span class='text-gray-700'>₦" . number_format($delivery_fee, 2) . "</span>";
} else {
    $delivery_fee = array_sum($uniqueSellerFees);
    $delivery_message = "<span class='text-gray-700'>₦" . number_format($delivery_fee, 2) . "</span>";
}

$grand_total = $total + $delivery_fee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
  <title>Your Cart</title>
</head>
<body class="bg-gray-100 font-sans">
<div class="max-w-7xl mx-auto p-6 mt-10">
  <h1 class="text-3xl font-bold mb-6">Your Cart</h1>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Cart Items -->
    <div class="lg:col-span-2 space-y-6" id="cart-list">
      <?php if (count($cartItems) > 0): ?>
        <?php foreach ($cartItems as $item): ?>
        <div class="block items-center justify-between p-4 bg-white rounded-lg shadow-sm cart-item" data-product-id="<?= $item['product_id'] ?>">
          <div class="flex items-center gap-4">
            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-20 h-30 object-cover rounded">
            <div>
              <h3 class="font-semibold text-sm"><?= htmlspecialchars($item['name']) ?></h3>
              <p class="text-sm text-gray-500"><?= htmlspecialchars($item['color']) ?> | <?= htmlspecialchars($item['size']) ?></p>
              <p class="text-gray-700 mt-1">₦<?= number_format($item['price'], 2) ?></p>
              <button class="text-red-500 hover:text-red-700 delete">Remove</button>
              <div class="flex items-center gap-2">
            <button class="bg-gray-200 px-2 py-1 rounded decrease">-</button>
            <span class="font-medium quantity"><?= $item['quantity'] ?></span>
            <button class="bg-gray-200 px-2 py-1 rounded increase">+</button>
          </div>
            </div>
          </div>
        
          <p class="text-gray-700 font-semibold price-total">₦<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-gray-600">Your cart is empty.</p>
      <?php endif; ?>
    </div>

    <!-- Order Summary -->
    <div class="bg-white rounded-lg shadow-lg p-6" id="order-summary">
  <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

  <div class="flex justify-between mb-2">
    <span>Subtotal</span>
    <span id="subtotal">₦<?= number_format($total, 2) ?></span>
  </div>

  <div class="flex justify-between mb-2">
    <span>Delivery Fee</span>
    <span><?= $delivery_message ?></span> <!-- Moved inside <span> for consistency -->
  </div>

  <div class="flex justify-between mt-4 border-t pt-4 font-bold text-lg">
    <span>Total</span>
    <span id="total">₦<?= number_format($grand_total, 2) ?></span>
  </div>

  <a href="checkout.php" class="block text-center bg-black text-white mt-6 py-3 rounded hover:bg-black-700 transition">
    Checkout
  </a>
</div>

  </div>
</div>

<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white shadow-md border-t border-gray-200 z-50 md:hidden">
  <div class="flex justify-between px-4 py-2 text-xs text-gray-700">
    <a href="index.php" class="flex flex-col items-center <?= $currentPage == 'index.php' ? 'text-red-500' : 'text-gray-500' ?>">
      <i class="fas fa-house text-lg"></i>
      <span>Home</span>
    </a>
    <a href="cart.php" class="relative flex flex-col items-center <?= $currentPage == 'cart.php' ? 'text-red-500' : 'text-gray-500' ?>">
      <i class="fas fa-bag-shopping text-lg"></i>
      <?php if ($cart_count > 0): ?>
      <span class="absolute top-0 left-3 h-4.9 w-4.2 bg-red-500 text-white text-[11px] px-1.5 py-0.5 rounded-full leading-none">
        <?= $cart_count ?>
      </span>
      <?php endif; ?>
      <span class="text-xs mt-0">Cart</span>
    </a>
    <a href="wishlist.php" class="flex flex-col items-center <?= $currentPage == 'wishlist.php' ? 'text-red-500' : 'text-gray-500' ?>">
      <i class="fas fa-star text-lg"></i>
      <span>Wishlist</span>
    </a>
    <a href="useraccount.php" class="flex flex-col items-center <?= $currentPage == 'useraccount.php' ? 'text-red-500' : 'text-gray-500' ?>">
      <i class="fas fa-user-circle text-lg"></i>
      <span>Profile</span>
    </a>
    <a href="seller_dashboard.php" class="flex flex-col items-center <?= $currentPage == 'add_product.php' ? 'text-red-500' : 'text-gray-500' ?>">
      <i class="fas fa-store text-lg"></i>
      <span>Sell</span>
    </a>
  </div>
</nav>
<br><br>

<script>
  const updateCart = (item, action) => {
    const product_id = item.dataset.productId;
    fetch('update_cart.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `action=${action}&product_id=${product_id}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (action === 'delete') {
          item.remove();
        } else {
          item.querySelector('.quantity').textContent = data.new_quantity;
          item.querySelector('.price-total').textContent = '₦' + parseFloat(data.new_price).toLocaleString();
        }

        document.getElementById('subtotal').textContent = '₦' + parseFloat(data.new_total).toLocaleString();
        document.getElementById('total').textContent = '₦' + parseFloat(data.new_total).toLocaleString();

        if (data.new_total === 0) {
          document.getElementById('cart-list').innerHTML = '<p class="text-gray-600">Your cart is empty.</p>';
        }
      }
    });
  };

  document.querySelectorAll('.increase').forEach(btn => {
    btn.addEventListener('click', function () {
      updateCart(this.closest('.cart-item'), 'increase');
    });
  });

  document.querySelectorAll('.decrease').forEach(btn => {
    btn.addEventListener('click', function () {
      updateCart(this.closest('.cart-item'), 'decrease');
    });
  });

  document.querySelectorAll('.delete').forEach(btn => {
    btn.addEventListener('click', function () {
      updateCart(this.closest('.cart-item'), 'delete');
    });
  });
</script>
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
