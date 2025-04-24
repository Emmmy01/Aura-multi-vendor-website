<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("You must be logged in to view your requests.");
}

// Fetch user's requests
$sql = "SELECT * FROM requests WHERE user_id = :user_id ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

    <title>My Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: sans-serif;
            background: #f8f8f8;
            padding: 20px;
            white-space: wrap;
        }
        .request-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .request-card img {
            max-width: 120px;
            border-radius: 5px;
        }
        .request-info {
            margin-left: 20px;
            white-space: wrap;
        }
        .flex {
            display: block;
            align-items: center;
        }
        .btn-cancel {
            background: black;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>📋 My Request Listings</h2>

<?php if (count($requests) === 0): ?>
    <p>You haven't made any requests yet.</p>
<?php else: ?>
    <?php foreach ($requests as $req): ?>
        <div class="request-card">
            <div class="flex">
                <?php if ($req['image']): ?>
                    <img src="<?= htmlspecialchars($req['image']) ?>" alt="Requested Image">
                <?php endif; ?>
                <div class="request-info">
                    <p><strong>Item:</strong> <?= htmlspecialchars($req['request']) ?></p>
                    <p><strong>Brand:</strong> <?= htmlspecialchars($req['brand']) ?></p>
                    <p><strong>Color:</strong> <?= htmlspecialchars($req['color']) ?></p>
                    <p><strong>Size:</strong> <?= htmlspecialchars($req['size']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($req['email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($req['phone']) ?></p>

                    <!-- Cancel Button -->
                    <form method="post" action="delete-request.php">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <button type="submit" class="btn-cancel">Cancel Request</button>
    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
