<?php
include 'db.php';
session_start();
if (!isset($_SESSION['seller_name'])) {
  header("Location: login.php");
  exit;
}

$sellerName = $_SESSION['seller_name'];

// Fetch product data
if (!isset($_GET['id'])) {
  echo "Product ID missing!";
  exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_name = ?");
$stmt->execute([$id, $sellerName]);
$product = $stmt->fetch();

if (!$product) {
  echo "Product not found or you don't have permission.";
  exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $discount = $_POST['discount'];

  $update = $conn->prepare("UPDATE products SET name = ?, price = ?, discount_percentage = ? WHERE id = ? AND seller_name = ?");
  $update->execute([$name, $price, $discount, $id, $sellerName]);

  header("Location: seller_dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-6">✏️ Edit Product</h2>

    <form method="POST">
      <div class="mb-4">
        <label class="block mb-1 font-medium">Product Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="w-full border p-2 rounded" required>
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">Price (₦)</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" class="w-full border p-2 rounded" required>
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">Discount (%)</label>
        <input type="number" name="discount" value="<?= $product['discount_percentage'] ?>" class="w-full border p-2 rounded">
      </div>

      <div class="flex justify-between mt-6">
        <a href="seller_dashboard.php" class="text-gray-600 hover:underline">← Back</a>
        <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-purple-700">Update Product</button>
      </div>
    </form>
  </div>
</body>
</html>
