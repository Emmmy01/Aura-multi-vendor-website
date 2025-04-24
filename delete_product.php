<?php
include 'db.php';
session_start();

if (!isset($_SESSION['seller_name'])) exit;

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_name = ?");
$stmt->execute([$id, $_SESSION['seller_name']]);

header("Location: seller_dashboard.php");
exit;
