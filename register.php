<?php
session_start(); 
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Check if email already exists
  $stmt = $conn->prepare("SELECT * FROM sellers WHERE email = ?");
  $stmt->execute([$email]);

  if ($stmt->rowCount() > 0) {
    $_SESSION['message'] = "❌ Email already registered.";
    $_SESSION['type'] = "error";
  } else {
    // Insert the new seller with status 'pending'
    $insert = $conn->prepare("INSERT INTO sellers (name, email, password, status) VALUES (?, ?, ?, 'pending')");
    $insert->execute([$name, $email, $password]);

    // Get the last inserted seller ID
    $seller_id = $conn->lastInsertId();

    // Redirect to payment page
    header("Location: payment.php?seller_id=$seller_id");
    exit;
  }

  header("Location: register.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
  <title>Seller Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Seller Registration</h2>

    <!-- Flash message -->
    <?php if (isset($_SESSION['message'])): ?>
      <div class="mb-4 p-3 rounded <?php echo $_SESSION['type'] === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
        <?= $_SESSION['message'] ?>
      </div>
      <?php unset($_SESSION['message'], $_SESSION['type']); ?>
    <?php endif; ?>

    <form method="POST" action="register.php" class="space-y-4">
      <input type="text" name="name" placeholder="Full Name Please" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
      <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
      <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
      <button type="submit" name="register" class="w-full bg-red-500 text-white py-2 rounded hover:bg-blue-700 transition">Register</button>
    </form>
    <p class="mt-4 text-sm text-left">Already registered but haven't paid yet? <a href="activate-account.php" class="text-red-500 hover:underline">Click here to complete payment</a></p>

    <p class="mt-4 text-sm text-left">
  Already have an account?
  <a href="login.php" class="text-red-500 hover:underline">Login</a>
</p>

  </div>
</body>
</html>
