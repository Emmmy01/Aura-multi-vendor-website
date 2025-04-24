<?php
require 'db.php';

$sql = "SELECT * FROM requests ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buyer Requests</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; background: #f9f9f9; }
    h2 { color: #222; }
    .request-card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    img {
      max-width: 120px;
      height: auto;
      display: block;
      margin-top: 10px;
    }
    .btn-cancel {
      background: black;
      color: white;
      padding: 6px 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }
    a{
        color:gray; 
    }
  </style>
</head>
<body>

<h2>📋 Buyer Requests - Do You Have It?</h2>

<?php foreach ($requests as $row): ?>
  <div class="request-card">
    <p><strong>Item:</strong> <?= htmlspecialchars($row['request']) ?></p>
    <p><strong>Brand:</strong> <?= htmlspecialchars($row['brand']) ?></p>
    <p><strong>Color:</strong> <?= htmlspecialchars($row['color']) ?></p>
    <p><strong>Size:</strong> <?= htmlspecialchars($row['size']) ?></p>
    <?php if (!empty($row['image'])): ?>
      <img src="<?= htmlspecialchars($row['image']) ?>" alt="Requested item">
    <?php endif; ?>
    <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
    <p><strong>Phone:</strong> <a href="https://wa.me/<?= htmlspecialchars($row['phone']) ?>" target="_blank"><?= htmlspecialchars($row['phone']) ?></a></p>
  
  </div>
<?php endforeach; ?>

</body>
</html>
