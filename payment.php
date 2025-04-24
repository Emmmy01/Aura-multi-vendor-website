<?php
include 'db.php';

// Support both email or seller_id
if (isset($_GET['seller_id'])) {
    $stmt = $conn->prepare("SELECT * FROM sellers WHERE id = ?");
    $stmt->execute([$_GET['seller_id']]);
} elseif (isset($_GET['email'])) {
    $stmt = $conn->prepare("SELECT * FROM sellers WHERE email = ?");
    $stmt->execute([$_GET['email']]);
} else {
    header("Location: login.php");
    exit;
}

$seller = $stmt->fetch();

if (!$seller || $seller['status'] === 'approved') {
    header("Location: login.php");
    exit;
}

$seller_id = $seller['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Activate Your Seller Account</h2>
        
        <!-- Payment Information -->
        <div class="mb-6">
            <p class="text-gray-700 text-lg">To start selling, please make a payment to activate your seller account. The payment will confirm your registration and grant you access to the platform.</p>
        </div>

        <!-- Payment Form -->
        <div class="space-y-4">
            <input type="hidden" id="sellerEmail" value="<?php echo $seller['email']; ?>">
            <input type="hidden" id="sellerId" value="<?php echo $seller_id; ?>">

            <!-- Pay Button -->
            <button onclick="payWithPaystack()" class="w-full bg-red-500 text-white py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                Pay to Activate
            </button>
        </div>

<script>
function payWithPaystack() {
    let sellerEmail = document.getElementById("sellerEmail").value.trim();
    let sellerId = document.getElementById("sellerId").value.trim(); // Hidden input containing seller ID
    let amountInKobo = 5000 * 100; // Subscription fee in kobo (5000 NGN)

    var handler = PaystackPop.setup({
        key: "pk_test_e1f63a3ba7522fab44967e242e26e0cd1d6fbdd2", // Use your actual public key
        email: sellerEmail,
        amount: amountInKobo,
        currency: "NGN",
        ref: "SUB_" + Math.floor(Math.random() * 1000000000 + 1),
        metadata: {
            custom_fields: [
                {
                    display_name: "Seller ID",
                    variable_name: "seller_id",
                    value: sellerId
                }
            ]
        },
        callback: function (response) {
            // After payment is confirmed, verify the payment
            fetch("payment_callback.php?reference=" + response.reference)
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    alert("Payment successful! Your account is now approved.");
                    window.location.href = data.redirect; // Redirect to the login page
                } else {
                    alert("Payment verification failed. Please try again.");
                }
            });
        },
        onClose: function () {
            alert("Payment window closed.");
        }
    });

    handler.openIframe();
}
</script>
<script src="https://js.paystack.co/v1/inline.js"></script>