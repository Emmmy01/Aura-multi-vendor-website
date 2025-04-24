<?php
// check_subscription.php

require 'db.php'; // Your database connection



$stmt = $conn->prepare("SELECT subscribed_at FROM sellers WHERE seller_name = ?");
$stmt->execute([$_SESSION['seller_name']]);
$seller = $stmt->fetch();

if ($seller) {
    $subscribedAt = new DateTime($seller['subscribed_at']);
    $expiryDate = clone $subscribedAt;
    $expiryDate->modify('+30 days');
    $now = new DateTime();

    if ($now > $expiryDate) {
        header("Location: renew_subscription.php?expired=true");
        exit;
    }
}
?>
