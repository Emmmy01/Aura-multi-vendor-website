<?php
// shop.php
session_start();
include 'db.php';

// 1. Handle search & category filters
$search   = trim($_GET['search']   ?? '');
$category = trim($_GET['category'] ?? '');

// 2. Fetch distinct categories for the quick‑links
$catStmt = $conn->query("SELECT DISTINCT category FROM products");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

// 3. Fetch featured (newest 6 products)
$featStmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 6");
$featured = $featStmt->fetchAll();

// 4. Build main products query
$query  = "SELECT * FROM products WHERE 1=1";
$params = [];
if ($search) {
  $query .= " AND name LIKE ?";
  $params[] = "%$search%";
}
if ($category) {
  $query .= " AND category = ?";
  $params[] = $category;
}
$query .= " ORDER BY id DESC";
$prodStmt = $conn->prepare($query);
$prodStmt->execute($params);
$products = $prodStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Aura Shop</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- Search & Filters -->
  <section class="py-4">
    <div class="max-w-6xl mx-auto flex flex-wrap gap-4 px-4">
    <form method="GET" action="shop.php" class="flex-1 min-w-[200px] flex relative">
  <input type="text" id="searchInput" name="search" value="<?= htmlspecialchars($search) ?>"
         placeholder="Search products…"
         autocomplete="off"
         class="flex-grow p-2 border rounded-l-md focus:outline-none">

  <button type="submit" class="bg-black hover:bg-black text-white px-4 rounded-r-md">Search</button>

  <!-- Suggestions box -->
  <ul id="suggestionBox"
      class="absolute top-full left-0 right-0 bg-white shadow-md z-50 mt-1 border rounded-md hidden max-h-64 overflow-auto">
  </ul>
</form>

      <select onchange="location = this.value" class="p-2 border rounded-md text-sm">
        <option value="shop.php?<?= $search?'search='.urlencode($search):'' ?>">All Categories</option>
        <?php foreach ($categories as $cat): 
          $url = 'shop.php?category='.urlencode($cat).($search?'&search='.urlencode($search):'');
        ?>
        <option value="<?= $url ?>" <?= $category===$cat?'selected':'' ?>>
          <?= htmlspecialchars($cat) ?>
        </option>
        <?php endforeach; ?>
      </select>
      <select onchange="location = this.value" class="p-2 border rounded-md text-sm">
        <option value="shop.php?<?= http_build_query(['search'=>$search,'category'=>$category,'sort'=>'new']) ?>">
          Sort by: Newest
        </option>
        <option value="shop.php?<?= http_build_query(['search'=>$search,'category'=>$category,'sort'=>'price_asc']) ?>">
          Price ↑
        </option>
        <option value="shop.php?<?= http_build_query(['search'=>$search,'category'=>$category,'sort'=>'price_desc']) ?>">
          Price ↓
        </option>
      </select>
    </div>
  </section>

  <!-- Product Grid -->
  <section class="py-6">
  <div class="max-w-6xl mx-auto px-4">
    <?php if (empty($products)): ?>
      <p class="text-center text-gray-500">No products found.</p>
    <?php else: ?>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($products as $p): ?>
          <div class="relative bg-white rounded-xl shadow hover:shadow-lg overflow-hidden transition p-3 flex flex-col">
            <a href="product_detail.php?id=<?= $p['id'] ?>" class="flex-grow">
              <img src="<?= htmlspecialchars($p['image']) ?>" class="w-full h-40 object-contain mx-auto">
              <h3 class="text-sm font-semibold text-gray-800 truncate mt-2"
                  title="<?= htmlspecialchars($p['name']) ?>">
                <?= htmlspecialchars($p['name']) ?>
              </h3>
              <p class="text-black font-bold mt-1">
                ₦<?= number_format($p['price']) ?>
              </p>
            </a>
            <label class="mt-2 text-xs">
              <input type="checkbox" class="compare-checkbox" value="<?= $p['id'] ?>"> Compare
            </label>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<div id="compareBar" class="fixed bottom-4 right-4 bg-black text-white px-4 py-2 rounded-lg hidden z-50 shadow">
  <button id="compareNowBtn" class="bg-red-500 px-3 py-1 rounded">Compare Now</button>
</div>


  <!-- Category Quick‑Links -->
  <section class="py-6">
    <div class="max-w-6xl mx-auto grid grid-cols-2 sm:grid-cols-4 gap-4 px-4">
      <?php foreach ($categories as $cat): ?>
      <a href="shop.php?category=<?= urlencode($cat) ?>"
         class="flex flex-col items-center bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
        <i class="fas fa-tags text-2xl text-red-500 mb-2"></i>
        <span class="font-medium capitalize"><?= htmlspecialchars($cat) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- New Arrivals Carousel -->
  <section class="py-6 bg-white">
    <div class="max-w-6xl mx-auto px-4">
      <h2 class="text-xl font-semibold mb-4">New Arrivals</h2>
      <div class="overflow-x-auto flex gap-4 pb-2">
        <?php foreach ($featured as $p): ?>
        <a href="product_detail.php?id=<?= $p['id'] ?>"
           class="min-w-[160px] bg-gray-100 rounded-lg p-3 hover:shadow-md transition">
          <img src="<?= htmlspecialchars($p['image']) ?>"
               class="h-32 w-full object-cover rounded-md mb-2">
          <p class="truncate text-sm font-medium"><?= htmlspecialchars($p['name']) ?></p>
          <p class="text-black font-bold">₦<?= number_format($p['price']) ?></p>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll(".compare-checkbox");
    const compareBar = document.getElementById("compareBar");
    const compareNowBtn = document.getElementById("compareNowBtn");
    let selectedIds = [];

    checkboxes.forEach(box => {
      box.addEventListener("change", function () {
        const id = this.value;

        if (this.checked) {
          if (selectedIds.length >= 4) {
            alert("You can only compare up to 4 items.");
            this.checked = false;
            return;
          }
          selectedIds.push(id);
        } else {
          selectedIds = selectedIds.filter(pid => pid !== id);
        }

        // Show/hide compare bar
        compareBar.style.display = selectedIds.length >= 2 ? "block" : "none";
      });
    });

    compareNowBtn.addEventListener("click", function () {
      if (selectedIds.length >= 2) {
        const url = "compare.php?ids=" + selectedIds.join(",");
        window.location.href = url;
      } else {
        alert("Select at least 2 products to compare.");
      }
    });
  });
</script>
<script>
const input = document.getElementById('searchInput');
const box = document.getElementById('suggestionBox');

input.addEventListener('keyup', () => {
  const query = input.value.trim();
  if (query.length === 0) {
    box.classList.add('hidden');
    return;
  }

  fetch(`search-suggest.php?q=${encodeURIComponent(query)}`)
    .then(res => res.json())
    .then(data => {
      box.innerHTML = '';
      if (data.length === 0) {
        box.classList.add('hidden');
        return;
      }

      data.forEach(item => {
        const li = document.createElement('li');
        li.className = 'flex items-center gap-2 p-2 hover:bg-gray-100 cursor-pointer text-sm';

        li.innerHTML = `
          <img src="${item.image}" class="w-8 h-8 object-contain rounded" />
          <span class="truncate">${item.name}</span>
        `;

        li.onclick = () => {
          window.location.href = `product_detail.php?id=${item.id}`;
        };

        box.appendChild(li);
      });

      box.classList.remove('hidden');
    });
});

document.addEventListener('click', (e) => {
  if (!input.contains(e.target) && !box.contains(e.target)) {
    box.classList.add('hidden');
  }
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

</body>
</html>
