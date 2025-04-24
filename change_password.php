<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Fetch the current password from the DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current, $user['password'])) {
        $_SESSION['password_msg'] = "❌ Current password is incorrect.";
    } elseif ($new !== $confirm) {
        $_SESSION['password_msg'] = "❌ New passwords do not match.";
    } elseif (strlen($new) < 6) {
        $_SESSION['password_msg'] = "❌ New password must be at least 6 characters.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$hashed, $user_id]);
        $_SESSION['password_msg'] = "✅ Password updated successfully.";
    }

    header("Location: useraccount.php");
    exit;
}
?>
