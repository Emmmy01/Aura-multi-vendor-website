<?php
include 'db.php';
session_start();

if (!isset($_SESSION['seller_name'])) {
  header("Location: login.php");
  exit;
}

$sellerName = $_SESSION['seller_name'];

$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? '';
$quality = $_POST['quality'] ?? '';
$price = $_POST['price'] ?? '';
$brand = $_POST['brand'] ?? '';
$color = $_POST['color'] ?? '';
$discount = $_POST['discount'] ?? '';
$product_date = $_POST['product_date'] ?? '';
$sales_channel = $_POST['sales_channel'] ?? '';
$sizes = isset($_POST['sizes']) ? implode(", ", $_POST['sizes']) : '';
$seller_bank_name = $_POST['seller_bank_name'] ?? '';
$seller_account_name = $_POST['seller_account_name'] ?? '';
$seller_account_number = $_POST['seller_account_number'] ?? '';
$seller_whatsapp = $_POST['seller_whatsapp'] ?? '';

// Add the delivery fee input here
$delivery_fee = $_POST['delivery_fee'] ?? 0.00; // Default to 0 if not provided

$target_dir = "uploads/";
$image = $image_2 = $image_3 = "";

if (!file_exists($target_dir)) {
  mkdir($target_dir, 0777, true);
}

if (isset($_FILES["images"])) {
  $files = $_FILES["images"];
  $total = count($files["name"]);

  for ($i = 0; $i < $total && $i < 3; $i++) {
    if ($files["error"][$i] === 0) {
      $ext = pathinfo($files["name"][$i], PATHINFO_EXTENSION);
      $filename = uniqid("prod_", true) . "." . $ext;
      $filepath = $target_dir . $filename;

      if (move_uploaded_file($files["tmp_name"][$i], $filepath)) {
        if ($i == 0) $image = $filepath;
        elseif ($i == 1) $image_2 = $filepath;
        elseif ($i == 2) $image_3 = $filepath;
      }
    }
  }
}

$sql = "INSERT INTO products (
  name, short_description, category, quality, price, discount_percentage, image, image_2, image_3,
  sizes, product_date, sales_channel, seller_name, brand, color,
  seller_bank_name, seller_account_name, seller_account_number, seller_whatsapp, delivery_fee
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$success = $stmt->execute([
  $name,
  $description,
  $category,
  $quality,
  $price,
  $discount,
  $image,
  $image_2,
  $image_3,
  $sizes,
  $product_date,
  $sales_channel,
  $sellerName,
  $brand,
  $color,
  $seller_bank_name,
  $seller_account_name,
  $seller_account_number,
  $seller_whatsapp,
  $delivery_fee  // Include the delivery fee here
]);

if ($success) {
  echo "✅ Product uploaded successfully.";
} else {
  echo "❌ Error uploading product.";
}
?>
