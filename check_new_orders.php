<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

if (!isset($_SESSION['seller_name'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$seller_id = $_SESSION['seller_name'];

// Fetch the latest unviewed order for this seller
$stmt = $conn->prepare("
    SELECT MAX(oi.order_id) AS latest_order_id
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE p.seller_name = ? AND oi.viewed_by_seller = 0
");
$stmt->execute([$seller_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result && $result['latest_order_id']) {
    echo json_encode([
        'status' => 'success',
        'latest_order_id' => (int)$result['latest_order_id']
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'latest_order_id' => 0
    ]);
}
?>
