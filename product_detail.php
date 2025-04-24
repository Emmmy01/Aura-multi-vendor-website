<?php
include 'db.php';
session_start();

// Sanitize and validate product ID
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Invalid product ID");
}

$id = (int)$_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found");
}

// Increment views
$stmt = $conn->prepare("UPDATE products SET views = views + 1 WHERE id = ?");
$stmt->execute([$id]);

// Images
$images = [];
if (!empty($product['image'])) $images[] = $product['image'];
if (!empty($product['image_2'])) $images[] = $product['image_2'];
if (!empty($product['image_3'])) $images[] = $product['image_3'];

$colors = explode(',', $product['color']);
$sizes = explode(',', $product['sizes']);

// Recommended products from the same seller
$sellerName = $product['seller_name'];
$recommendStmt = $conn->prepare("SELECT * FROM products WHERE seller_name = ? AND id != ? LIMIT 6");
$recommendStmt->execute([$sellerName, $id]);
$recommended = $recommendStmt->fetchAll();

$product_id = $id;
$user_id = $_SESSION['user_id'] ?? 0;

// Check wishlist status
$is_favorite = false;
if ($user_id) {
    $check = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
    $check->execute([$user_id, $product_id]);
    $is_favorite = $check->rowCount() > 0;
}


$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'] ?? 0;

// Save view to history
if ($user_id) {
    $sql = "INSERT INTO view_history (user_id, product_id) VALUES (:user_id, :product_id)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':product_id' => $product_id
    ]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($product['name']) ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .active-thumb {
      border: 2px solid #000;
    }
    .wishlist-icon {
      font-size: 24px;
      color:gray;
      text-align:right;
      cursor: pointer;
      transition: transform 0.2s ease, color 0.2s ease;
    }
    .wishlist-icon.active {
      color: red;
      
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans">
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

<div class="max-w-6xl mx-auto bg-white p-8 mt-0.5 rounded-xl shadow-lg flex flex-col md:flex-row gap-10">

  <!-- Wishlist Icon -->
  <span id="wishlist-icon"
        class="wishlist-icon <?= $is_favorite ? 'fas active' : 'far' ?> fa-heart"
        data-product-id="<?= $product_id ?>">
  </span>

  <!-- Images -->
  <div class="w-full md:w-1/2">
    <div class="w-full h-[420px] rounded-xl overflow-hidden bg-gray-50 flex items-center justify-center">
      <img id="mainImage" src="<?= $images[0] ?>" class="max-h-full object-contain transition-all duration-300" alt="Product Image">
    </div>
    <div class="flex gap-4 mt-4 overflow-x-auto">
      <?php foreach ($images as $index => $img): ?>
        <img src="<?= $img ?>" class="w-20 h-20 object-cover rounded cursor-pointer thumb <?= $index === 0 ? 'active-thumb' : '' ?>" onclick="switchImage(this)">
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Product Details -->
  <div class="w-full md:w-1/2 flex flex-col justify-between">
    <div class="space-y-3">
      <h2 class="text-xl text-gray-600"><?= htmlspecialchars($product['brand']) ?></h2>
      <h1 class="text-3xl font-semibold text-gray-900"><?= htmlspecialchars($product['name']) ?></h1>
      <p class="text-xs text-gray-600 mt-1 line-clamp-2 transition-all duration-300 ease-in-out" id="desc-<?= $product['id'] ?>">
  <?= htmlspecialchars($product['short_description']) ?>
</p>
<button onclick="toggleDescription(<?= $product['id'] ?>, this)" class="text-red-600 font-medium text-xs mt-1 focus:outline-none">See more</button>

      <div class="text-yellow-500 text-sm">★★★★☆</div>
      <div class="text-2xl font-bold text-gray-900 mt-4">₦<?= number_format($product['price'], 2) ?></div>

      <!-- Add to Cart Form -->
      <form method="POST" action="add_to_cart.php" class="mt-6 space-y-4">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
        <input type="hidden" name="price" value="<?= $product['price'] ?>">
        <input type="hidden" name="image" value="<?= $product['image'] ?>">

        <div class="space-y-2 text-sm text-gray-700">
          <p><strong>Quality:</strong> <?= htmlspecialchars($product['quality']) ?></p>
          <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand']) ?></p>
        </div>

        <!-- Color -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Choose Color</label>
          <select name="color"  class="w-full border px-3 py-2 rounded">
            <?php foreach($colors as $color): ?>
              <option value="<?= trim($color) ?>"><?= ucfirst(trim($color)) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <!-- Size -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Choose Size</label>
          <select name="size"  class="w-full border px-3 py-2 rounded">
            <?php foreach($sizes as $size): ?>
              <option value="<?= trim($size) ?>"><?= strtoupper(trim($size)) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Quantity -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
          <input type="number" name="quantity" value="1" min="1" class="w-full border px-3 py-2 rounded" required>
        </div>
        <?php if (!empty($product['seller_whatsapp'])): 
  $whatsappNumber = preg_replace('/[^0-9]/', '', $product['seller_whatsapp']);
?>
  <p class="text-sm mt-4">
    <strong>Chat with seller:</strong>
    <a href="https://wa.me/<?php echo $whatsappNumber; ?>" target="_blank" class="text-green-600 underline">
      <?php echo htmlspecialchars($product['seller_whatsapp']); ?>
    </a>
  </p>
<?php endif; ?>


        <button type="submit" class="w-full bg-black text-white py-3 rounded hover:opacity-90 transition">
          Add to Cart
        </button>
        <p class="text-sm text-gray-500 text-center">You go enjoy this one</p>
      </form>
    </div>
  </div>
</div>

<!-- More From This Seller -->
<?php if ($recommended): ?>
  <div class="max-w-6xl mx-auto mt-10 px-4">
    <h2 class="text-2xl font-semibold mb-4">More From This Seller</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php foreach ($recommended as $item): ?>
        <a href="product_detail.php?id=<?= $item['id'] ?>" class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
          <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-40 object-contain rounded mb-2">
          <h3 class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($item['name']) ?></h3>
          <p class="text-black-500 font-bold mt-1">₦<?= number_format($item['price'], 2) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
    <br><br>
  </div>
<?php endif; ?>


<!-- JavaScript -->
<script>
function switchImage(element) {
  const mainImage = document.getElementById('mainImage');
  mainImage.src = element.src;
  document.querySelectorAll('.thumb').forEach(img => img.classList.remove('active-thumb'));
  element.classList.add('active-thumb');
}

document.addEventListener('DOMContentLoaded', function () {
  const icon = document.getElementById('wishlist-icon');

  icon.addEventListener('click', function () {
    const productId = icon.getAttribute('data-product-id');

    // Toggle UI immediately for instant feedback
    const isActive = icon.classList.contains('active');

    if (isActive) {
      icon.classList.remove('fas', 'active');
      icon.classList.add('far');
    } else {
      icon.classList.remove('far');
      icon.classList.add('fas', 'active');
    }

    // Send request to server in the background
    fetch('wishlist_handler.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `product_id=${productId}`
    })
    .then(res => res.json())
    .then(data => {
      // Optional: confirm response or revert changes on error
      // But since UI already updated, you might not need to do anything here
    })
    .catch(err => {
      console.error('Wishlist error:', err);
      // Optional: Revert the toggle if something goes wrong
    });
  });
});

