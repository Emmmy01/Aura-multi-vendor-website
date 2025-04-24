<?php
// compare.php
require 'db.php';
session_start();

// Get selected product IDs from query string (e.g. compare.php?ids=3,5)
$product_ids = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];

$products = [];
if (!empty($product_ids)) {
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compare Products</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">
<div class="max-w-7xl mx-auto px-4 py-8">
  <h2 class="text-2xl font-bold mb-6">Compare Products</h2>

  <?php if (!empty($products)): ?>

    <!-- 📱 Mobile View: Card Layout -->
    <div class="grid gap-6 md:hidden">
      <?php foreach ($products as $p): ?>
        <div class="bg-white shadow rounded-lg p-4">
          <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>" class="w-full h-40 object-contain mb-3">
          <h3 class="text-lg font-semibold " title="<?= $p['name'] ?>"><?= $p['name'] ?></h3>
          <p class="text-sm text-gray-500 ">Brand: <?= $p['brand'] ?></p>
          <p class="text-black font-bold">₦<?= number_format($p['price']) ?></p>
          <p class="text-sm text-gray-600 mt-2">
          <?= nl2br($p['short_description']) ?>

          </p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- 💻 Desktop View: Table Layout -->
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full table-auto border">
        <thead>
          <tr>
            <th class="p-2 border"></th>
            <?php foreach ($products as $p): ?>
              <th class="p-2 border text-center">#<?= $p['id'] ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="p-2 font-bold border">Image</td>
            <?php foreach ($products as $p): ?>
              <td class="p-2 border text-center">
                <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>" class="w-32 h-32 object-contain mx-auto">
              </td>
            <?php endforeach; ?>
          </tr>
          <tr>
            <td class="p-2 font-bold border">Name</td>
            <?php foreach ($products as $p): ?>
              <td class="p-2 border text-center text-sm truncate max-w-[120px]" title="<?= $p['name'] ?>">
                <?= $p['name'] ?>
              </td>
            <?php endforeach; ?>
          </tr>
          <tr>
            <td class="p-2 font-bold border">Brand</td>
            <?php foreach ($products as $p): ?>
              <td class="p-2 border text-center text-sm truncate max-w-[120px]" title="<?= $p['brand'] ?>">
                <?= $p['brand'] ?>
              </td>
            <?php endforeach; ?>
          </tr>
          <tr>
            <td class="p-2 font-bold border">Price</td>
            <?php foreach ($products as $p): ?>
              <td class="p-2 border text-center">₦<?= number_format($p['price']) ?></td>
            <?php endforeach; ?>
          </tr>
          <tr>
            <td class="p-2 font-bold border">Description</td>
            <?php foreach ($products as $p): ?>
              <td class="p-2 border text-sm text-gray-600 text-left max-w-xs">
              <?= nl2br($p['short_description']) ?>

              </td>
            <?php endforeach; ?>
          </tr>
        </tbody>
      </table>
    </div>

  <?php else: ?>
    <p>No products selected for comparison.</p>
  <?php endif; ?>
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
