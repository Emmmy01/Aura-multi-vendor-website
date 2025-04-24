<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit();
}
$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // SUM the quantity instead of COUNTing rows
    $stmt = $conn->prepare("SELECT SUM(quantity) AS item_count FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['item_count']) {
        $cart_count = $row['item_count'];
    }
}
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = :user_id LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(":user_id", $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account | Aura</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
     <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="style.css">
  <!-- Swiper CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://unpkg.com/lucide@latest"></script>


<script>
  lucide.createIcons(); // Load icons
</script>


<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: white;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #fff;
            padding: 30px 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 18px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a i {
            margin-right: 12px;
            color: gray;
        }

        .sidebar ul li a.active,
        .sidebar ul li a:hover {
            background-color: gray;
            color: #fff;
        }

        .sidebar ul li a.active i,
        .sidebar ul li a:hover i {
            color: #fff;
        }

        /* Main content */
        .main {
            flex: 1;
            padding: 50px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
        }

        .profile-header img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid rgb(14, 14, 14);
        }

        .profile-details {
            margin-left: 20px;
        }

        .profile-details h3 {
            margin: 0;
            font-size: 24px;
            color: #111;
        }

        .profile-details p {
            margin: 6px 0;
            color: #666;
        }

        .btn-orange {
            background-color:#ef4444;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            text-align:right;
            display:flex;
            justify-content:right;
            margin-top: 15px;
            transition: background 0.3s ease;
        }

        .btn-orange:hover {
            background-color:rgb(12, 12, 12);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 15px;
        }

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }

        @media(max-width: 768px) {
            .dashboard {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                box-shadow: none;
                display: none;
            }

            .main {
                padding: 20px;
            }



              /* Sidebar */
        .sidebar2 {
            width: 100%;
            background-color: #fff;
            padding: 30px 20px;
            margin-bottom: 40px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar2 h2 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
        }

        .sidebar2 ul {
            list-style: none;
            padding: 0;
        }

        .sidebar2 ul li {
            margin-bottom: 18px;
        }

        .sidebar2 ul li a {
            text-decoration: none;
            color: #333;
            font-size: 17px;
            display: flex;
            align-items: center;
            padding: 12px 0px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar2 ul li a i {
            margin-right: 12px;
            color:gray;
        }

      
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 15px;
        }
        h2{
            font-size:20px;
        }

        }
        .main h2{
            font-size:25px;
            display:flex;
            justify-content:center;
            padding: 20px;
        }
        ul li a:hover {
  background-color: #f5f5f5;
  color: #000;
}

ul li a:hover .arrow {
  color: #000;
}
ul li a {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 15px;
  text-decoration: none;
  color: gray;
  border-bottom: 1px solid #eee; /* optional divider */
}

ul li a i {
  margin-right: 10px;
}

ul li a .arrow {
  margin-right: 0;
  margin-left: auto;
  color: #999;
}
@media (min-width: 768px) {

.sidebar2{
    display:none;
}
}

    </style>
</head>
<body>

<div class="dashboard">

    <!-- Sidebar Navigation -->
    <aside class="sidebar">
       
        <ul>
        <li><a href="useraccount.php" class="active"><i class='fas fa-user-graduate'></i> User Info <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="user_orders.php"><i class='fas fa-shopping-basket'></i> My Orders <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="wishlist.php"><i class='far fa-heart'></i> Saved Items <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="inbox.php"><i class='far fa-bell'></i> Inbox <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="delivery_info.php"><i class="fa fa-address-book-o"></i> Delivery Address <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout <i class="fas fa-chevron-right arrow"></i></a></li>
