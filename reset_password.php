<?php
session_start();
include "includes/db.php";

// Check if user came from verify_otp page
if (!isset($_SESSION['forgot_step']) || $_SESSION['forgot_step'] != 'reset_password') {
    header("Location: forgot_password.php");
    exit();
}

$msg = "";
$msg_type = "";

if (isset($_POST['reset_password'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (strlen($password) < 6) {
        $msg = "Password must be at least 6 characters long.";
        $msg_type = "danger";
    } elseif ($password !== $confirm_password) {
        $msg = "Passwords do not match.";
        $msg_type = "danger";
    } else {
        $user_id = $_SESSION['reset_user_id'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            // Clear all session data
            session_unset();
            session_destroy();

            // Start new session for success message
            session_start();
            $_SESSION['password_reset_success'] = true;

            header("Location: login.php?reset=success");
            exit();
        } else {
            $msg = "Failed to update password. Please try again.";
            $msg_type = "danger";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Blood Bank</title>
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

        .reset-card {
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

        .reset-card .icon-wrapper {
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

        .reset-card .icon-wrapper i {
            font-size: 35px;
            color: white;
        }

        .reset-card h2 {
            margin-bottom: 15px;
            color: #C41E3A;
            font-weight: 700;
            font-size: 28px;
        }

        .reset-card .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
            line-height: 1.6;
        }

        .icon-input {
            position: relative;
            margin-bottom: 20px;
        }

        .icon-input i.input-icon {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: #C41E3A;
            font-size: 18px;
        }

        .icon-input i.toggle-password {
            position: absolute;
            top: 50%;
            right: 18px;
            transform: translateY(-50%);
            color: #999;
            cursor: pointer;
            transition: 0.3s;
        }

        .icon-input i.toggle-password:hover {
            color: #C41E3A;
        }

        .reset-card input[type="password"],
        .reset-card input[type="text"] {
            width: 100%;
            padding: 15px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            outline: none;
            font-size: 15px;
            transition: all 0.3s;
        }

        .reset-card input:focus {
            border-color: #C41E3A;
            box-shadow: 0 0 15px rgba(196, 30, 58, 0.2);
        }

        .password-strength {
            margin-top: -10px;
            margin-bottom: 20px;
            text-align: left;
            padding: 0 20px;
        }

        .strength-meter {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .strength-meter-fill {
            height: 100%;
            width: 0;
            transition: all 0.3s;
            border-radius: 2px;
        }

        .strength-text {
            font-size: 12px;
            color: #666;
        }

        .password-requirements {
            text-align: left;
            margin-top: 15px;
            padding: 15px;
            background: #f8f8f8;
            border-radius: 10px;
            font-size: 13px;
        }

        .password-requirements h4 {
            font-size: 14px;
            color: #C41E3A;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .requirement {
            color: #999;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .requirement.met {
            color: #28a745;
        }

        .requirement i {
            margin-right: 8px;
        }

        .reset-card button {
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
            margin-top: 10px;
        }

        .reset-card button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(196, 30, 58, 0.4);
        }

        .reset-card button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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

        .success-animation {
            display: none;
            margin-top: 20px;
        }

        .checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            border-radius: 50%;
            background: #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }

            to {
                transform: scale(1);
            }
        }

        .checkmark i {
            color: white;
            font-size: 40px;
        }
    </style>
</head>

<body>

    <div class="reset-card">
        <div class="icon-wrapper">
            <i class="fas fa-key"></i>
        </div>

        <h2>Set New Password</h2>
        <p class="subtitle">
            Create a strong password for your account. Make sure it's at least 6 characters long.
        </p>

        <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msg_type; ?>">
                <i class="fas fa-<?php echo $msg_type == 'danger' ? 'exclamation-circle' : 'check-circle'; ?>"></i>
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" id="resetForm">
            <div class="icon-input">
                <i class="fas fa-lock input-icon"></i>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter new password"
                    required
                    minlength="6">
                <i class="fas fa-eye toggle-password" id="togglePassword"></i>
            </div>

            <div class="password-strength">
                <div class="strength-meter">
                    <div class="strength-meter-fill" id="strengthMeter"></div>
                </div>
                <div class="strength-text" id="strengthText">Password strength: <span>-</span></div>
            </div>

            <div class="icon-input">
                <i class="fas fa-lock input-icon"></i>
                <input
                    type="password"
                    name="confirm_password"
                    id="confirmPassword"
                    placeholder="Confirm new password"
                    required
                    minlength="6">
                <i class="fas fa-eye toggle-password" id="toggleConfirmPassword"></i>
            </div>

            <div class="password-requirements">
                <h4><i class="fas fa-shield-alt"></i> Password Requirements:</h4>
                <div class="requirement" id="req-length">
                    <i class="fas fa-circle"></i> At least 6 characters
                </div>
                <div class="requirement" id="req-match">
                    <i class="fas fa-circle"></i> Passwords match
                </div>
                <div class="requirement" id="req-number">
                    <i class="fas fa-circle"></i> Contains a number (recommended)
                </div>
                <div class="requirement" id="req-special">
                    <i class="fas fa-circle"></i> Contains special character (recommended)
                </div>
            </div>

            <button type="submit" name="reset_password" id="submitBtn">
                <i class="fas fa-check-circle me-2"></i>Reset Password
            </button>
        </form>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const strengthMeter = document.getElementById('strengthMeter');
        const strengthText = document.getElementById('strengthText');
        const submitBtn = document.getElementById('submitBtn');

        // Password visibility toggle
        document.getElementById('togglePassword').addEventListener('click', function() {
            const type = password.type === 'password' ? 'text' : 'password';
            password.type = type;
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const type = confirmPassword.type === 'password' ? 'text' : 'password';
            confirmPassword.type = type;
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Password strength checker
        password.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            let strengthLabel = '';
            let color = '';

            // Check requirements
            const hasLength = val.length >= 6;
            const hasNumber = /\d/.test(val);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(val);
            const hasUpper = /[A-Z]/.test(val);
            const hasLower = /[a-z]/.test(val);

            // Update requirement indicators
            document.getElementById('req-length').classList.toggle('met', hasLength);
            document.getElementById('req-number').classList.toggle('met', hasNumber);
            document.getElementById('req-special').classList.toggle('met', hasSpecial);

            // Calculate strength
            if (hasLength) strength += 20;
            if (hasNumber) strength += 20;
            if (hasSpecial) strength += 20;
            if (hasUpper) strength += 20;
            if (hasLower) strength += 20;

            // Set strength label and color
            if (strength === 0) {
                strengthLabel = '-';
                color = '#e0e0e0';
            } else if (strength <= 40) {
                strengthLabel = 'Weak';
                color = '#ff4d4d';
            } else if (strength <= 60) {
                strengthLabel = 'Fair';
                color = '#ffc107';
            } else if (strength <= 80) {
                strengthLabel = 'Good';
                color = '#28a745';
            } else {
                strengthLabel = 'Strong';
                color = '#0d7377';
            }

            strengthMeter.style.width = strength + '%';
            strengthMeter.style.background = color;
            strengthText.querySelector('span').textContent = strengthLabel;
            strengthText.querySelector('span').style.color = color;
        });

        // Confirm password matching
        confirmPassword.addEventListener('input', function() {
            const match = this.value === password.value && this.value !== '';
            document.getElementById('req-match').classList.toggle('met', match);
        });

        // Form validation
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }

            if (password.value.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return;
            }
        });
    </script>
</body>

</html>