<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-black to-black min-h-screen flex items-center justify-center">

  <div class="w-full  bg-white  shadow-lg overflow-hidden">
    
    <!-- Illustration -->
    <div class="bg-black relative p-5 flex items-center justify-center">
      <img src="brand communication-pana.png" alt="bike boy" class="h-44 object-contain" />
    </div>

    <!-- Form Content -->
    <form action="send_message.php" method="POST" class="p-6 space-y-4">
      <h2 class="text-xl font-semibold text-center text-gray-800">GET IN TOUCH!</h2>

      <div>
        <label class="text-sm text-gray-500">Name</label>
        <input type="text" name="name" required class="w-full border border-gray-300 px-4 py-2 mt-1 rounded-md focus:outline-none focus:ring-2 focus:ring-black" />
      </div>

      <div>
        <label class="text-sm text-gray-500">E-mail</label>
        <input type="email" name="email" required class="w-full border border-gray-300 px-4 py-2 mt-1 rounded-md focus:outline-none focus:ring-2 focus:ring-black" />
      </div>

      <div>
        <label class="text-sm text-gray-500">Message</label>
        <textarea name="message" rows="4" required class="w-full border border-gray-300 px-4 py-2 mt-1 rounded-md focus:outline-none focus:ring-2 focus:ring-black"></textarea>
      </div>

      <!-- Send Button -->
      <div class="flex justify-center">
        <button type="submit" class="bg-black text-white p-4 rounded-full hover:bg-gray-500 transition">
          <i class="fas fa-paper-plane"></i>
        </button>
      </div>
    </form>

    <!-- Socials -->
    <div class="flex justify-center items-center gap-6 p-4 text-red-500">
    <a href="https://www.instagram.com/yourprofile" target="_blank">
      <i class="fab fa-instagram  text-xl"></i>
    </a>
    <a href="https://www.facebook.com/yourpage" target="_blank">
      <i class="fab fa-facebook text-blue-600 text-xl"></i>
    </a>
    <a href="https://chat.whatsapp.com/GGaOLkzHpXnBIUaqAl3FiZ " target="_blank">
      <i class="fab fa-whatsapp text-green-500 text-xl"></i>
    </a>
    </div>
  </div>

</body>
</html>
