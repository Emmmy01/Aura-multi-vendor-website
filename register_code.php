<?php
session_start();
$host = 'localhost';
$db   = 'Aura';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If there's an error, output the message
    die("Connection failed: " . $e->getMessage());
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Send email verification function
function sendmail_verify($username, $email, $verify_token)
{
    $mail = new PHPMailer(true);

    try {
        // Configure SMTP settings
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "ajibadejerry67@gmail.com";
        $mail->Password = "wdokahugsbunscdx"; // Use App Password if required
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Set email sender and recipient
        $mail->setFrom("ajibadejerry67@gmail.com", "Aura");
        $mail->addAddress($email);

        // Set email format to HTML
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = 'Click the link below to verify your email: <br><a href="http://localhost/Aura_Mutivendor/verify.php?token=' . $verify_token . '">Verify Email</a>';

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
}

// Handle registration form submission
if (isset($_POST['register-btn'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    // Check if email already exists
    $check_email_query = "SELECT email FROM users WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['status'] = "Email already exists!";
        header("Location: redistration.php");
        exit();
    }

    // Password validation
    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Passwords do not match!";
        header("Location: redistration");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate unique verification token
    $verify_token = md5(rand());

    // Insert user data into the database
    $query = "INSERT INTO users (username, email, password, verify_token) VALUES (:username, :email, :password, :verify_token)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':verify_token', $verify_token);

    if ($stmt->execute()) {
        // Send verification email
        sendmail_verify($username, $email, $verify_token);

        $_SESSION['status'] = "Registration successful! Please check your email to verify your account.";
        header("Location: redistration.php");
        exit();
    } else {
        $_SESSION['status'] = "Registration failed! Please try again.";
        header("Location: redistration.php");
        exit();
    }
}
?>
