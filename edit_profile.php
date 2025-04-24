<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    if ($username && $email) {
        $update = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $update->execute([$username, $email, $user_id]);

        $_SESSION['profile_msg'] = "✅ Profile updated successfully.";
        header("Location: edit_profile.php");
        exit;
    } else {
        $_SESSION['profile_msg'] = "❌ Both fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

  <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-semibold text-center mb-6">Edit Profile</h2>

    <?php if (isset($_SESSION['profile_msg'])): ?>
      <div class="text-sm mb-4 px-4 py-2 rounded-md 
                  <?= strpos($_SESSION['profile_msg'], '✅') !== false 
                      ? 'bg-green-100 text-green-700' 
                      : 'bg-red-100 text-red-700' ?>">
        <?= $_SESSION['profile_msg'] ?>
      </div>
      <?php unset($_SESSION['profile_msg']); ?>
    <?php endif; ?>

    <div class="flex justify-center mb-6 relative">
    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username']); ?>" alt="Profile" class="w-16 h-16 rounded-full border-4 border-red-500 object-cover">
    
    </div>

    <form action="edit_profile.php" method="POST" class="space-y-4">
      <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" 
               required class="mt-1 w-full px-4 py-2 bg-gray-100 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-black">
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" 
               required class="mt-1 w-full px-4 py-2 bg-gray-100 rounded-lg border-none focus:outline-none focus:ring-2 focus:ring-black">
      </div>

      <div class="flex justify-end mt-4">
        <button type="submit" class="bg-black text-white px-5 py-2 rounded-lg hover:bg-gray-800 transition">
          Save Changes
        </button>
      </div>
    </form>
  </div>

</body>
</html>
