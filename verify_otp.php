<?php
session_start();
include "includes/db.php";

// Check if user came from forgot_password page
if (!isset($_SESSION['forgot_step']) || $_SESSION['forgot_step'] != 'verify_otp') {
    header("Location: forgot_password.php");
    exit();
}

$msg = "";
$msg_type = "";
$attempts = isset($_SESSION['otp_attempts']) ? $_SESSION['otp_attempts'] : 0;

if (isset($_POST['verify_otp'])) {
    $entered_otp = trim($_POST['otp']);
    $stored_otp = $_SESSION['reset_otp'];
    $expiry = $_SESSION['otp_expiry'];

    // Check if OTP has expired
    if (strtotime($expiry) < time()) {
        $msg = "OTP has expired. Please request a new one.";
        $msg_type = "danger";
        unset($_SESSION['reset_otp']);
        unset($_SESSION['otp_expiry']);
    }
    // Check attempts
    elseif ($attempts >= 3) {
        $msg = "Too many failed attempts. Please request a new OTP.";
        $msg_type = "danger";
        session_destroy();
    }
    // Verify OTP
    elseif ($entered_otp == $stored_otp) {
        $_SESSION['forgot_step'] = 'reset_password';
        unset($_SESSION['otp_attempts']);
        header("Location: reset_password.php");
        exit();
    } else {
        $attempts++;
        $_SESSION['otp_attempts'] = $attempts;
        $remaining = 3 - $attempts;
        $msg = "Invalid OTP. You have $remaining attempt(s) remaining.";
        $msg_type = "danger";
    }
}

// Resend OTP
if (isset($_POST['resend_otp'])) {
    include "includes/mailer.php";

    $email = $_SESSION['reset_email'];
    $user_id = $_SESSION['reset_user_id'];

    // Get user name
    $stmt = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Generate new OTP
    $otp = rand(100000, 999999);
    $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    $_SESSION['reset_otp'] = $otp;
    $_SESSION['otp_expiry'] = $expiry;
    $_SESSION['otp_attempts'] = 0;

    // Send OTP
    $subject = "New Password Reset OTP - Blood Bank System";
    $body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;'>
        <div style='background: linear-gradient(135deg, #C41E3A, #FF4D4D); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;'>
            <h1 style='color: white; margin: 0;'>🩸 Blood Bank System</h1>
        </div>
        <div style='background: white; padding: 30px; border-radius: 0 0 10px 10px;'>
            <h2 style='color: #C41E3A;'>New OTP Request</h2>
            <p>Hello <strong>{$user['name']}</strong>,</p>
            <p>Here is your new OTP code for password reset:</p>
            <div style='background: #f0f0f0; padding: 20px; text-align: center; border-radius: 10px; margin: 20px 0;'>
                <h1 style='color: #C41E3A; font-size: 36px; margin: 0; letter-spacing: 5px;'>{$otp}</h1>
            </div>
            <p style='color: #666;'><strong>⏰ This OTP will expire in 15 minutes.</strong></p>
        </div>
    </div>
    ";

    if (send_mail($email, $subject, $body)) {
        $msg = "New OTP has been sent to your email.";
        $msg_type = "success";
    } else {
        $msg = "Failed to send OTP. Please try again.";
        $msg_type = "danger";
    }
}

