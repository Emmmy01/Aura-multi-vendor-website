<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $phone = $_POST['phone'];

    $update = $conn->prepare("UPDATE users SET address = ?, city = ?, state = ?, postal_code = ?, phone = ? WHERE id = ?");
    $update->execute([$address, $city, $state, $postal_code, $phone, $user_id]);

    header("Location: delivery_info.php");
    exit;
}

// Fetch user data
$stmt = $conn->prepare("SELECT address, city, state, postal_code, phone FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Delivery Address</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }

        .box {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Edit Delivery Address</h2>
    <form method="post">
        <label for="phone">Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

        <label for="address">Address</label>
        <textarea name="address" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>

        <label for="city">City</label>
        <input type="text" name="city" value="<?= htmlspecialchars($user['city']) ?>" required>

        <label for="state">State</label>
        <input type="text" name="state" value="<?= htmlspecialchars($user['state']) ?>" required>

        <label for="postal_code">Postal Code</label>
        <input type="text" name="postal_code" value="<?= htmlspecialchars($user['postal_code']) ?>" required>

        <button type="submit">Save Address</button>
    </form>
</div>

</body>
</html>