</script>
<!-- Product Reviews -->
<div class="max-w-6xl mx-auto mt-10 px-4">
  <h2 class="text-2xl font-semibold mb-4">Product Reviews</h2>

  <!-- Display reviews -->
  <?php
  $reviewQuery = $conn->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
  $reviewQuery->execute([$product_id]);
  $reviews = $reviewQuery->fetchAll();
  ?>

  <?php if ($reviews): ?>
    <div class="space-y-4">
      <?php foreach ($reviews as $review): ?>
        <div class="bg-white p-4 rounded shadow">
          <div class="flex justify-between items-center">
            <h4 class="font-semibold"><?= htmlspecialchars($review['username']) ?></h4>
            <span class="text-sm text-gray-400"><?= date('M d, Y', strtotime($review['created_at'])) ?></span>
          </div>
          <div class="text-yellow-500 text-sm mb-1">
            <?= str_repeat('★', (int)$review['rating']) ?><?= str_repeat('☆', 5 - (int)$review['rating']) ?>
          </div>
          <p class="text-gray-700 text-sm"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-gray-600">No reviews yet. Be the first to review this product.</p>
  <?php endif; ?>
</div>

<!-- Leave a Review -->
<?php if ($user_id): ?>
  <div class="max-w-6xl mx-auto mt-6 px-4">
    <h3 class="text-xl font-semibold mb-2">Leave a Review</h3>
    <form action="submit_review.php" method="POST" class="bg-white p-4 rounded shadow space-y-4">
      <input type="hidden" name="product_id" value="<?= $product_id ?>">

      <label class="block">
        <span class="text-gray-700">Rating</span>
        <select name="rating" required class="w-full mt-1 px-3 py-2 border rounded">
          <option value="">Select</option>
          <?php for ($i = 5; $i >= 1; $i--): ?>
            <option value="<?= $i ?>"><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
          <?php endfor; ?>
        </select>
      </label>

      <label class="block">
        <span class="text-gray-700">Comment</span>
        <textarea name="comment" required class="w-full mt-1 px-3 py-2 border rounded" rows="4"></textarea>
      </label>

      <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Submit Review</button>
    </form>
  </div>
<?php else: ?>
  <div class="max-w-6xl mx-auto mt-6 px-4 text-center text-gray-600">
    <p>You must <a href="login2.php" class="text-red-600 underline">login</a> to leave a review.</p>
  </div>
<?php endif; ?>


<br>
<br>
<script>
  function toggleDescription(id, btn) {
    const desc = document.getElementById('desc-' + id);
    if (desc.classList.contains('line-clamp-2')) {
      desc.classList.remove('line-clamp-2');
      btn.innerText = 'See less';
    } else {
      desc.classList.add('line-clamp-2');
      btn.innerText = 'See more';
    }
  }
</script>


</body>
</html>
