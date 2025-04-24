<?php

include 'db.php';
session_start();

// Check if seller_name is set
if (!isset($_SESSION['seller_name'])) {
  header("Location: login.php");
  exit;
}
$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // SUM the quantity instead of COUNTing rows
    $stmt = $conn->prepare("SELECT SUM(quantity) AS item_count FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['item_count']) {
        $cart_count = $row['item_count'];
    }
}
// Check if seller still exists in the database
$sellerName = $_SESSION['seller_name'];
$stmt = $conn->prepare("SELECT * FROM sellers WHERE name = ?");
$stmt->execute([$sellerName]);
$seller = $stmt->fetch();

if (!$seller) {
  // Seller no longer exists — destroy session and redirect
  session_destroy();
  header("Location: login.php");
  exit;
}

// ✅ Get seller ID from the seller info
$seller_id = $seller['id'];
if (isset($_GET['delete'])) {
  $product_id = $_GET['delete'];

  // 1. Delete product from carts
  $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
  $stmt->execute([$product_id]);

  // 2. Delete product itself
  $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_name = ?");
  $stmt->execute([$product_id, $sellerName]);

  header("Location: seller_dashboard.php");
  exit;
}

// Check subscription validity
$stmt = $conn->prepare("SELECT id, subscribed_at FROM sellers WHERE name = ?");

$stmt->execute([$_SESSION['seller_name']]);
$seller = $stmt->fetch();

if ($seller && $seller['subscribed_at']) {
    $subscribedAt = new DateTime($seller['subscribed_at']);
    $expiryDate = clone $subscribedAt;
    $expiryDate->modify('+30 days');
    $now = new DateTime();

    if ($now > $expiryDate) {
        // Subscription expired
        echo "<p style='color:red; text-align:center;'>Your subscription has expired. Please <a href='payment.php?seller_id={$seller['id']}'>renew here</a> to continue.</p>";
        exit; // Stop page from loading further
    }
} else {
    // No subscription found
    echo "<p style='color:red; text-align:center;'>No active subscription found. Please <a href='payment.php?seller_id={$seller['id']}'>subscribe here</a>.</p>";
    exit;
}

// ✅ Now fetch products
$query = $conn->prepare("SELECT * FROM products WHERE seller_name = ?");
$query->execute([$sellerName]);
$products = $query;

?>


<!DOCTYPE html>
<html>
<head>
  <title>Seller Dashboard</title>
  <link
  rel="stylesheet"
  
  href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.tailwindcss.com"></script>

<script>
  lucide.createIcons(); // Load icons
</script>


<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#ef4444',
            light: '#F4F4F5',
          }
        }
      }
    }
  </script>
</head>
<style>
  /* Adjust the size and position of the badge */
.relative {
  position: relative;
}

a {
  display: inline-block;
}

.fas.fa-bell {
  font-size: 20px; /* Adjust size of the bell icon */
}

.bg-red-600 {
  background-color: #e53e3e;
}

.text-white {
  color: white;
}

.text-xs {
  font-size: 12px;
}

.px-2 {
  padding-left: 90px;
  padding-right: 8px;
}

.py-1 {
  padding-top: 4px;
  padding-bottom: 4px;
}

.rounded-full {
  border-radius: 9999px; /* Full circle */
}

.absolute {
  position: absolute;
}

.-top-3 {
  top: 12px;
}

.-right-3 {
  right: -12px;
}