// Calculate remaining time
$remaining_time = 0;
if (isset($_SESSION['otp_expiry'])) {
    $remaining_time = strtotime($_SESSION['otp_expiry']) - time();
    if ($remaining_time < 0) $remaining_time = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | Blood Bank</title>
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

        .verify-card {
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

        .verify-card .icon-wrapper {
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

        .verify-card .icon-wrapper i {
            font-size: 35px;
            color: white;
        }

        .verify-card h2 {
            margin-bottom: 15px;
            color: #C41E3A;
            font-weight: 700;
            font-size: 28px;
        }

        .verify-card .subtitle {
            color: #666;
            margin-bottom: 10px;
            font-size: 14px;
            line-height: 1.6;
        }

        .verify-card .email-display {
            background: #f8f8f8;
            padding: 10px 15px;
            border-radius: 10px;
            color: #C41E3A;
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 25px;
        }

        .otp-inputs input {
            width: 55px;
            height: 55px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            outline: none;
            transition: all 0.3s;
            color: #C41E3A;
        }

        .otp-inputs input:focus {
            border-color: #C41E3A;
            box-shadow: 0 0 15px rgba(196, 30, 58, 0.2);
            transform: scale(1.05);
        }

        .timer {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            color: #856404;
            font-size: 14px;
            font-weight: 600;
        }

        .timer i {
            margin-right: 5px;
        }

        .timer.expired {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .verify-card button {
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
            margin-bottom: 15px;
        }

        .verify-card button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(196, 30, 58, 0.4);
        }

        .verify-card button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .verify-card .resend-btn {
            background: linear-gradient(45deg, #6c757d, #495057);
        }

        .verify-card .resend-btn:hover:not(:disabled) {
            box-shadow: 0 10px 25px rgba(108, 117, 125, 0.4);
        }

        .verify-card .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #C41E3A;
            text-decoration: none;
            font-size: 14px;
        }

        .verify-card .back-link:hover {
            text-decoration: underline;
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

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .attempts-warning {
            color: #856404;
            font-size: 13px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="verify-card">
        <div class="icon-wrapper">
            <i class="fas fa-envelope-open"></i>
        </div>

        <h2>Verify OTP</h2>
        <p class="subtitle">
            We've sent a 6-digit verification code to
        </p>
        <div class="email-display">
            <i class="fas fa-envelope me-1"></i>
            <?php echo htmlspecialchars($_SESSION['reset_email']); ?>
        </div>

        <?php if ($remaining_time > 0): ?>
            <div class="timer" id="timer">
                <i class="fas fa-clock"></i>
                <span>OTP expires in: <strong><span id="countdown"><?php echo gmdate("i:s", $remaining_time); ?></span></strong></span>
            </div>
        <?php else: ?>
            <div class="timer expired">
                <i class="fas fa-exclamation-triangle"></i>
                <span>OTP has expired. Please request a new one.</span>
            </div>
        <?php endif; ?>

        <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msg_type; ?>">
                <i class="fas fa-<?php echo $msg_type == 'danger' ? 'exclamation-circle' : 'check-circle'; ?>"></i>
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" id="otpForm">
            <div class="otp-inputs">
                <input type="text" maxlength="1" class="otp-input" name="otp[]" pattern="[0-9]" required autocomplete="off">
                <input type="text" maxlength="1" class="otp-input" name="otp[]" pattern="[0-9]" required autocomplete="off">
                <input type="text" maxlength="1" class="otp-input" name="otp[]" pattern="[0-9]" required autocomplete="off">
                <input type="text" maxlength="1" class="otp-input" name="otp[]" pattern="[0-9]" required autocomplete="off">
                <input type="text" maxlength="1" class="otp-input" name="otp[]" pattern="[0-9]" required autocomplete="off">
                <input type="text" maxlength="1" class="otp-input" name="otp[]" pattern="[0-9]" required autocomplete="off">
            </div>
            <input type="hidden" name="otp" id="otpValue">

            <button type="submit" name="verify_otp" id="verifyBtn">
                <i class="fas fa-check-circle me-2"></i>Verify OTP
            </button>
        </form>

        <form action="" method="POST" style="margin-bottom: 0;">
            <button type="submit" name="resend_otp" class="resend-btn" id="resendBtn">
                <i class="fas fa-redo me-2"></i>Resend OTP
            </button>
        </form>

        <?php if ($attempts > 0): ?>
            <div class="attempts-warning">
                <i class="fas fa-exclamation-circle"></i>
                Failed attempts: <?php echo $attempts; ?>/3
            </div>
        <?php endif; ?>

        <a href="forgot_password.php" class="back-link">
            <i class="fas fa-arrow-left me-1"></i> Use different email
        </a>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // OTP Input handling
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpForm = document.getElementById('otpForm');
        const otpValue = document.getElementById('otpValue');

        otpInputs.forEach((input, index) => {
            // Focus next input on entry
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1) {
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    } else {
                        // All inputs filled, combine OTP
                        updateOTPValue();
                    }
                }
            });

            // Handle backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '') {
                    if (index > 0) {
                        otpInputs[index - 1].focus();
                    }
                }
            });

            // Only allow numbers
            input.addEventListener('keypress', (e) => {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });

            // Handle paste
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').slice(0, 6);
                const digits = pasteData.match(/\d/g);

                if (digits) {
                    digits.forEach((digit, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = digit;
                        }
                    });
                    updateOTPValue();
                    otpInputs[Math.min(digits.length, otpInputs.length - 1)].focus();
                }
            });
        });

        function updateOTPValue() {
            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });
            otpValue.value = otp;
        }

        // Update OTP value before form submission
        otpForm.addEventListener('submit', (e) => {
            updateOTPValue();
            if (otpValue.value.length !== 6) {
                e.preventDefault();
                alert('Please enter all 6 digits');
            }
        });

        // Countdown timer
        <?php if ($remaining_time > 0): ?>
            let timeLeft = <?php echo $remaining_time; ?>;
            const countdown = document.getElementById('countdown');
            const timerDiv = document.getElementById('timer');

            const timer = setInterval(() => {
                timeLeft--;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    timerDiv.classList.add('expired');
                    timerDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> <span>OTP has expired. Please request a new one.</span>';
                    document.getElementById('verifyBtn').disabled = true;
                } else {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    countdown.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                    if (timeLeft <= 60) {
                        timerDiv.style.background = '#f8d7da';
                        timerDiv.style.borderColor = '#f5c6cb';
                        timerDiv.style.color = '#721c24';
                    }
                }
            }, 1000);
        <?php endif; ?>

        // Auto-focus first input
        otpInputs[0].focus();
    </script>
</body>

</html>