<?php
session_start(); // Must come before anything else
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        die("You must be logged in to make a request.");
    }

    $request = $_POST["request"] ?? '';
    $brand = $_POST["brand"] ?? '';
    $color = $_POST["color"] ?? '';
    $size = $_POST["size"] ?? '';
    $email = $_POST["email"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $imagePath = '';

    // Handle image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $imagePath = $uploadDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
    }

    // Prepare insert
    $sql = "INSERT INTO requests (user_id, request, brand, color, size, image, email, phone) 
            VALUES (:user_id, :request, :brand, :color, :size, :image, :email, :phone)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':request', $request);
    $stmt->bindParam(':brand', $brand);
    $stmt->bindParam(':color', $color);
    $stmt->bindParam(':size', $size);
    $stmt->bindParam(':image', $imagePath);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);

    if ($stmt->execute()) {
        echo "<script>alert('Request submitted successfully.'); window.location.href='index.php';</script>";
    } else {
        echo "Error submitting request.";
    }
}
?>