</style>
<body class="bg-gray-100 text-gray-800">

  <!-- Mobile Topbar -->
  <div class=" p-4 bg-white shadow sticky top-0 z-40 flex justify-between items-center">
      <button onclick="toggleSidebar()" class="lg:hidden text-2xl">
    ☰
  </button>
    <h1 class="text-lg font-semibold">Hi, <?= htmlspecialchars($sellerName) ?> 👋</h1>
    <a href="upload_product.html" class="bg-primary text-white px-3 py-1.5 rounded text-sm hover:bg-red-500">+ Add</a>
  </div>

  <div class="flex flex-col lg:flex-row min-h-screen">

    <!-- Sidebar (hidden on mobile) -->
    <aside class="hidden lg:block w-64 bg-white shadow-lg p-6">
      <h2 class="text-xl font-bold mb-6">🛍️ Seller Panel</h2>
      <nav class="space-y-3">
        <a href="#" class="block font-medium text-gray-700 hover:text-primary">Dashboard</a>
        <a href="seller_orders.php" class="block font-medium text-gray-700 hover:text-primary">Orders</a>
        <a href="#" class="block font-medium text-gray-700 hover:text-primary">Products</a>
        <a href="view-requests.php" class="block font-medium text-gray-700 hover:text-primary">Requests</a>
        <a href="analytics.php" class="block font-medium text-gray-700 hover:text-primary">Analytics</a>

        <a href="logout2.php" class="block font-medium text-red-600 hover:text-red-800">Logout</a>

      </nav>
    </aside>
<!-- Sidebar Overlay & Panel (Mobile Only) -->
<div id="sidebarWrapper" class="fixed inset-0 z-50 hidden lg:hidden">
  <!-- Overlay -->
  <div onclick="closeSidebar()" class="absolute inset-0 bg-black bg-opacity-30"></div>

  <!-- Sidebar Panel -->
  <div id="mobileSidebar" class="relative bg-white w-84 p-6 shadow-lg transform -translate-x-full transition-transform duration-300">
    <h2 class="text-xl font-bold mb-6">🛍️ Seller Panel</h2>
    <nav class="space-y-3">
      <a href="#" class="block font-medium text-gray-700 hover:text-primary">Dashboard</a>
      <a href="seller_orders.php" class="block font-medium text-gray-700 hover:text-primary">Orders</a>
      <a href="#" class="block font-medium text-gray-700 hover:text-primary">Products</a>
      <a href="view-requests.php" class="block font-medium text-gray-700 hover:text-primary">Requests</a>
      <a href="analytics.php" class="block font-medium text-gray-700 hover:text-primary">Analytics</a>
      <a href="logout2.php" class="block font-medium text-red-600 hover:text-red-800">Logout</a>
    </nav>
  </div>
</div>



<ul id="notif-list" style="list-style: none; padding-left: 0;"></ul>

    <!-- Main Content -->
    <main class="flex-1 p-4 pb-24 lg:p-6 lg:pb-6">
    <?php
// Display days left — separate from the block/redirect logic
$daysLeft = (new DateTime())->diff($expiryDate)->format('%a');
echo "<p class='text-sm text-green-700 text-center'>You have <strong>$daysLeft</strong> day(s) left in your subscription.</p>";
?>

      <!-- Filters (Responsive) -->
      <div class="flex flex-wrap items-center gap-3 mb-4">
        <select class="border p-2 rounded text-sm flex-1">
          <option>All Categories</option>
        </select>
     
        <select class="border p-2 rounded text-sm flex-1">
          <option>Price Range</option>
        </select>
        <input type="text" class="border p-2 rounded flex-1 text-sm" placeholder="Search product..." />
      </div>
  <!-- Notification Icon and Badge -->
<div class="flex justify-end mb-6">

<div class="relative inline-block">
<a href="notifications.php" >
  <i class="fa fa-bell text-2xl text-gray-800"></i>

  <?php
  $stmt = $conn->prepare("SELECT COUNT(*) FROM notifications2 WHERE seller_name = ? AND is_read = 0");
  $stmt->execute([$_SESSION['seller_name']]);
  $count = $stmt->fetchColumn();
  if ($count > 0):
?>
<span class="absolute -top-1 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.9 rounded-full animate-pulse">
  <?= $count ?>
</span>
<?php endif; ?>
</a>