</ul>
    </aside>

    <!-- Main Profile Area -->
    
        <!-- Profile Card -->
     
        <section class="bg-whitesmoke p-4">
        <div class="bg-black rounded-xl shadow-md p-8 items-center gap-4">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username']); ?>" alt="Profile" class="w-16 h-16 rounded-full border-4 border-red-500 object-cover">
            <div>
                <h2 class="text-xl text-white font-semibold"><?= htmlspecialchars($user['username']); ?></h2>
                <p class="text-white text-sm"><?= htmlspecialchars($user['email']); ?></p>
                <p class="text-xs text-white">Joined on <?= date('F j, Y', strtotime($user['created_at'])); ?></p>
            </div>
        </div>
        <div class="text-right mt-3">
            <a href="edit_profile.php" class="text-gray-500 font-semibold text-sm underline">Edit Profile</a>
        </div>
    </section>

    <!-- Flash Message -->
    <?php if (isset($_SESSION['password_msg'])): ?>
    <div class="mx-4 mt-2 text-center font-medium text-sm <?= strpos($_SESSION['password_msg'], '✅') !== false ? 'text-green-600' : 'text-red-600' ?>">
        <?= $_SESSION['password_msg'] ?>
        <?php unset($_SESSION['password_msg']); ?>
    </div>
    <?php endif; ?>
<aside class="sidebar2 p-8">
      
<ul>
  <li><a href="useraccount.php" class="active"><i class='fas fa-user-graduate'></i> User Info <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="user_orders.php"><i class='fas fa-shopping-basket'></i> My Orders <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="wishlist.php"><i class='far fa-heart'></i> Saved Items <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="inbox.php"><i class='far fa-bell'></i> Inbox <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="delivery_info.php"><i class="fa fa-address-book-o"></i> Delivery Address <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="my-requests.php"><i class="fa fa-address-book-o"></i> My Requests <i class="fas fa-chevron-right arrow"></i></a></li>
  <li><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout <i class="fas fa-chevron-right arrow"></i></a></li>
</ul>


    </aside>
        <!-- Change Password Form -->
        <div class="card">
            <h3 style="margin-bottom: 20px;">Change Password</h3>
            <form action="change_password.php" method="POST">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-actions">
                    <button type="submit" name="change_password" class="btn-orange">Update Password</button>
                </div>
            </form>
        </div>
    </main>

</div>
<?php
$currentPage = basename($_SERVER['PHP_SELF']); // Get current page filename
?>

<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white shadow-md border-t border-gray-200 z-50 md:hidden">
  <div class="flex justify-between px-4 py-2 text-xs text-gray-700">

    <!-- Home -->
    <a href="index.php" class="flex flex-col items-center <?php echo ($currentPage == 'index.php') ? 'text-red-500' : 'text-gray-500'; ?>">
      <i class="fas fa-house text-lg"></i>
      <span>Home</span>
    </a>

    <!-- Cart -->
    <a href="cart.php" class="relative flex flex-col items-center <?php echo ($currentPage == 'cart.php') ? 'text-red-500' : 'text-gray-500'; ?>">
  
  <!-- Bag Icon -->
  <i class="fas fa-bag-shopping text-lg"></i>

  <!-- Count Badge (now directly positioned) -->
  <?php if ($cart_count > 0): ?>
  <span class="absolute top-0 left-3 h-4.9 w-4.2 bg-red-500 text-white text-[11px] px-1.5 py-0.5 rounded-full leading-none">
    <?= $cart_count ?>
  </span>
  <?php endif; ?>

  <!-- Label -->
  <span class="text-xs mt-0">Cart</span>
</a>


    <!-- Wishlist -->
    <a href="wishlist.php" class="flex flex-col items-center <?php echo ($currentPage == 'wishlist.php') ? 'text-red-500' : 'text-gray-500'; ?>">
      <i class="fas fa-star text-lg"></i>
      <span>Wishlist</span>
    </a>

    <!-- Profile -->
    <a href="useraccount.php" class="flex flex-col items-center <?php echo ($currentPage == 'useraccount.php') ? 'text-red-500' : 'text-gray-500'; ?>">
      <i class="fas fa-user-circle text-lg"></i>
      <span>Profile</span>
    </a>

    <!-- Sell -->
    <a href="seller_dashboard.php" class="flex flex-col items-center <?php echo ($currentPage == 'add_product.php') ? 'text-red-500' : 'text-gray-500'; ?>">
      <i class="fas fa-store text-lg"></i>
      <span>Sell</span>
    </a>

  </div>
</nav>
<br>
<br>


</html>
