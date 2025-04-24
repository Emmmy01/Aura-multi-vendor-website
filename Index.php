<?php

session_start();
include 'db.php';

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
$user_id = $_SESSION['user_id'] ?? null;
$hasCartItems = false;

if ($user_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $hasCartItems = $stmt->fetchColumn() > 0;
}

?>
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>


<!DOCTYPE html>
<html lang="en">  
<head>

  <meta charset="UTF-8">
  <link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#000000">

  <title>Aura Marketplace</title>
  <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 

  
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

</head>

<style>
  .swiper {
  padding: 0 16px;
}

.swiper-wrapper {

  padding-right: 19px;
}

.swiper-slide {
  background: linear-gradient(to right, #000, #1a1a1a, #000);
  color: white;
  padding: 16px;
  display:none;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-right: 0px;
  width: 0px;
  height: 200px;
  box-sizing: border-box;
  
}

.slide-text {
  max-width: 60%;
}
.swiper-pagination{
  color:  #ef4444;
}

.badge {
  background: white;
  color: black;
  font-size: 12px;
  padding: 4px 12px;
  border-radius: 999px;
  display: inline-block;
  width: max-content;
}

.slide_text h2 {
  font-size: 18px;
  font-weight: 600;
  margin-top: 12px;
}

.discount {
  font-size: 14px;
  margin-top: -3px;
}

.discount span {
  font-size: 18px;
  font-weight: bold;
}

.small-text {
  font-size: 10px;
  margin-top: 4px;
}

.btn {
  margin-top: 12px;
  background: #ef4444;
  color: white;
  font-size: 14px;
  padding: 6px 16px;
  border: none;
  border-radius: 999px;
  cursor: pointer;
}

.slide-img {
  width: 130px;
  height: auto;
  object-fit: contain;
}
.slide-img2 {
  width: 170px;
  height: auto;
  object-fit: contain;
}
@media (min-width: 1025px) {
  .swiper{
    display:none;
  }
  * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Segoe UI", sans-serif;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 30px;
  background: #fff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.nav-left {
  display: flex;
  align-items: center;
  gap: 20px;
}

.nav-left i {
  font-size: 20px;
  cursor: pointer;
}

nav a {
  margin: 0 10px;
  text-decoration: none;
  color: #333;
  font-weight: 500;
}

nav a:hover {
  color: red;
}

.promo-bar {
  font-size: 14px;
  color: red;
}

.main-banner {
  display: flex;
  padding: 30px;
  gap: 20px;
  flex-wrap: wrap;
  background: #fff;
}

.banner-left {
  flex: 2;
  background: linear-gradient(to right, rgb(11, 11, 11), rgb(52, 51, 51));
  color: #fff;
  padding: 70px;

  border-radius: 10px;
  position: relative;
}

.banner-left h2 {
  font-size: 28px;
}

.banner-left span {
  color: yellow;
  font-weight: bold;
}

.banner-left p {
  margin: 10px 0;
  font-size: 14px;
}

.banner-left button {
  padding: 10px 20px;
  background: #fff;
  border: none;
  border-radius: 20px;
  color: #333;
  font-weight: bold;
  cursor: pointer;
}

.banner-left img {
  width: 260px;
  position: absolute;
  right: 130px;
  top: 30px;
}

.banner-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.box {
  flex: 1;
  color: #fff;
  padding: 20px;
  border-radius: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.ipad {
  background: linear-gradient(to right, #520c98, #881baf);
}

.gamepad {
  background: #d62424;
}

.box h3 {
  font-size: 18px;
}

.box p {
  font-size: 13px;
}

.box img {
  width: 100px;
}

@media (max-width: 768px) {
  .main-banner {
    flex-direction: column;
  }

  .banner-left img {
    position: static;
    width: 100px;
    display: block;
    margin: 20px auto 0;
  }

  .banner-right {
    flex-direction: row;
    gap: 10px;
  }

  .box {
    flex: 1;
    flex-direction: column;
    text-align: center;
  }

  .box img {
    margin-top: 10px;
  }
  
}

.swiper-pagination{
  color:rgb(15, 15, 15);
}


}
.swiper-pagination{
  color:  #ef4444;
}

  /* Change inactive bullet color */
  .swiper-pagination-bullet {
    background-color: #ef4444 !important;
  }

  /* Optional: Change active bullet color too */
  .swiper-pagination-bullet-active {
    background-color: #ef4444 !important;
  }

  @media (min-width: 768px) {

  .discount2 {
  font-size: 80px;
  margin-top: -3px;
}

.discount2 span {
  font-size: 58px;
  font-weight: bold;
}
.banner-left h2{
  font-size:30px;
}
.banner-left p{
  font-size:19px;
}
.small-text2 {
  font-size: 30px;
  margin-top: 4px;
}
.banner-left {
  height:470px;
}
.btn2 {
  margin-top: 12px;
  background: #ef4444;
  color: white;
  font-size: 14px;
  padding: 6px 16px;
  border: none;
  border-radius: 999px;
  cursor: pointer;
}
.banner-left img {
  width: 290px;
  position: absolute;
  right: 50px;
  top: 140px;
}
.box img{
  width: 190px;
  height: auto;
  object-fit: contain;
}
.slide-img2 {
  width: 220px;
  height: auto;
  object-fit: contain;
}
.banner-left button {
  padding: 20px 30px;
  background: #d62424;
  border: none;
  border-radius: 30px;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
  margin-top:70px;
}
.left{
  margin-left:80px;
}
.main-banner {
  display: flex;
  gap: 2rem;
  padding: 2rem;
  background: whitesmoke;
  justify-content: space-between;
}

.hero-wrapper {
  flex: 1.2;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero-content {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 2rem;
}

.hero-text {
  max-width: 400px;
}

.hero-text h2 {
  font-size: 2rem;
  font-weight: 700;
}

.discount {
  font-size: 1.2rem;
  margin: 0.5rem 0;
}

.discount span {
  color:#ef4444;
  font-weight: bold;
  font-size: 1.4rem;
}

.shop-btn {
  padding: 0.6rem 1.3rem;
  background:#ef4444;
  color: white;
  border: none;
  border-radius: 8px;
  margin-top: 1rem;
  cursor: pointer;
}

.hero-image img {
  max-width: 0px;
  height: auto;
}

/* Right Column Section */
.banner-right {
  flex: 0.8;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.box {
  background:rgba(12, 12, 12, 0.98);
  padding: 1.5rem;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.slide-text {
  max-width: 200px;
}

.slide-text h2 {
  font-size: 1.4rem;
  font-weight: bold;
}

.small-text {
  font-size: 0.9rem;
  color: #fff;
}

.btn {
  margin-top: 0.8rem;
  padding: 0.5rem 1.2rem;
  background:rgb(254, 250, 250);
  color: black;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.box img {
  max-width: 150px;
  height: auto;
}


  @keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
  }
  .fade {
  opacity: 0;
  transition: opacity 1s ease-in-out;
}

.fade.show {
  opacity: 1;
}

  }

  @keyframes slideIn {
    0% { transform: translateY(50px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
  }

  .animate-slide-in {
    animation: slideIn 0.5s ease-out forwards;
  }


</style>
<link rel="manifest" href="manifest.json">

</head>
  <body>
    <!-- Chat Bubble -->
<!-- Chat Bubble -->
<div id="chatToggle" onclick="toggleChat()" class="fixed bottom-20 right-5 bg-black text-white p-3 rounded-full shadow-lg cursor-pointer z-50">
  <i class="fas fa-comment-dots text-xl"></i>
</div>

<!-- Chat Box -->
<div id="chatBox" class="hidden fixed bottom-20 right-5 w-80 bg-white border shadow-xl rounded-lg z-50">
  <div class="bg-black text-white p-3 rounded-t-lg flex justify-between items-center">
    <span>💬 Ask NEXA</span>
    <button onclick="toggleChat()" class="text-white text-lg">&times;</button>
  </div>
  <div class="p-4 h-64 overflow-y-auto text-sm space-y-2" id="chatMessages">
    <div class="text-gray-600 bg-gray-100 p-2 rounded-lg">Hey there! 👋 Howfar? I dey for your side. What can I help you with today?</div>
  </div>
  <div class="p-3 border-t flex items-center">
    <input type="text" id="chatInput" placeholder="Type a message…" class="flex-grow p-2 border rounded-l-md text-sm focus:outline-none">
    <button onclick="sendMessage()" class="bg-black text-white px-3 py-2 rounded-r-md text-sm">Send</button>
  </div>
</div>

<script>
  const chatBox = document.getElementById('chatBox');
  const chatInput = document.getElementById('chatInput');
  const chatMessages = document.getElementById('chatMessages');

  // Keyword to detailed responses
  const keywordAnswers = {
    // Greetings
    "hi": "Hello! 👋 Howfar? Welcome to Aura. What can I help you with?",
    "hey": "Hey! How you dey? What can I assist you with?",
    "hello": "Hello there! How can I help you today?",
    "howfar": "I dey gidigba! How can I assist you today?",

    // Account & Registration
    "register": "To register, just click the 'Register' link at the top and fill out your details. 📋",
    "sign up": "Simply click 'Register', enter your details and you’ll be good to go! 🎉",
    "account": "You can access your account dashboard under 'Profile' in the top menu. 🔑",
    "change password": "Go to your User Account → Settings → Change Password, and enter your new one. 🔒",
    "change email": "In your Account Settings, you’ll find an option to update your email address. 📧",
    "recover account": "Click 'Forgot Password' on the login page, enter your email, and follow the link we send you. 📩",

    // Product Selection & Cart
    "select product": "To select a product, browse our categories or use the search bar. Once you find a product, click 'Add to Cart'. 🛒",
    "add to cart": "Great choice! You can now view your cart and proceed to checkout when you’re ready. 🛍️",
    "cart": "Check your cart by clicking the cart icon at the top right. You can adjust quantities or remove items here. 📦",

    // Checkout Process
    "checkout": "During checkout, you'll be asked to provide your delivery details like address and phone number. 🏠",
    "delivery details": "Enter your full address, phone number, and any delivery instructions in the checkout page. 📍",
    "payment": "We accept bank transfer, debit cards, USSD, and Paystack for payments. 💳",
    "submit payment receipt": "Once you’ve made your payment, please submit the payment receipt through WhatsApp to confirm your order. 📲",
    "choose payment method": "Choose your preferred payment method—either bank transfer, card, or USSD during checkout. 💳",

    // Shipping & Notifications
    "order": "Once you place an order, you’ll get an Order ID. You can use this ID to track your order status. 📦",
    "order": "Once you place an order, you’ll get an Order ID. You can use this ID to track your order status. 📦",
    "shipping": "Free shipping on orders over ₦50,000! A small fee applies to orders under that amount. 🚚",
    "order status": "Use your order ID on the 'Track Order' page to see your order's current status. 📍",

    // Seller Interaction (WhatsApp)
    "sell": "To sell on Aura, register as a seller then activate via our subscription page. 🏷️",
    "selling": "After becoming a seller, upload products from your dashboard and watch them fly off the shelf! 🚀",
"seller": "To sell on Aura, register as a seller then activate via our subscription page. 🏷️",
  "add to cart": "To add a product to your cart, just click the 'Add to Cart' button on the product page. 🛒",
"checkout": "After adding items to your cart, go to checkout, fill in your delivery details, choose a payment method, and you're good to go! ✅",
"pay": "You can pay using a bank transfer, card or USSD. Once done, send the receipt to the seller via WhatsApp. 💳",
"order": "Once you place an order, you’ll get an Order ID to track it on the 'Track Order' page. 📦",
"buy": "To buy something on Aura, just select a product, add it to your cart, proceed to checkout, fill in your delivery details, and choose your payment method. You can also chat with the seller directly on WhatsApp to confirm or share your payment receipt. 🛍️",



    "chat with seller": "To chat with the seller, click the WhatsApp icon in your order confirmation page or go to our Contact page. 📱",
    "contact seller": "You can contact the seller on WhatsApp to discuss product details, confirm your order, or send your payment receipt. 💬",
    "notify seller": "Notify the seller via WhatsApp once you’ve made the payment. This ensures a smooth transaction. 📲",

    // Returns & Policy
    "return": "You can return unused items within 7 days in their original condition. 🔄",
    "return policy": "Our return policy allows returns within 7 days if the item is unused and in original condition. 🛍️",
    "refund": "For refunds, please reach out to our support team via WhatsApp. 💵",

    // General Questions
    "about": "Aura is your all‑in‑one Nigerian marketplace to buy, sell, and discover amazing deals! 🇳🇬",
    "support": "Need help? Our support team is on WhatsApp or email—check our Contact page for more info. 📞",
    "contact": "Reach us via WhatsApp at +2348012345678 or email support@aura.com. 📧",
    "aura": "Aura is your all‑in‑one Nigerian marketplace to buy, sell, and discover amazing deals! 🇳🇬",
    "cool":            "Glad you think so! 😎",
    "nice":            "Thank you! You’re too kind. 😊",
    "thank":           "You’re welcome! Happy to help anytime. 🙌",
    "thanks":          "Thanks for chatting with Aura! ❤️",
    "ok":          "Thanks for chatting with Aura! ❤️",
    "ok nah":          "Thanks for chatting with Aura! ❤️",
    "ok na":          "Thanks for chatting with Aura! ❤️",
    "thank you":       "Our pleasure! Let me know if there’s anything else.",

    // Fallback
    "default": "Sorry, I didn’t quite get that. Can you please rephrase your question? 🤖"
  };

  function toggleChat() {
    chatBox.classList.toggle('hidden');
  }

  function sendMessage() {
    const userMsg = chatInput.value.trim().toLowerCase();
    if (!userMsg) return;

    appendMessage(userMsg, 'user');
    chatInput.value = "";

    // Typing indicator
    const typing = document.createElement('div');
    typing.id = 'typing';
    typing.className = 'text-gray-400 italic text-sm';
    typing.textContent = 'Typing...';
    chatMessages.appendChild(typing);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    setTimeout(() => {
      document.getElementById('typing').remove();
      let reply = keywordAnswers["default"];
      // Match user input to predefined answers
      for (const key in keywordAnswers) {
        if (userMsg.includes(key)) {
          reply = keywordAnswers[key];
          break;
        }
      }
      appendMessage(reply, 'bot');
    }, 1000);
  }

  function appendMessage(text, type) {
    const msgDiv = document.createElement('div');
    msgDiv.className = type === 'user'
      ? 'bg-black text-white p-2 rounded-lg text-right'
      : 'bg-gray-100 text-gray-700 p-2 rounded-lg';
    msgDiv.textContent = text;
    chatMessages.appendChild(msgDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }
</script>

  <?php if ($hasCartItems): ?>
  <div id="cartReminder" class="fixed bottom-20 right-8 bg-white shadow-2xl rounded-xl p-8 pl-12 border-l-4 border-0 max-w-xs z-50 animate-slide-in">
  <div class="flex items-start justify-between gap-4">
    <div>
      <p class="text-sm font-bold text-gray-900 flex items-center">
        <span class="text-red-600 mr-1 text-lg">👀</span> You left items in your cart!
      </p>
      <a href="cart.php" class="text-xs text-red-600 font-semibold underline hover:no-underline mt-1 inline-block">Continue to Checkout</a>
    </div>
    <button onclick="document.getElementById('cartReminder').remove()" class="text-gray-400 hover:text-red-600 text-xl leading-none font-bold">&times;</button>
  </div>
</div>
<?php endif; ?>
  <!-- Header Main -->
  <header class=" w-full border-b px-4 py-3 lg:px-10 flex flex-col gap-2 lg:gap-0 lg:flex-row items-center justify-between  ">

    <!-- Logo + Search -->
    <div class="flex items-center w-full lg:w-auto gap-4">
      <!-- Logo -->
      <div class=" left w-[70px] h-[`70px]">
        <img src="Gray_and_Black_Simple_Studio_Logo__1_-removebg-preview.png" alt="Aura Logo" class=" object-contain w-full">
        <!-- Replace logo.png with your actual logo path -->
      </div>

      <!-- Category + Search Bar -->
      <div class=" hidden lg:flex flex-grow flex items-center max-w-xl border rounded-md overflow-hidden">
        <select class="bg-gray-100 text-sm px-3 py-2 outline-none border-r">
          <option selected>All Categories</option>
          <option>Phones</option>
          <option>Electronics</option>
          <option>Fashion</option>
        </select>
        <?php
// If you want the home page to show the last search term (optional)
$lastSearch = htmlspecialchars($_GET['search'] ?? '');
?>
  
        <form action="shop.php" method="GET" class="max-w-lg mx-auto my-0">
  <div class="hidden lg:flex items-center bg-white shadow-sm rounded-md overflow-hidden border focus-within:ring-2 focus-within:ring-black transition">
    
    <!-- Input (with name so shop.php can see it) -->
    <input 
      type="text" 
      name="search"
      value="<?= $lastSearch ?>"
      placeholder="Search for products…" 
      class="flex-grow px-4 py-2 text-sm text-gray-700 placeholder-gray-400 focus:outline-none"
    >
    
    <!-- Submit Button -->
    <button 
      type="submit"
      class="bg-black hover:bg-black text-white px-4 py-2 text-sm font-medium transition"
    >
      Search
    </button>

  </div>
</form>
      </div>
    </div>
    <br>
    
    <div class="block  w-full px-4">
    <?php
// If you want the home page to show the last search term (optional)
$lastSearch = htmlspecialchars($_GET['search'] ?? '');
?>
  
<!-- Home Search Bar -->
<form action="shop.php" method="GET" class="max-w-lg mx-auto my-0">
  <div class="flex lg:hidden items-center bg-white shadow-sm rounded-md overflow-hidden border focus-within:ring-2 focus-within:ring-black transition">
    
    <!-- Input (with name so shop.php can see it) -->
    <input 
      type="text" 
      name="search"
      value="<?= $lastSearch ?>"
      placeholder="Search for products…" 
      class="flex-grow px-4 py-2 text-sm text-gray-700 placeholder-gray-400 focus:outline-none"
    >
    
    <!-- Submit Button -->
    <button 
      type="submit"
      class="bg-black hover:bg-black text-white px-4 py-2 text-sm font-medium transition"
    >
      Search
    </button>

  </div>
</form>


    <!-- Right Links -->
    <div class="hidden lg:flex flex items-center gap-6 text-sm mt-3 lg:mt-0">
      <a href="seller_dashboard.php" class="text-gray-600 hover:text-blue-600">Sell On Aura</a>
      <a href="redistration.php" class="text-gray-600 hover:text-blue-600">Register</a>
      <a href="faq.php" class="text-gray-600 hover:text-blue-600">FAQs</a>
      

      <!-- Language & Currency -->
      <select class="bg-transparent text-gray-600 hover:text-blue-600">
        <option>English</option>
        <option>Français</option>
      </select>
  

      <!-- Icons -->
      <div class="relative">
  <!-- Toggle Button -->
  <button onclick="toggleDropdown()" class="flex items-center gap-2 hover:text-red-500 focus:outline-none">
    <img src="user.png" alt="User" class="w-5 h-5">
    <span>Account</span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <!-- Dropdown Menu -->
  <div id="accountDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
    <a href="useraccount.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">User Account</a>
    <a href="seller_dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Seller Account</a>
  </div>
</div>


      <a href="wishlist.php" class="relative hover:text-blue-600 ">
        <img src="like.png" alt="" class="w-5 h-5">
  
      </a>

      <a href="cart.php" class="relative flex items-center hover:text-blue-600">
      <img src="online-shopping.png" alt="" class="w-5 h-5">

        
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full"><?= $cart_count ?></span>
      </a>
    </div>
  </header>




  <header class="hidden lg:flex">
    <div class="nav-left">
   
      <div class="relative group inline-block">
  <!-- Menu Icon -->
  <i class="fas fa-bars"></i>
  <!-- Dropdown Menu -->
  <div class="absolute left-0 top-full z-50 mt-2 w-60 bg-white rounded-lg shadow-lg opacity-0 group-hover:opacity-100 invisible group-hover:visible transition duration-200 ease-in-out">
    <ul class="text-gray-700 text-sm py-2 px-1 space-y-1">
    <a href="category.php?category=Appliances">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="household-appliance.png" class="w-5 h-5" /> Appliances
  </li>
</a>

<a href="category.php?category=Phones and Tablets">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="smartphone.png" class="w-5 h-5" /> Phones & Tablets
  </li>
</a>

<a href="category.php?category=Health and Beauty">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="personal-hygiene.png" class="w-5 h-5" /> Health & Beauty
  </li>
</a>

<a href="category.php?category=Home and Office">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="worker.png" class="w-5 h-5" /> Home & Office
  </li>
</a>

<a href="category.php?category=Electronics">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="responsive.png" class="w-5 h-5" /> Electronics
  </li>
</a>

<a href="category.php?category=Fashion">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="brand.png" class="w-5 h-5" /> Fashion
  </li>
</a>

<a href="category.php?category=Computing">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="pc-tower.png" class="w-5 h-5" /> Computing
  </li>
</a>

<a href="category.php?category=Gaming">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="game-controller.png" class="w-5 h-5" /> Gaming
  </li>
</a>

<a href="category.php?category=Musical Instruments">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="music.png" class="w-5 h-5" /> Musical Instruments
  </li>
</a>

<a href="category.php?category=Other Categories">
  <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
    <img src="more.png" class="w-5 h-5" /> Other Categories
  </li>
</a>

    </ul>
  </div>
</div>

      <span><strong>Browse All Categories</strong></span>
    </div>
    <nav>
      <a href="#">Home</a>
      <a href="shop.php">Shop</a>
      <a href="products.php">Product</a>
      <a href="#">Blog</a>
      <a href="request.html">Send a Request</a>
      <a href="about.php">About Aura</a>
     
    </nav>
    <div class="promo-bar">
     <a href="register.php"><i class="fas fa-gift"></i> Become a seller on Aura</a> 
    </div>
  </header>
<!-- Mobile Navbar -->
<header class="lg:hidden flex items-center justify-between p-4 bg-white shadow-md fixed w-full h-100 top-0 z-50">
  <!-- Logo -->
  <div class="w-[60px] h-[60px]">
    <img src="Gray_and_Black_Simple_Studio_Logo__1_-removebg-preview.png" alt="Aura Logo" class="object-contain w-full h-full" />
  </div>

  <!-- Hamburger / Cancel Icon -->
  <button id="menu-toggle" class="text-2xl text-gray-800 focus:outline-none">
    <i id="menu-icon" class="fas fa-bars"></i>
  </button>
</header>


<!-- Dropdown Menu (Hidden by Default) -->
<div id="mobile-menu" class="lg:hidden fixed top-14 left-0 w-full bg-white shadow-md p-5 space-y-4 transform -translate-y-full transition-all duration-300 z-40">
  <nav class="flex flex-col space-y-3 text-gray-700 text-sm font-medium">
    <a href="#">Home</a>
    <a href="shop.php">Shop</a>
    <a href="products.php">Product</a>
    <a href="about.php">About Aura</a>
    <a href="contact.php">Contact Us</a>
    <a href="faq.php">Faq</a>
    <a href="request.html">Send a Request</a>
    <a href="redistration.php" class="text-red-500">Register</a>
  </nav>
  <div class="mt-4 border-t pt-4">
    <a href="register.php" class="flex items-center text-blue-600 font-semibold">
      <i class="fas fa-gift mr-2"></i> Become a seller on Aura
    </a>
  </div>
</div>


  <section class="main-banner hidden lg:flex">
  <div class="hero-wrapper bg-white py-12 px-6">
  <div class="hero-content max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-10">
    
    <!-- LEFT: Text & Trust Badges -->
    <div class="hero-text max-w-xl">
    <h2 class="text-3xl md:text-4xl font-bold leading-snug">
  <span id="typed-text" class="text-primary"></span>
</h2>

      <p class="discount text-lg mt-4">Up to <span class="text-red-500 font-semibold">40%</span> off</p>
      <p class="tagline text-gray-600 mt-2">Shop Aura today — trusted sellers, great deals await.</p>

      <!-- Shop Button -->
     <a href="shop.php"><button class="shop-btn mt-6 bg-primary text-white px-6 py-3 rounded-lg shadow hover:bg-primary-dark transition">
        Shop Now
      </button></a> 

      <!-- Trust Badges -->
      <div class="mt-8 bg-gray-50 rounded-lg shadow-sm p-4 flex flex-col sm:flex-row items-center justify-start gap-6 text-center text-gray-800 border border-gray-200">
        
        <!-- Delivery in Nigeria -->
        <div class="flex flex-col items-center gap-1">
          <img src="shipped.png" alt="Nigeria Delivery" class="w-8 h-8" />
          <h3 class="text-base font-semibold">Nationwide</h3>
          <p class="text-xs text-gray-500">Delivery in Nigeria</p>
        </div>

        <!-- Divider -->
        <div class="hidden sm:block h-8 w-px bg-gray-300"></div>

        <!-- Secure Checkout -->
        <div class="flex flex-col items-center gap-1">
          <img src="down-payment.png" alt="Secure Checkout" class="w-8 h-8" />
          <h3 class="text-base font-semibold">100% Secure</h3>
          <p class="text-xs text-gray-500">Safe & Protected Checkout</p>
        </div>

        <!-- Divider -->
        <div class="hidden sm:block h-8 w-px bg-gray-300"></div>

        <!-- Easy Setup -->
        <div class="flex flex-col items-center gap-1">
          <img src="store.png" alt="Launch Seller" class="w-8 h-8" />
          <h3 class="text-base font-semibold">Getting Started?</h3>
          <p class="text-xs text-gray-500">Easy Setup for New Sellers</p>
        </div>

      </div>
    </div>

    <!-- RIGHT: Hero Image -->
    <div class="hero-image">
      <img src="30b2432c-e7d4-4a72-9a59-2326a0681e06-removebg-preview.png" alt="Aura Promo" class="w-full max-w-md">
    </div>

  </div>
</div>


    <div class="banner-right">
      <div class="box ipad">
       <div class="slide-text">
   
        <h2>Shop From Home</h2>
        <p class="discount">Up to <span>40%</span> off – Discount Dey!</p>
        <p class="small-text">Only this week | Shop Now</p>
       <a href="Products.php"> <button class="btn">Buy Now</button></a>
        
      </div>
      <img src="A_marketplace_for_handmade__vintage__and_unique_items__often_crafted_by_independent_artisans_and_sel-removebg-preview.png" alt="iPad" class="slide-img">
      </div>
      <div class="box gamepad">
      <div class="slide-text">
     
     <h2>Become A seller</h2>
     <p class="discount"><span>On Aura</span></p>
     <p class="small-text">Add | Sell | Cashout</p>
    <a href="register.php"><button class="btn">Sell On Aura</button></a> 
   </div>
   <img src="Premium_Vector___Online_shopping_background__with_an_illustration_of_shopping_for_clothes_-removebg-preview.png" alt="Vintage" class="slide-img2">
 </div>
    </div>
  </section>
  <div class="swiper mySwiper">
  <div class="swiper-wrapper">
    <!-- Slide 1 -->
    <div class="swiper-slide slide">
      <div class="slide-text">

        <h2>Get </h2>
        <p class="discount">Up to <span>40%</span></p>
        <p class="small-text">When You Shop At Aura | Sellers are here waiting</p>
       <a href="shop.php"> <button class="btn">Shop Now</button></a>
      </div>
      <img src="30b2432c-e7d4-4a72-9a59-2326a0681e06-removebg-preview.png" alt="Offer" class="slide-img">
    </div>

    <!-- Slide 2 -->
    <div class="swiper-slide slide">
      <div class="slide-text">
   
        <h2>Shop From Home</h2>
        <p class="discount">Discount <span>Dey</span></p>
        <p class="small-text">Only this week | Shop Now</p>
       <a href="products.php"> <button class="btn">Buy Now</button></a>
      </div>
      <img src="A_marketplace_for_handmade__vintage__and_unique_items__often_crafted_by_independent_artisans_and_sel-removebg-preview.png" alt="iPad" class="slide-img">
    </div>

    <!-- Slide 3 -->
    <div class="swiper-slide slide">
      <div class="slide-text">
     
        <h2>Become A seller</h2>
        <p class="discount"><span>On Aura</span></p>
        <p class="small-text">Add | Sell | Cashout</p>
      <a href="register.php">  <button class="btn">Sell On Aura</button></a>
      </div>
      <img src="Premium_Vector___Online_shopping_background__with_an_illustration_of_shopping_for_clothes_-removebg-preview.png" alt="Vintage" class="slide-img2">
    </div>
  </div>

  <div class="swiper-pagination"></div>
</div>


<!-- Category Icons -->
<section  class="lg:hidden py-8 px-4 bg-white">
  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-800">Category</h2>
    <a href="shop.php" class="text-sm text-red-500 font-medium hover:underline">See All</a>
  </div>

  <!-- Categories Grid -->
  <div class="grid grid-cols-4 gap-4 text-center text-sm text-gray-700">
    
    <!-- Clothes -->
    <a href="category.php?category=Clothes" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
        <i class="fas fa-tshirt text-xl"></i>
      </div>
      <p class="mt-2 font-medium text-gray-800">Clothes</p>
    </a>

    <!-- Electronics -->
    <a href="category.php?category=Electronics" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
        <i class="fas fa-tv text-xl"></i>
      </div>
      <p class="mt-2 font-medium text-gray-800">Electronics</p>
    </a>

    <!-- Shoes -->
    <a href="category.php?category=Shoes" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
        <i class="fas fa-shoe-prints text-xl"></i>
      </div>
      <p class="mt-2 font-medium text-gray-800">Shoes</p>
    </a>

    <!-- Home -->
    <a href="category.php?category=Home" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
        <i class="fas fa-blender text-xl"></i>
      </div>
      <p class="mt-2 font-medium text-gray-800">Home</p>
    </a>

  </div>
</section>

<section class=" hidden lg:block py-8 px-16 bg-white">
  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-800">Category</h2>
    <a href="shop.php" class="text-sm text-red-500 font-medium hover:underline">See All</a>
  </div>

  <!-- Categories Grid -->
  <div class="grid grid-cols-6 gap-4 text-center text-sm text-gray-700">
    
    <!-- Clothes -->
    <a href="category.php?category=Clothes" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
        <i class="fas fa-tshirt text-xl"></i>
      </div>
      <p class="mt-2 font-medium text-gray-800">Clothes</p>
    </a>

    <!-- Electronics -->
    <a href="category.php?category=Electronics" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
        <i class="fas fa-tv text-xl"></i>
      </div>
      <p class="mt-2 font-medium text-gray-800">Electronics</p>
    </a>
  <!-- Clothes -->
  <a href="category.php?category=Women" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
      <i class="fas fa-female text-xl"></i>

      </div>
      <p class="mt-2 font-medium text-gray-800">Women</p>
    </a>

    <!-- Electronics -->
    <a href="category.php?category=Men" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
        <i class="fas fa-male text-xl"></i>
      </div>
      <p class="mt-2 font-medium text-gray-800">Men</p>
    </a>
    <!-- Shoes -->
    <a href="category.php?category=Shoes" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
        <i class="fas fa-shoe-prints text-xl"></i>
      </div>
      <p class="mt-2 font-medium text-gray-800">Shoes</p>
    </a>

    <!-- Home -->
    <a href="category.php?category=Home" class="flex flex-col items-center hover:scale-105 transition-transform duration-200">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center text-red-500">
        <i class="fas fa-blender text-xl"></i>
      </div>
      <p class="mt-2 font-medium text-gray-800">Home</p>
    </a>

  </div>
</section>

<?php include 'db.php'; ?>
<section class="py-12 px-4 md:px-12 bg-gray-50">
  <div class="mb-6 flex justify-between items-center">
    <h2 class="text-lg font-semibold text-red-600">
       HOT DEALS! 
    </h2>
    <div class="flex items-center gap-1 text-sm text-gray-700">
  <span>Ends in:</span>
  <div id="hours" class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">00</div>
  <div id="minutes" class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">00</div>
  <div id="seconds" class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">00</div>
</div>
</div>
<script>
  // Set countdown to 24 hours from now
  const endTime = new Date().getTime() + (24 * 60 * 60 * 1000); // 24 hours

  const updateCountdown = () => {
    const now = new Date().getTime();
    const distance = endTime - now;

    if (distance <= 0) {
      document.getElementById("hours").textContent = "00";
      document.getElementById("minutes").textContent = "00";
      document.getElementById("seconds").textContent = "00";
      return;
    }

    const hours = String(Math.floor((distance / (1000 * 60 * 60)) % 24)).padStart(2, '0');
    const minutes = String(Math.floor((distance / (1000 * 60)) % 60)).padStart(2, '0');
    const seconds = String(Math.floor((distance / 1000) % 60)).padStart(2, '0');

    document.getElementById("hours").textContent = hours;
    document.getElementById("minutes").textContent = minutes;
    document.getElementById("seconds").textContent = seconds;
  };

  setInterval(updateCountdown, 1000);
  updateCountdown(); // Initial call
</script>

  

  <div id="product-container" class="grid grid-cols-2 md:grid-cols-5 gap-4 items-stretch">
    <?php
  $stmt = $conn->prepare("SELECT * FROM products WHERE discount_percentage IN (0, 10, 20, 30, 40, 50) ORDER BY created_at DESC LIMIT 10");

      $stmt->execute();
   
    ?>
  </div>

  <div id="product-container" class="grid grid-cols-2 md:grid-cols-5 gap-4 items-stretch">
  <!-- your initial items will be injected here -->
</div>

<!-- this hidden input is no longer strictly necessary, but you can keep it if you like -->
<input type="hidden" id="offset" value="0">

<!-- scroll “sentinel” -->
<div id="sentinel" class="h-1"></div>

</section>
<?php
// get last viewed category
$sql = "SELECT p.category FROM products p 
        JOIN view_history v ON p.id = v.product_id 
        WHERE v.user_id = :user_id 
        ORDER BY v.viewed_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$lastViewed = $stmt->fetch(PDO::FETCH_ASSOC);

if ($lastViewed) {
    $category = $lastViewed['category'];
    
    // Suggest products from the same category
    $suggest = $conn->prepare("SELECT * FROM products WHERE category = :cat ORDER BY RAND() LIMIT 4");
    $suggest->execute([':cat' => $category]);
    $suggestions = $suggest->fetchAll(PDO::FETCH_ASSOC);
}
?>
<?php if (!empty($suggestions)): ?>
  <section class="py-12 px-4 md:px-12">
    <h3 class="text-lg font-semibold mb-2 px-2 mt-6 text-red-600">YOU MIGHT ALSO LIKE…</h3>
    <div class="overflow-x-auto">
      <div class="flex space-x-4 px-2 pb-2 w-max">
        <?php foreach ($suggestions as $item): ?>
          <div class="bg-white p-3 shadow-md rounded-lg h-[240px] w-[150px] flex flex-col justify-between text-center">
            <a href="product_detail.php?id=<?= $item['id'] ?>">
              <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-40 object-contain mx-auto mb-2" />
              <h2 class="text-sm truncate" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></h2>
              <p class="text-black font-bold text-sm mt-2 text-left">
                ₦<?= number_format($item['price'], 0, '.', ',') ?>
              </p>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>


<!-- Bottom Nav (Mobile Only) -->
<?php
$currentPage = basename($_SERVER['PHP_SELF']); // Get current page filename
?>
<br>

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
<!-- JS for Load More -->
<script>
  let offset = 0;
  let loading = false;
  let allLoaded = false;

  async function loadMore() {
    if (loading || allLoaded) return;
    loading = true;

    try {
      const res = await fetch(`load_more.php?offset=${offset}`);
      const html = await res.text();

      if (html.trim()) {
        document
          .getElementById('product-container')
          .insertAdjacentHTML('beforeend', html);

        offset += 10;
      } else {
        // no more results
        allLoaded = true;
        observer.unobserve(sentinel);
      }
    } catch (e) {
      console.error("Failed to load more products:", e);
    } finally {
      loading = false;
    }
  }

  // create our IntersectionObserver
  const sentinel = document.getElementById('sentinel');
  const observer = new IntersectionObserver(entries => {
    for (let entry of entries) {
      if (entry.isIntersecting) {
        loadMore();
      }
    }
  }, {
    rootMargin: '200px'  // start loading before the user actually hits the bottom
  });
  observer.observe(sentinel);

  // kick things off
  loadMore();
</script>


<script>
  function toggleDropdown() {
    const dropdown = document.getElementById("accountDropdown");
    dropdown.classList.toggle("hidden");
  }

  // Optional: Hide dropdown when clicking outside
  window.addEventListener('click', function(e) {
    const button = document.querySelector('button[onclick="toggleDropdown()"]');
    const dropdown = document.getElementById("accountDropdown");

    if (!button.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add("hidden");
    }
  });
</script>
<script>
  const swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 20,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
  });
</script>
<script>
  const toggleBtn = document.getElementById('menu-toggle');
  const menu = document.getElementById('mobile-menu');
  const icon = document.getElementById('menu-icon');

  toggleBtn.addEventListener('click', () => {
    const isOpen = menu.classList.contains('translate-y-0');

    menu.classList.toggle('-translate-y-full');
    menu.classList.toggle('translate-y-0');

    // Toggle between hamburger and cancel icon
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
  });
</script>


<script>
  const phrases = [
    "Turning Deals Into Dreams — Buy & Sell on Aura.",
    "Shop Easily & Securely — Only on Aura.",
    "New Sellers Welcome — Get Started Today!"
  ];

  const typedText = document.getElementById("typed-text");
  let phraseIndex = 0;

  function showNextPhrase() {
    typedText.classList.remove("show"); // fade out
    setTimeout(() => {
      typedText.innerText = phrases[phraseIndex];
      typedText.classList.add("show"); // fade in
      phraseIndex = (phraseIndex + 1) % phrases.length;
    }, 500); // half duration of fade transition
  }

  // Initial display
  typedText.innerText = phrases[phraseIndex];
  typedText.classList.add("show");
  phraseIndex++;
  typedText.classList.remove("opacity-100");
typedText.classList.add("opacity-0");

setTimeout(() => {
  typedText.innerText = phrases[phraseIndex];
  typedText.classList.remove("opacity-0");
  typedText.classList.add("opacity-100");
}, 500);

  // Loop every 4 seconds
  setInterval(showNextPhrase, 4000);
</script>

<!-- Loader -->
<div id="loader" class="fixed inset-0 bg-white z-[9999] flex items-center justify-center">
  <div class="loader animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-black"></div>
</div>

<script>
  // Wait until everything loads
  window.addEventListener("load", function () {
    const loader = document.getElementById("loader");
    loader.style.display = "none";
  });
</script>

<style>
  .loader {
    border-top-color: #f00;
    border-radius: 50%;
  }
</style>
<!-- Register Service Worker -->
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('service-worker.js')
        .then(reg => console.log('✅ Service Worker registered!', reg))
        .catch(err => console.error('❌ Service Worker failed', err));
    });
  }
</script>

<!-- Allow Native Add to Home Screen Prompt -->
<script>
  window.addEventListener('beforeinstallprompt', (e) => {
    // Don't call e.preventDefault()
    console.log('📲 A2HS prompt will show natively when conditions are met');
  });
</script>

</body>
</html>








   
  
