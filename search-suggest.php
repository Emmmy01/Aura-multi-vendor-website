<?php
require 'db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q !== '') {
    $stmt = $conn->prepare("SELECT id, name, image FROM products WHERE name LIKE ? LIMIT 5");
    $stmt->execute(["%$q%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
}
?>
