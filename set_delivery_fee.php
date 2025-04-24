<?php
session_start();
include 'db.php';

if (!isset($_SESSION['seller_name'])) {
    header("Location: login.php");
    exit;
}

$seller = $_SESSION['seller_name'];
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fee = floatval($_POST['delivery_fee']);

    $stmt = $conn->prepare("UPDATE sellers SET delivery_fee = ? ");
    $stmt->execute([$fee]);

    $success = "Delivery fee updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Delivery Fee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen px-4">

    <div class="bg-white shadow-lg rounded-2xl w-full max-w-md p-6">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Set Delivery Fee</h2>

        <?php if ($success): ?>
            <div class="bg-green-100 text-green-800 text-sm rounded-md px-4 py-3 mb-4">
                <i class="fas fa-check-circle mr-2"></i><?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label for="delivery_fee" class="block text-sm font-medium text-gray-700">Delivery Fee (₦):</label>
                <input type="number" step="0.01" name="delivery_fee" id="delivery_fee" required
                    class="mt-1 block w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 focus:outline-none">
            </div>
            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition-all duration-200">
                Update Fee
            </button>
        </form>
    </div>

</body>
</html>
