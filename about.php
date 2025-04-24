<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Aura</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- Navbar (optional) -->
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
  <main>
    <!-- Hero Banner -->
    <section class="bg-cover bg-center h-64" style="background-image:url('11 Insider Secrets From Real Estate Agents.jpg')">
      <div class="bg-black bg-opacity-40 h-full flex items-center">
        <div class="max-w-4xl mx-auto text-center text-white px-4">
          <h1 class="text-4xl font-extrabold mb-2">About Aura</h1>
          <p class="text-lg">A seamless marketplace where university students can effortlessly buy and sell—all in one organized place.</p>
        </div>
      </div>
    </section>

    <!-- Our Story -->
    <section class="max-w-4xl mx-auto py-12 px-4">
  <h2 class="text-2xl font-bold mb-4">Our Story</h2>
  <p class="leading-relaxed text-gray-700 mb-6">
    Aura was founded by a student who saw the chaos of university trading groups on WhatsApp—hours wasted scrolling, missed opportunities, and endless screenshots.
    Driven by the need for an organized, intuitive platform, Aura was born to bring structure and efficiency to campus commerce.
    Now, buying and selling is as easy as a few clicks, giving students more time to focus on what matters.
  </p>
  <p class="text-sm text-gray-600">
    Hi, I'm <span class="font-semibold text-gray-800">Jeremiah</span>, the founder of Aura. This platform was built out of a passion to solve real marketplace problems in university communities.
    Feel free to <a href="https://www.linkedin.com/in/ajibade-jeremiah-987323346?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app" target="_blank" class="text-blue-500 hover:underline">connect with me on LinkedIn</a> to learn more or collaborate.
  </p>
</section>

    <!-- Mission & Vision -->
    <section class="bg-white py-12">
      <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 px-4">
        <div>
          <h3 class="text-xl font-semibold mb-2 flex items-center">
            <i class="fas fa-bullseye text-red-500 mr-2"></i> Our Mission
          </h3>
          <p class="text-gray-700">
            To empower university communities with a user-friendly marketplace that brings order to campus trading—so students spend less time scrolling and more time succeeding.
          </p>
        </div>
        <div>
          <h3 class="text-xl font-semibold mb-2 flex items-center">
            <i class="fas fa-eye text-red-500 mr-2"></i> Our Vision
          </h3>
          <p class="text-gray-700">
            To be the go-to marketplace for every campus, creating a seamless ecosystem where every student can effortlessly buy and sell with confidence.
          </p>
        </div>
      </div>
    </section>

    <!-- Core Values -->
    <section class="max-w-4xl mx-auto py-12 px-4">
      <h2 class="text-2xl font-bold mb-6">Our Core Values</h2>
      <ul class="space-y-4 text-gray-700">
        <li class="flex items-start">
          <i class="fas fa-list-alt text-blue-500 mt-1 mr-2"></i>
          <span><strong>Organization:</strong> We believe in structured, intuitive design that makes buying and selling straightforward.</span>
        </li>
        <li class="flex items-start">
          <i class="fas fa-bolt text-yellow-500 mt-1 mr-2"></i>
          <span><strong>Efficiency:</strong> Our platform reduces time wasted scrolling and searching, giving back precious hours to students.</span>
        </li>
        <li class="flex items-start">
          <i class="fas fa-users text-green-500 mt-1 mr-2"></i>
          <span><strong>Community:</strong> Built by students, for students—fostering trust and connection in every transaction.</span>
        </li>
      </ul>
    </section>
  

  </main>


  <!-- Footer (optional) -->
  <footer class="bg-black text-gray-300 py-6">
    <div class="max-w-6xl mx-auto text-center text-sm">
      &copy; <?= date('Y') ?> Aura. All rights reserved.
    </div>
  </footer>
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
