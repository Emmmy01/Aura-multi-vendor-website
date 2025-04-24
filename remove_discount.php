<?php
include 'db.php';
session_start();

if (!isset($_SESSION['seller_id'])) exit;

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("UPDATE products SET discount_percentage = 0 WHERE id = ? AND seller_id = ?");
$stmt->execute([$id, $_SESSION['seller_id']]);

header("Location: seller_dashboard.php");
exit;
