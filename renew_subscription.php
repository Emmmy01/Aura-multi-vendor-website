<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Renew Subscription</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded shadow-md max-w-md text-center">
    <h1 class="text-2xl font-bold text-red-600 mb-4">Subscription Expired</h1>
    <p class="mb-4 text-gray-700">Your 30-day seller subscription has expired.</p>
    <p class="mb-4">To continue selling on Aura, please renew your subscription.</p>
    <p class="font-bold text-lg mb-6">₦5,000 per month</p>
    
    <!-- Replace with your payment gateway integration -->
    <a href="payment.php" class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700 transition">Renew Now</a>
  </div>
</body>
</html>
