<?php
include 'db.php';

$stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  
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

  <title>All Products</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <div class="max-w-6xl mx-auto p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">All Products</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
      <?php foreach ($products as $product): ?>
        <a href="product_detail.php?id=<?= $product['id'] ?>" class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300">
          <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-40 object-contain rounded-t-xl">
          <div class="p-3">
            <h3 class="text-sm font-semibold text-gray-800 truncate" title="<?= htmlspecialchars($product['name']) ?>">
              <?= htmlspecialchars($product['name']) ?>
            </h3>
            <p class="text-black font-bold text-sm mt-1">₦<?= number_format($product['price']) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

</body>
</html>
