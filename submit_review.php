<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  die("Unauthorized access");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$comment = trim($_POST['comment'] ?? '');

if (!$product_id || !$rating || !$comment) {
  die("All fields are required");
}

// Insert the review
$stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([$product_id, $user_id, $rating, $comment]);

header("Location: product_detail.php?id=$product_id");
exit;
?>
