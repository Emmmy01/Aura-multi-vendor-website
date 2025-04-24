<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$product_id || !$action) {
    echo json_encode(['success' => false]);
    exit;
}

switch ($action) {
    case 'increase':
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        break;

    case 'decrease':
        // Get current quantity first
        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $current = $stmt->fetchColumn();
        if ($current <= 1) {
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
        } else {
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
        }
        break;

    case 'delete':
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        break;
}

$stmt = $conn->prepare("SELECT p.price, c.quantity 
                        FROM cart c 
                        INNER JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ? AND c.product_id = ?");
$stmt->execute([$user_id, $product_id]);
$item = $stmt->fetch();

$new_quantity = $item['quantity'] ?? 0;
$new_price = ($item) ? $item['price'] * $item['quantity'] : 0;

// Get new total
$stmt = $conn->prepare("SELECT p.price, c.quantity 
                        FROM cart c 
                        INNER JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll();
$new_total = array_sum(array_map(function($item) {
    return $item['price'] * $item['quantity'];
}, $cartItems));

echo json_encode([
    'success' => true,
    'new_quantity' => $new_quantity,
    'new_price' => $new_price,
    'new_total' => $new_total
]);
