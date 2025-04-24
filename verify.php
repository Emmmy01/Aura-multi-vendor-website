<?php
session_start();

$host = 'localhost';
$db   = 'Aura';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}




if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        // Check if token exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE verify_token = :token LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user['verified'] == 0) {
                // Update verified status
                $update = $pdo->prepare("UPDATE users SET verified = 1 WHERE verify_token = :token");
                $update->bindParam(':token', $token);
                $update->execute();

                $_SESSION['status'] = "Your email has been verified successfully!";
                header("Location: login2.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Email already verified. Please login.";
                header("Location: login2.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Invalid token.";
            header("Location: redistration.php");
            exit(0);
        }
    } catch (PDOException $e) {
        $_SESSION['status'] = "Error: " . $e->getMessage(); // For debugging only
        header("Location: redistration.php");
        exit(0);
    }
} else {
    $_SESSION['status'] = "No token provided.";
    header("Location: redistration.php");
    exit(0);
}
