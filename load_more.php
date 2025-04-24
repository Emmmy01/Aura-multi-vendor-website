<?php
include 'db.php';

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

$stmt = $conn->prepare("
    SELECT * 
    FROM products
    WHERE discount_percentage IN (0,10,20,30,40,50)
    ORDER BY created_at DESC
    LIMIT 10
    OFFSET ?
");
$stmt->bindParam(1, $offset, PDO::PARAM_INT);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  echo '
  <div class="bg-white p-3 shadow-md rounded-lg h-[240px] w-full flex flex-col justify-between text-center">
    <a href="product_detail.php?id=' . $row['id'] . '">
      <img src="' . $row['image'] . '" alt="' . $row['name'] . '" class="w-full h-40 object-contain mx-auto mb--8" />
      <h2 class="text-sm truncate" title="' . $row['name'] . '">' . $row['name'] . '</h2>
      <p class="text-black font-bold text-sm mt-2 text-left">
        ₦' . number_format($row['price'], 0, '.', ',') . '
      </p>
    </a>
  </div>
';
}
