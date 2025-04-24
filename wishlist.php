<?php
session_start();
include 'db.php';
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

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT p.* FROM products p 
    JOIN wishlist w ON p.id = w.product_id 
    WHERE w.user_id = ?
");
$stmt->execute([$user_id]);
$wishlist_items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Wishlist</title>
  <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 

  
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://unpkg.com/lucide@latest"></script>


<script>
  lucide.createIcons(); // Load icons
</script>


<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fefefe;
      color: #333;
      padding: 1rem;
      text-align: left;
    }

    h2 {
      text-align: left;
      margin-bottom: 1.5rem;
      font-size: 1.5rem;
      color:rgb(16, 15, 15);
    }

    .wishlist-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 1rem;
    }

    .wishlist-item {
      background: #fff;
      padding: 0.8rem;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
   
      transition: transform 0.3s ease;
    }

    .wishlist-item:hover {
      transform: translateY(-3px);
    }

    .wishlist-item img {
      width: 100%;
      max-width: 120px;
      height: 150px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 0.5rem;
    }

    .wishlist-item h3 {
      font-size: 0.8rem;
      margin: 0.3rem 0;
      text-align: left;
    }

    .wishlist-item p {
      font-weight: bold;
      color: #333;
      font-size: 0.8rem;
      margin: 0.3rem 0;
      text-align: left;
    }

    .wishlist-item a {
      display: inline-block;
      margin-top: 0.5rem;
      padding: 0.4rem 0.8rem;
      font-size: 0.85rem;
      background-color: #1d3557;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      transition: background 0.2s ease;
    }

    .wishlist-item a:hover {
      background-color: #457b9d;
    }

    @media (max-width: 480px) {
      .wishlist-container {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    a{
        text-decoration:none;
        color:black;
    }
  </style>
</head>
<body>

  <h2 class="text-3xl font-bold mb-6">Your Wishlist</h2>

  <div class="wishlist-container">
    <?php if (count($wishlist_items) > 0): ?>
      <?php foreach ($wishlist_items as $item): ?>
        <a href="product_detail.php?id=<?= $item['id'] ?>">
        <div class="wishlist-item">
        

          <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          <h3><?= htmlspecialchars($item['name']) ?></h3>
          <p>₦<?= number_format($item['price']) ?></p>
      
        </div>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align:center;">Your wishlist is empty</p>
    <?php endif; ?>
  </div>
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
    <a href="seller_dashboard.php" class="flex flex-col items-center <?php echo ($currentPage == 'add_product.php') ? 'text-red-500' : 'text-gray-500'; ?>">
      <i class="fas fa-store text-lg"></i>
      <span>Sell</span>
    </a>

  </div>
</nav>
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
