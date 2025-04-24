<?php
session_start();
include 'db.php';

if (!isset($_GET['reference'])) {
    echo json_encode(["status" => "failed", "message" => "No reference supplied"]);
    exit;
}

$reference = $_GET['reference'];
$paystack_secret_key = "sk_test_7fab4f7a29ecf952b62aaa1ef61405b5ddc03e1a"; // Your secret key

// Verify payment with Paystack
$verify_url = "https://api.paystack.co/transaction/verify/" . $reference;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verify_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $paystack_secret_key",
    "Cache-Control: no-cache"
]);

$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);

// Check if payment was successful
if ($result['status'] && $result['data']['status'] === 'success') {
    $seller_id = $result['data']['metadata']['custom_fields'][0]['value'];

    // ✅ Update seller: approve & store subscription start time
    $stmt = $conn->prepare("UPDATE sellers SET status = 'approved', subscribed_at = NOW() WHERE id = ?");
    $stmt->execute([$seller_id]);

    echo json_encode(["status" => "success", "redirect" => "login.php"]);
    exit;
} else {
    echo json_encode(["status" => "failed", "message" => "Payment verification failed"]);
    exit;
}
?>
