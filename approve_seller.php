<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

function send_approval_email($name, $email) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "ajibadejerry67@gmail.com";
        $mail->Password = "wdokahugsbunscdx"; // Use App Password for Gmail
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom("ajibadejerry67@gmail.com", "Aura Admin");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your Seller Account Has Been Approved!';

        $email_template = "
            <h2>Hi $name,</h2>
            <p>Your seller account has been <strong>approved</strong> 🎉</p>
            <p>You can now login and start uploading your products.</p>
            <br/>
            <a href='http://localhost/yourprojectfolder/login.php' style='
                padding: 10px 15px;
                background-color: #7C3AED;
                color: white;
                text-decoration: none;
                border-radius: 5px;'>Login Now</a>
            <br/><br/>
            <p>— Aura Admin</p>
        ";

        $mail->Body = $email_template;
        $mail->send();
    } catch (Exception $e) {
        echo "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seller_id'])) {
    $seller_id = $_POST['seller_id'];
    $email = $_POST['email'];
    $name = $_POST['name'];

    // Fetch seller details
    $query = "SELECT * FROM sellers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$seller_id]);
$seller = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($seller) {
        // Update status to approved
        $update_query = "UPDATE sellers SET status = 'approved' WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->execute([$seller_id]);
        
        // Send email
        send_approval_email($seller['name'], $seller['email']);

        $_SESSION['status'] = "✅ Seller approved and email sent!";
    } else {
        $_SESSION['status'] = "❌ Seller not found!";
    }

    header("Location: admin_dashboard.php");
    exit();
}
?>
