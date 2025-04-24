<?php
session_start();
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$user_id = $_SESSION['user_id'];  // Get logged-in user ID

// Retrieve form data
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$price = $_POST['price'];
$image = $_POST['image'];
$color = $_POST['color'];
$size = $_POST['size'];
$quantity = $_POST['quantity'];

// Check if the product is already in the cart for this user
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND color = ? AND size = ?");
$stmt->execute([$user_id, $product_id, $color, $size]);
$existingItem = $stmt->fetch();

if ($existingItem) {
    // If the product already exists in the cart, update the quantity
    $newQuantity = $existingItem['quantity'] + $quantity;
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->execute([$newQuantity, $existingItem['id']]);
} else {
    // If the product is not in the cart, insert it
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, color, size) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $product_id, $quantity, $color, $size]);
}

// Redirect to the cart page after adding the item
header("Location: cart.php");
exit;
?>
