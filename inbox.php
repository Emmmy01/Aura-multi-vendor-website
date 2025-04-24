<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Aura Inbox</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col">

  <!-- Top Bar -->
  <header class="bg-white shadow px-4 py-3 flex items-center justify-between md:hidden">
    <h1 class="text-lg font-semibold">Inbox</h1>
    <button id="toggleList" class="text-gray-600 focus:outline-none">
      <i class="fas fa-bars text-xl"></i>
    </button>
  </header>

  <div class="flex flex-1 overflow-hidden md:flex-row flex-col">

    <!-- Sidebar: Message List -->
    <aside id="sidebar" class="bg-white border-r overflow-y-auto transition-all duration-300
                              w-full md:w-64 md:block"
           >
      <div class="px-4 py-2 font-medium text-gray-700 border-b hidden md:block">Messages</div>
      <ul>
        <li class="border-b">
          <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 bg-gray-50">
            <div class="flex-1">
              <p class="text-gray-800 font-semibold">Welcome to Aura!</p>
              <p class="text-gray-500 text-sm">Aura Team — You go enjoy this one</p>
            </div>
          </a>
        </li>
        <!-- Repeat <li> for more messages -->
      </ul>
    </aside>

    <!-- Main Content: Message View -->
    <main class="flex-1 overflow-y-auto p-4 md:p-6">
      <div class="bg-white rounded-lg shadow p-6">
        <header class="border-b pb-4 mb-4">
          <h2 class="text-xl md:text-2xl font-bold text-gray-800">Welcome to Aura!</h2>
          <div class="text-gray-500 mt-1 text-xs md:text-sm">
            <span class="font-medium">From:</span> Aura Team &lt;no-reply@aura.com&gt;
            <span class="mx-2">|</span>2025
          </div>
        </header>
        <div class="space-y-4 text-gray-700 text-sm md:text-base leading-relaxed">
          <p>Hi there,</p>
          <p>Thank you for registering with <span class="font-semibold text-red-600">Aura</span>! We're thrilled to have you on board.</p>
          <p>Start shopping now to enjoy exclusive deals, curated collections, and a seamless buying experience tailored just for you.</p>
          <p>Happy shopping! 🛍️</p>
          <p>— The Aura Team</p>
        </div>
        <div class="mt-6 text-center">
          <a href="shop.php"
             class="inline-block bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-md transition text-sm md:text-base">
            Start Shopping
          </a>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Toggle sidebar on mobile
    const btn = document.getElementById('toggleList');
    const sidebar = document.getElementById('sidebar');
    let open = true;
    btn.addEventListener('click', () => {
      open = !open;
      sidebar.style.display = open ? 'block' : 'none';
    });
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

  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>
