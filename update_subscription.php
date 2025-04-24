<?php
session_start();
require 'db.php';

if (!isset($_SESSION['seller_name'])) {
    header("Location: login.php");
    exit;
}

// Assuming this file is called after successful payment
$stmt = $conn->prepare("UPDATE sellers SET subscribed_at = NOW() WHERE seller_name = ?");
$stmt->execute([$_SESSION['seller_name']]);

header("Location: seller_dashboard.php?renewed=true");
exit;
