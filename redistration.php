<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic reset */
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
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 92%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .password-error {
            color: red;
            font-size: 12px;
            display: none;
        }

        .agree-container {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .agree-container input {
            margin-right: 5px;
        }

        .register-btn {
            width: 100%;
            padding: 10px;
            background-color:rgb(11, 11, 11);
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .register-btn:hover {
            background-color:rgb(16, 15, 15);
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #ef4444;
        }

        /* Alert message */
        .alert {
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.show {
            display: block;
        }
.agree-container a{
    color:  gray;
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
a{
    text-decoration:none;
}
    </style>
</head>
<body>


<div class="container">
    <div class="form-section">
    <?php if (isset($_SESSION['status'])): ?>
    <div class="alert show" id="alert-message">
        <?php
        echo $_SESSION['status'];
        unset($_SESSION['status']); // Clear the session message
        ?>
    </div>
    <script>
        setTimeout(function() {
            var alertMessage = document.getElementById("alert-message");
            if (alertMessage) {
                alertMessage.style.display = "none";
            }
        }, 10000);
    </script>
<?php endif; ?>

        <h2>Register</h2>
        <form action="register_code.php" method="POST" onsubmit="return validatePassword()">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <p id="password-error" class="password-error">⚠️ Passwords do not match!</p>
            <div class="agree-container">
                <input type="checkbox" id="agree" required>
                <label for="agree">I agree to the <a href="#">terms and conditions</a></label>
            </div>
            <button type="submit" name="register-btn" class="register-btn">Register</button>
            <p class="login-link">Already have an account? <a href="login2.php">Login</a></p>
        </form>
    </div>
</div>

<script>
    function validatePassword() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
        const errorText = document.getElementById("password-error");

        if (password !== confirmPassword) {
            errorText.style.display = "block";
            return false; // Prevent form submission
        } else {
            errorText.style.display = "none";
            return true;
        }
    }
</script>

</body>
</html>
