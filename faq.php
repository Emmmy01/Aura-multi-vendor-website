<?php
// faq.php
session_start();
// include 'header.php'; // if you have a common header
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FAQ – Aura Shop</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <header class="bg-white shadow-sm">
    <div class="max-w-6xl mx-auto flex items-center justify-between p-4">
    <div class="w-[60px] h-[60px]">
    <img src="Gray_and_Black_Simple_Studio_Logo__1_-removebg-preview.png" alt="Aura Logo" class="object-contain w-full h-full" />
  </div>
      <nav class="space-x-4">
        <a href="index.php" class="hover:text-red-600">Home</a>
   
      </nav>
    </div>
  </header>

  <main class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Frequently Asked Questions</h1>
    
    <div class="space-y-4">
      
      <!-- FAQ Item 1 -->
      <div class="bg-white rounded-lg shadow">
        <button 
          class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
          onclick="toggleFaq(1)"
        >
          <span>How do I place an order?</span>
          <i id="icon-1" class="fas fa-plus text-gray-500"></i>
        </button>
        <div id="answer-1" class="px-4 pb-4 text-gray-700 hidden">
          To place an order, browse our Shop, click on a product to see details, choose your options, then click “Add to Cart.” When you’re ready, go to your cart, review your items, and proceed to checkout.
        </div>
      </div>

      <!-- FAQ Item 2 -->
      <div class="bg-white rounded-lg shadow">
        <button 
          class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
          onclick="toggleFaq(2)"
        >
          <span>What payment methods do you accept?</span>
          <i id="icon-2" class="fas fa-plus text-gray-500"></i>
        </button>
        <div id="answer-2" class="px-4 pb-4 text-gray-700 hidden">
          We accept only cash on delivery and bank transfer.
        </div>
      </div>

      <!-- FAQ Item 3 -->
      <div class="bg-white rounded-lg shadow">
        <button 
          class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
          onclick="toggleFaq(3)"
        >
          <span>Can I return or exchange an item?</span>
          <i id="icon-3" class="fas fa-plus text-gray-500"></i>
        </button>
        <div id="answer-3" class="px-4 pb-4 text-gray-700 hidden">
          Yes—if your product is defective or doesn’t match the description, you can request a return or exchange within 2 days of delivery. Please contact the seller with your order number.
        </div>
      </div>

      <!-- FAQ Item 4 -->
      <div class="bg-white rounded-lg shadow">
        <button 
          class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
          onclick="toggleFaq(4)"
        >
          <span>How long does shipping take?</span>
          <i id="icon-4" class="fas fa-plus text-gray-500"></i>
        </button>
        <div id="answer-4" class="px-4 pb-4 text-gray-700 hidden">
          Standard shipping usually takes 3–5 business days within Lagos, and up to 7–10 business days nationwide. Express options are available at checkout.
        </div>
      </div>

      <!-- FAQ Item 5 -->
      <div class="bg-white rounded-lg shadow">
        <button 
          class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
          onclick="toggleFaq(5)"
        >
          <span>Do you ship internationally?</span>
          <i id="icon-5" class="fas fa-plus text-gray-500"></i>
        </button>
        <div id="answer-5" class="px-4 pb-4 text-gray-700 hidden">
          Currently we ship only within Nigeria, but we’re working on expanding to other countries soon—stay tuned!
        </div>
      </div>
<!-- FAQ Item 6 - How to sell on Aura -->
<div class="bg-white rounded-lg shadow">
  <button 
    class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
    onclick="toggleFaq(6)"
  >
    <span>How can I sell on Aura?</span>
    <i id="icon-6" class="fas fa-plus text-gray-500"></i>
  </button>
  <div id="answer-6" class="px-4 pb-4 text-gray-700 hidden">
    To sell on Aura, you need to register as a seller and activate your account by subscribing with ₦5,000.
  </div>
</div>

<!-- FAQ Item 7 - Subscription cost -->
<div class="bg-white rounded-lg shadow">
  <button 
    class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
    onclick="toggleFaq(7)"
  >
    <span>How much do I have to pay to become a seller?</span>
    <i id="icon-7" class="fas fa-plus text-gray-500"></i>
  </button>
  <div id="answer-7" class="px-4 pb-4 text-gray-700 hidden">
    The seller subscription costs ₦5,000 only.
  </div>
</div>

<!-- FAQ Item 8 - Subscription renewal -->
<div class="bg-white rounded-lg shadow">
  <button 
    class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
    onclick="toggleFaq(8)"
  >
    <span>When will I renew my subscription?</span>
    <i id="icon-8" class="fas fa-plus text-gray-500"></i>
  </button>
  <div id="answer-8" class="px-4 pb-4 text-gray-700 hidden">
    Subscription is renewed every month to keep your account active as a seller on Aura.
  </div>
</div>

<!-- FAQ Item 9 - Aura's mission -->
<div class="bg-white rounded-lg shadow">
  <button 
    class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
    onclick="toggleFaq(9)"
  >
    <span>What is Aura all about?</span>
    <i id="icon-9" class="fas fa-plus text-gray-500"></i>
  </button>
  <div id="answer-9" class="px-4 pb-4 text-gray-700 hidden">
    Aura is an online marketplace designed to connect buyers with trusted sellers offering quality products across Nigeria.
  </div>
</div>

<!-- FAQ Item 10 - Benefits of selling on Aura -->
<div class="bg-white rounded-lg shadow">
  <button 
    class="w-full px-4 py-3 text-left flex justify-between items-center focus:outline-none"
    onclick="toggleFaq(10)"
  >
    <span>Why should I sell on Aura?</span>
    <i id="icon-10" class="fas fa-plus text-gray-500"></i>
  </button>
  <div id="answer-10" class="px-4 pb-4 text-gray-700 hidden">
    Aura gives sellers access to a large customer base, easy product uploads, secure payments, and dedicated support to grow your business.
  </div>
</div>

    </div>
  </main>

  <script>
    function toggleFaq(id) {
      const ans = document.getElementById(`answer-${id}`);
      const icon = document.getElementById(`icon-${id}`);
      if (ans.classList.contains('hidden')) {
        ans.classList.remove('hidden');
        icon.classList.replace('fa-plus', 'fa-minus');
      } else {
        ans.classList.add('hidden');
        icon.classList.replace('fa-minus', 'fa-plus');
      }
    }
  </script>

  <!-- Font Awesome CDN for icons -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
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

</body>
</html>
