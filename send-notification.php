<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_dashboard.php");
    exit;
}

$message    = trim($_POST['message']);
$send_to_all = isset($_POST['send_to_all']);

if ($send_to_all) {
    // Send to every seller
    $all = $conn->query("SELECT id, name FROM sellers")->fetchAll(PDO::FETCH_ASSOC);
    $insert = $conn->prepare("
        INSERT INTO notifications2 (seller_id, seller_name, message, is_read, created_at)
        VALUES (?, ?, ?, 0, NOW())
    ");
    foreach ($all as $s) {
        $insert->execute([$s['id'], $s['name'], $message]);
    }
} else {
    // Send to one seller
    $seller_id = (int)$_POST['seller_id'];
    // Fetch their name
    $stmt = $conn->prepare("SELECT name FROM sellers WHERE id = ?");
    $stmt->execute([$seller_id]);
    $s = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($s) {
        $insert = $conn->prepare("
            INSERT INTO notifications2 (seller_id, seller_name, message, is_read, created_at)
            VALUES (?, ?, ?, 0, NOW())
        ");
        $insert->execute([$seller_id, $s['name'], $message]);
    }
}

header("Location: admin_dashboard.php");
exit;
