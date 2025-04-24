<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Check if already in wishlist
$check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
$check->execute([$user_id, $product_id]);

if ($check->rowCount() > 0) {
    // Remove from wishlist
    $delete = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $delete->execute([$user_id, $product_id]);
    echo json_encode(['status' => 'removed']);
} else {
    // Add to wishlist
    $insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $insert->execute([$user_id, $product_id]);
    echo json_encode(['status' => 'added']);
}
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const icon = document.getElementById('wishlist-icon');

    icon.addEventListener('click', function () {
        const productId = icon.getAttribute('data-product-id');

        fetch('wishlist_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${productId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'added') {
                icon.classList.add('active');
            } else if (data.status === 'removed') {
                icon.classList.remove('active');
            }
        });
    });
});
</script>
