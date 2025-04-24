<?php
include 'db.php';
session_start();

if (isset($_POST['login-btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // ✅ Store user ID in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name']; // optional

        // ✅ Redirect to home or dashboard
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['status'] = "❌ Invalid email or password.";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Aura</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: whitesmoke;
            color: black;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(190, 189, 189, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: rgb(13, 13, 13);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            width: 92%;
            padding: 10px;
            background: #fdfdfd;
            border: 1px solid #ccc;
            border-radius: 6px;
            color: #000;
            transition: 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: rgb(13, 13, 13);
            outline: none;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: rgb(9, 9, 9);
            border: none;
            color: white;
            font-weight: bold;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .login-btn:hover {
            background-color: rgb(6, 6, 6);
        }

        .register-link {
            text-align: center;
            margin-top: 18px;
            font-size: 15px;
        }

        .register-link a {
            color: rgb(206, 47, 29);
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 20px;
            background-color:#28a745;
        
            color: white;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if (isset($_SESSION['status'])): ?>
        <div class="alert" id="alert-message">
            <?= $_SESSION['status']; ?>
            <?php unset($_SESSION['status']); ?>
        </div>
        <script>
            setTimeout(() => {
                let alertMsg = document.getElementById("alert-message");
                if (alertMsg) alertMsg.style.display = "none";
            }, 8000);
        </script>
    <?php endif; ?>

    <h2>Login</h2>
    <form action="login_code.php" method="POST">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required />
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required />
        </div>

        <button type="submit" name="login-btn" class="login-btn">Login</button>

        <p class="register-link">Don't have an account? <a href="redistration.php">Register</a></p>
    </form>
</div>

</body>
</html>
