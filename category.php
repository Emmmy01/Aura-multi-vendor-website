<?php
session_start();
include 'db.php';

$category = $_GET['category'] ?? '';
$allowedCategories = ['Clothes', 'Electronics', 'Shoes', 'Home' , 'Women', 'Men', 'Appliances', 'Phones and Tablets', 'Health and Beauty', 'Home and Office', 'Fashion', 'Computing', 'Gaming', 'Musical Instruments', 'Other Categories'];

if (!in_array($category, $allowedCategories)) {
  exit("Category not found.");
}

// Fetch products by category
$stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
$stmt->execute([$category]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Aura Marketplace</title>
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

</head>
<body>
    

<!-- Page Container -->
<div class="p-4 max-w-7xl mx-auto">
  <!-- Category Title -->
  <h1 class="text-2xl font-bold text-gray-800 mb-6 capitalize"><?= htmlspecialchars($category) ?></h1>

  <!-- Products Grid -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php foreach ($products as $product): ?>
      <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
      <a href="product_detail.php?id=<?= $product['id'] ?>">
        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="w-full h-40 object-contain">
        
        <div class="p-3">
          <h3 class="text-sm font-semibold text-gray-800 truncate" title="<?= htmlspecialchars($product['name']) ?>">
            <?= htmlspecialchars($product['name']) ?>
          </h3>
          <p class="text-black font-bold text-sm mt-1">₦<?= number_format($product['price']) ?></p>
        </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>