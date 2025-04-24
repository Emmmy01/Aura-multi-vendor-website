<?php 
 

include 'db.php';
session_start();

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM sellers WHERE email = ?");
  $stmt->execute([$email]);
  $seller = $stmt->fetch();

  if ($seller && password_verify($password, $seller['password'])) {
    if ($seller['status'] === 'approved') {
      $_SESSION['seller_name'] = $seller['name'];
      header("Location: seller_dashboard.php");
      exit;
    } else {
      $_SESSION['message'] = "⏳ Your account is still under review. You'll be notified via email once approved.";
      $_SESSION['type'] = 'warning';
    }
  } else {
    $_SESSION['message'] = "❌ Invalid email or password.";
    $_SESSION['type'] = 'error';
  }

  header("Location: login.php");
  exit;
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Seller Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Seller Login</h2>

    <!-- Flash Message -->
    <?php if (isset($_SESSION['message'])): ?>
      <div class="mb-4 p-3 rounded <?php echo $_SESSION['type'] === 'error' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'; ?>">
        <?= $_SESSION['message'] ?>
      </div>
      <?php unset($_SESSION['message'], $_SESSION['type']); ?>
    <?php endif; ?>

    <form method="POST" action="login.php" class="space-y-4">
      <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
      <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
      <button type="submit" name="login" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-700 transition">Login</button>
    </form>
 

    <p class="mt-4 text-sm text-left">
  Don’t have an account?
  <a href="register.php" class="text-red-500 hover:underline">Register</a>
</p>
<p class="mt-4 text-sm text-left"><a href="activate-account.php" class="text-red-500 hover:underline">Activate account</a></p>
  </div>
  
</body>
</html>
