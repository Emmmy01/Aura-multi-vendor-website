<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM sellers WHERE email = ? AND status != 'approved'");
    $stmt->execute([$email]);
    $seller = $stmt->fetch();

    if ($seller) {
        header("Location: payment.php?email=" . urlencode($email));
        exit;
    } else {
        $error = "Account already activated or not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activate Seller Account</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Activate Your Seller Account</h2>
    <?php if (isset($error)): ?>
      <p class="text-red-500 text-center mb-4"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" class="space-y-4">
    <label class="block">
  <span class="block text-sm font-medium text-gray-700 mb-1">Registered Email</span>
  <input 
    type="email" 
    name="email" 
    required 
    placeholder="e.g. johndoe@example.com"
    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-black focus:border-black placeholder-gray-400"
  >
</label>

      <button type="submit" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-700 transition">
        Proceed to Payment
      </button>
    </form>
  </div>
</body>
</html>
