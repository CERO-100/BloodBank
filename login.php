<?php
session_start();
include "includes/db.php";


if (isset($_POST['login'])) {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email) {
        $error = "Please enter a valid email address!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Check if user is approved
            if ($user['status'] !== 'approved') {
                $error = "Your account is {$user['status']}. Please wait for admin approval.";
            } else {
                // Regenerate session ID for security
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['last_activity'] = time();

                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header("Location: admin/admin.php"); // FIXED: admin.php not dashboard.php
                } elseif ($user['role'] == 'hospital') {
                    header("Location: hospital/dashboard.php");
                } else {
                    header("Location: user/dashboard.php");
                }
                exit();
            }
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Blood Bank</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #FF4D4D, #C41E3A);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: #fff;
            padding: 40px 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-card h2 {
            margin-bottom: 25px;
            color: #C41E3A;
            font-weight: 700;
        }

        .login-card input[type="email"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0 20px 0;
            border: 1px solid #ddd;
            border-radius: 50px;
            outline: none;
            transition: 0.3s;
        }

        .login-card input:focus {
            border-color: #C41E3A;
            box-shadow: 0 0 8px rgba(196, 30, 58, 0.3);
        }

        .login-card button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(45deg, #C41E3A, #FF4D4D);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-card button:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(196, 30, 58, 0.4);
        }

        .login-card .register-link,
        .login-card .forgot-link {
            display: block;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #C41E3A;
            text-decoration: none;
        }

        .login-card .register-link:hover,
        .login-card .forgot-link:hover {
            text-decoration: underline;
        }

        .login-card .forgot-link {
            margin-top: 12px;
            font-size: 0.85rem;
        }

        .error-msg {
            color: #ff0000;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .success-msg {
            color: #28a745;
            margin-bottom: 15px;
            font-weight: 600;
            background: #d4edda;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <h2>Login</h2>

        <?php if (isset($_GET['reset']) && $_GET['reset'] == 'success'): ?>
            <div class="success-msg">
                <i class="fas fa-check-circle"></i> Password reset successful! Please login with your new password.
            </div>
        <?php endif; ?>

        <?php if (isset($error)) { ?>
            <div class="error-msg"><?= $error; ?></div>
        <?php } ?>

        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
        <a href="register.php" class="register-link">Don't have an account? Register</a>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>