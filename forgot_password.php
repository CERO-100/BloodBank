<?php
session_start();
include "includes/db.php";
include "includes/mailer.php";

$msg = "";
$msg_type = "";

if (isset($_POST['send_otp'])) {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT user_id, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Store OTP in session
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_user_id'] = $user['user_id'];
        $_SESSION['otp_expiry'] = $expiry;

        // Send OTP via email
        $subject = "Password Reset OTP - Blood Bank System";
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;'>
            <div style='background: linear-gradient(135deg, #C41E3A, #FF4D4D); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
                <h1 style='color: white; margin: 0;'>🩸 Blood Bank System</h1>
            </div>
            <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px;'>
                <h2 style='color: #C41E3A;'>Password Reset Request</h2>
                <p>Hello <strong>{$user['name']}</strong>,</p>
                <p>You have requested to reset your password. Please use the following OTP code:</p>
                <div style='background: #f0f0f0; padding: 20px; text-align: center; border-radius: 10px; margin: 20px 0;'>
                    <h1 style='color: #C41E3A; font-size: 36px; margin: 0; letter-spacing: 5px;'>{$otp}</h1>
                </div>
                <p style='color: #666;'><strong>⏰ This OTP will expire in 15 minutes.</strong></p>
                <p>If you didn't request this, please ignore this email and ensure your account is secure.</p>
                <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
                <p style='color: #999; font-size: 12px; text-align: center;'>
                    This is an automated email from Blood Bank System. Please do not reply to this email.
                </p>
            </div>
        </div>
        ";

        if (send_mail($email, $subject, $body)) {
            $_SESSION['forgot_step'] = 'verify_otp';
            header("Location: verify_otp.php");
            exit();
        } else {
            $msg = "Failed to send OTP. Please try again.";
            $msg_type = "danger";
        }
    } else {
        $msg = "Email not found in our system.";
        $msg_type = "danger";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Blood Bank</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #FF4D4D, #C41E3A);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .forgot-card {
            background: #fff;
            padding: 40px 35px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 450px;
            text-align: center;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .forgot-card .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #C41E3A, #FF4D4D);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(196, 30, 58, 0.3);
        }

        .forgot-card .icon-wrapper i {
            font-size: 35px;
            color: white;
        }

        .forgot-card h2 {
            margin-bottom: 15px;
            color: #C41E3A;
            font-weight: 700;
            font-size: 28px;
        }

        .forgot-card .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
            line-height: 1.6;
        }

        .icon-input {
            position: relative;
            margin-bottom: 20px;
        }

        .icon-input i {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: #C41E3A;
            font-size: 18px;
        }

        .forgot-card input[type="email"] {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            outline: none;
            font-size: 15px;
            transition: all 0.3s;
        }

        .forgot-card input[type="email"]:focus {
            border-color: #C41E3A;
            box-shadow: 0 0 15px rgba(196, 30, 58, 0.2);
        }

        .forgot-card button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(45deg, #C41E3A, #FF4D4D);
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .forgot-card button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(196, 30, 58, 0.4);
        }

        .forgot-card button:active {
            transform: translateY(0);
        }

        .forgot-card .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #C41E3A;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .forgot-card .back-link:hover {
            text-decoration: underline;
            color: #FF4D4D;
        }

        .alert {
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .alert-danger {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .features {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .features .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            color: #666;
            font-size: 13px;
            text-align: left;
        }

        .features .feature-item i {
            color: #C41E3A;
            margin-right: 10px;
            font-size: 14px;
        }

        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        button.loading .spinner {
            display: inline-block;
        }

        button.loading .btn-text {
            display: none;
        }
    </style>
</head>

<body>

    <div class="forgot-card">
        <div class="icon-wrapper">
            <i class="fas fa-lock"></i>
        </div>

        <h2>Forgot Password?</h2>
        <p class="subtitle">
            Don't worry! Enter your registered email address and we'll send you a verification code to reset your password.
        </p>

        <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msg_type; ?>">
                <i class="fas fa-<?php echo $msg_type == 'danger' ? 'exclamation-circle' : 'check-circle'; ?>"></i>
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" id="forgotForm">
            <div class="icon-input">
                <i class="fas fa-envelope"></i>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter your email address"
                    required
                    autocomplete="email">
            </div>

            <button type="submit" name="send_otp" id="submitBtn">
                <span class="btn-text">
                    <i class="fas fa-paper-plane me-2"></i>Send Verification Code
                </span>
                <div class="spinner"></div>
            </button>
        </form>

        <a href="login.php" class="back-link">
            <i class="fas fa-arrow-left me-1"></i> Back to Login
        </a>

        <div class="features">
            <div class="feature-item">
                <i class="fas fa-shield-alt"></i>
                <span>Secure OTP verification</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-clock"></i>
                <span>OTP expires in 15 minutes</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-user-shield"></i>
                <span>Your data is protected</span>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add loading state to form submission
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });

        // Email validation
        document.getElementById('email').addEventListener('input', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                e.target.style.borderColor = '#ff4d4d';
            } else {
                e.target.style.borderColor = '#e0e0e0';
            }
        });
    </script>
</body>

</html>