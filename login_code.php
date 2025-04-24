<?php
session_start();
include('db.php'); // Your DB connection

if (isset($_POST['login-btn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Optional: check if verified
            if ($user['verified'] == 0) {
                $_SESSION['status'] = "📩 Please verify your email before logging in.";
                header("Location: login2.php");
                exit();
            }

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: index.php"); // Or wherever you want users to go after logging in
                exit();
            } else {
                $_SESSION['status'] = "❌ Invalid email or password.";
                header("Location: login2.php");
                exit();
            }
        } else {
            $_SESSION['status'] = "❌ No user found with that email.";
            header("Location: login2.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['status'] = "❌ Database error: " . $e->getMessage();
        header("Location: login2.php");
        exit();
    }
}
?>