</div>
</div>


      <!-- Products Table -->
      <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-100 text-xs text-gray-600 uppercase">
            <tr>
              <th class="p-4">Product</th>
              <th class="p-4">Price</th>
              <th class="p-4">Views</th>
              <th class="p-4">Discount</th>
              <th class="p-4">Status</th>
              <th class="p-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $products->fetch()) : ?>
              <tr class="border-b hover:bg-gray-50">
                <td class="p-4"><?= htmlspecialchars($row['name']) ?></td>
                <td class="p-4">₦<?= number_format($row['price']) ?></td>
                <td class="p-4"><?= $row['views'] ?></td>
                <td class="p-4"><?= $row['discount_percentage'] ?>%</td>
                <td class="p-4">
                  <span class="px-2 py-1 text-green-700 bg-green-100 rounded-full text-xs">Active</span>
                </td>
                <td class="p-4 text-right space-x-2">
                  <a href="product_detail.php?id=<?= $row['id'] ?>">View</a>
                  <a href="edit_product.php?id=<?= $row['id'] ?>" class="text-primary hover:underline">Edit</a>
                  <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')" class="text-red-600 hover:underline">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Bottom Navigation for Mobile -->
  <?php
$currentPage = basename($_SERVER['PHP_SELF']); // Get current page filename
?>
<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white shadow-md border-t border-gray-200 z-50 md:hidden">
  <div class="flex justify-between px-4 py-2 text-xs text-gray-700">

    <!-- Home -->
    <a href="index.php" class="flex flex-col items-center <?php echo ($currentPage == 'index.php') ? 'text-red-500' : 'text-gray-500'; ?>">
      <i class="fas fa-house text-lg"></i>
      <span>Home</span>
    </a>

    <!-- Cart -->
    <a href="cart.php" class="relative flex flex-col items-center <?php echo ($currentPage == 'cart.php') ? 'text-red-500' : 'text-gray-500'; ?>">
  
  <!-- Bag Icon -->
  <i class="fas fa-bag-shopping text-lg"></i>

  <!-- Count Badge (now directly positioned) -->
  <?php if ($cart_count > 0): ?>
  <span class="absolute top-0 left-3 h-4.9 w-4.2 bg-red-500 text-white text-[11px] px-1.5 py-0.5 rounded-full leading-none">
    <?= $cart_count ?>
  </span>
  <?php endif; ?>

  <!-- Label -->
  <span class="text-xs mt-0">Cart</span>
</a>


    <!-- Wishlist -->
    <a href="wishlist.php" class="flex flex-col items-center <?php echo ($currentPage == 'wishlist.php') ? 'text-red-500' : 'text-gray-500'; ?>">
      <i class="fas fa-star text-lg"></i>
      <span>Wishlist</span>
    </a>

    <!-- Profile -->
    <a href="useraccount.php" class="flex flex-col items-center <?php echo ($currentPage == 'useraccount.php') ? 'text-red-500' : 'text-gray-500'; ?>">
      <i class="fas fa-user-circle text-lg"></i>
      <span>Profile</span>
    </a>

    <!-- Sell -->
    <a href="seller_dashboard.php" class="flex flex-col items-center <?php echo ($currentPage == 'seller_dashboard.php') ? 'text-red-500' : 'text-gray-500'; ?>">
      <i class="fas fa-store text-lg"></i>
      <span>Sell</span>
    </a>

  </div>
</nav>
<script>
  function toggleSidebar() {
    const wrapper = document.getElementById('sidebarWrapper');
    const sidebar = document.getElementById('mobileSidebar');
    wrapper.classList.remove('hidden');
    setTimeout(() => {
      sidebar.classList.remove('-translate-x-full');
    }, 10);
  }

  function closeSidebar() {
    const wrapper = document.getElementById('sidebarWrapper');
    const sidebar = document.getElementById('mobileSidebar');
    sidebar.classList.add('-translate-x-full');
    setTimeout(() => {
      wrapper.classList.add('hidden');
    }, 300);
  }

  // Optional: Close when a nav link is clicked
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('#mobileSidebar a').forEach(link => {
      link.addEventListener('click', closeSidebar);
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
