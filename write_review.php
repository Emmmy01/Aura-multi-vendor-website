<?php
session_start();
require 'db.php'; // Your DB connection file

$user_id = $_SESSION['user_id'] ?? null;
$seller_name = $_SESSION['seller_name'] ?? null;
$product_id = $_GET['product_id'] ?? null;

// Handle new review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_text']) && !isset($_POST['review_id'])) {
    $rating = intval($_POST['rating']);
    $review_text = trim($_POST['review_text']);
    $target_seller = $_POST['seller_name'] ?? null;
    $target_product = $_POST['product_id'] ?? null;

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, seller_name, product_id, rating, review_text) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $target_seller, $target_product, $rating, $review_text]);
    header("Location: write_review.php?product_id=" . $target_product);
    exit;
}

// Handle seller reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_text'], $_POST['review_id'])) {
    $reply = trim($_POST['reply_text']);
    $review_id = intval($_POST['review_id']);

    $stmt = $conn->prepare("INSERT INTO review_replies (review_id, seller_name, reply_text) VALUES (?, ?, ?)");
    $stmt->execute([$review_id, $seller_name, $reply]);
    header("Location: review_system.php?product_id=" . ($product_id ?? ""));
    exit;
}

// Fetch reviews
$query = "SELECT r.*, u.username as buyer_name 
          FROM reviews r 
          JOIN users u ON r.user_id = u.id";

$where = [];
$params = [];

if ($seller_name) {
    $where[] = "r.seller_name = ?";
    $params[] = $seller_name;
}
if ($product_id) {
    $where[] = "r.product_id = ?";
    $params[] = $product_id;
}
if (!empty($where)) {
    $query .= " WHERE " . implode(" AND ", $where);
}
$query .= " ORDER BY r.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$reviews = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Reviews</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .review-box { border: 1px solid #ccc; margin: 10px 0; padding: 15px; border-radius: 8px; }
        .reply-box { margin-left: 30px; background: #f9f9f9; padding: 10px; border-left: 3px solid red; }
        .form-group { margin-bottom: 10px; }
        textarea { width: 100%; }
    </style>
    <script>
    function editReview(id, currentText, currentRating) {
        const newText = prompt("Edit your review:", currentText);
        const newRating = prompt("Edit your rating (1-5):", currentRating);
        if (newText !== null && newRating !== null) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "edit_review.php";

            const idField = document.createElement("input");
            idField.type = "hidden";
            idField.name = "review_id";
            idField.value = id;

            const textField = document.createElement("input");
            textField.type = "hidden";
            textField.name = "review_text";
            textField.value = newText;

            const ratingField = document.createElement("input");
            ratingField.type = "hidden";
            ratingField.name = "rating";
            ratingField.value = newRating;

            form.appendChild(idField);
            form.appendChild(textField);
            form.appendChild(ratingField);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function deleteReview(id) {
        if (confirm("Are you sure you want to delete this review?")) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "delete_review.php";

            const idField = document.createElement("input");
            idField.type = "hidden";
            idField.name = "review_id";
            idField.value = id;

            form.appendChild(idField);
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</head>
<body>
<h2>Leave a Review</h2>
<?php if ($user_id): ?>
    <form method="POST">
        <input type="hidden" name="seller_name" value="<?= htmlspecialchars($seller_name) ?>">
        <?php if ($product_id): ?><input type="hidden" name="product_id" value="<?= $product_id ?>"><?php endif; ?>
        <div class="form-group">
            <label>Rating (1-5):</label>
            <input type="number" name="rating" min="1" max="5" required>
        </div>
        <div class="form-group">
            <textarea name="review_text" rows="4" placeholder="Your review..." required></textarea>
        </div>
        <button type="submit">Submit Review</button>
    </form>
<?php else: ?>
    <p>You must be logged in as a buyer to leave a review.</p>
<?php endif; ?>

<h2>All Reviews</h2>
<?php foreach ($reviews as $review): ?>
    <div class="review-box">
        <strong><?= htmlspecialchars($review['buyer_name']) ?></strong> rated <strong><?= $review['rating'] ?>/5</strong><br>
        <small><strong>Seller:</strong> <?= htmlspecialchars($review['seller_name']) ?> | <strong>Product ID:</strong> <?= htmlspecialchars($review['product_id']) ?></small><br>
        <p><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
        <small><?= $review['created_at'] ?></small><br>

        <?php if ($user_id == $review['user_id']): ?>
            <button onclick="editReview(<?= $review['id'] ?>, <?= json_encode($review['review_text']) ?>, <?= $review['rating'] ?>)">Edit</button>
            <button onclick="deleteReview(<?= $review['id'] ?>)">Delete</button>
        <?php endif; ?>

        <?php if ($seller_name): ?>
            <form method="POST" style="margin-top:10px;">
                <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                <textarea name="reply_text" rows="2" placeholder="Reply to this review..." required></textarea>
                <button type="submit">Reply</button>
            </form>
        <?php endif; ?>

        <?php
        $replyStmt = $conn->prepare("SELECT * FROM review_replies WHERE review_id = ?");
        $replyStmt->execute([$review['id']]);
        $replies = $replyStmt->fetchAll();
        foreach ($replies as $reply): ?>
            <div class="reply-box">
                <strong>Seller (<?= htmlspecialchars($reply['seller_name']) ?>)</strong> replied:<br>
                <?= nl2br(htmlspecialchars($reply['reply_text'])) ?><br>
                <small><?= $reply['replied_at'] ?></small>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
</body>
</html>
