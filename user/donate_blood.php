<?php
include "../includes/auth_check.php";
check_role('user');
include "../includes/db.php";

// Fetch the logged-in user's blood group
$user_id = $_SESSION['user_id'];
// SECURITY FIX: Use prepared statement
$stmt = $conn->prepare("SELECT blood_group FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$blood_group = $user['blood_group'] ?? '';
$stmt->close();

if (!$blood_group) {
    $msg = "Your blood group is not set. Please update your profile first.";
}

// Handle donation submission
if (isset($_POST['donate']) && $blood_group) {
    $hospital_id = (int) $_POST['hospital_id'];
    $quantity = 1; // Always 1 unit per donation
    $status = 'pending';

    // Get donor information
    $donor_stmt = $conn->prepare("SELECT name, phone, email FROM users WHERE user_id = ?");
    $donor_stmt->bind_param("i", $user_id);
    $donor_stmt->execute();
    $donor_info = $donor_stmt->get_result()->fetch_assoc();
    $donor_stmt->close();

    $stmt = $conn->prepare("INSERT INTO donations (user_id, hospital_id, blood_group, quantity, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisis", $user_id, $hospital_id, $blood_group, $quantity, $status);

    if ($stmt->execute()) {
        // Create notification for the hospital
        $notification_msg = "New blood donation from {$donor_info['name']} - Blood Group: {$blood_group} (1 unit). Contact: {$donor_info['phone']}";
        $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type, status) VALUES (?, ?, 'donation', 'unread')");
        $notif_stmt->bind_param("is", $hospital_id, $notification_msg);
        $notif_stmt->execute();
        $notif_stmt->close();

        $msg = "Donation submitted successfully! Waiting for hospital approval.";
    } else {
        $msg = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all hospitals
$hospitals = $conn->query("SELECT user_id, name, location FROM users WHERE role='hospital'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Donate Blood</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            margin-top: 60px;
            max-width: 500px;
        }

        .card-donate {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 20px;
            padding: 30px 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card-donate:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .card-donate h3 {
            color: #C41E3A;
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-select {
            height: 50px;
            border-radius: 10px;
            padding-left: 15px;
        }

        .btn-custom {
            width: 100%;
            background-color: #C41E3A;
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
            padding: 12px;
            margin-top: 20px;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background-color: #900020;
        }

        .alert-success,
        .alert-danger {
            text-align: center;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px;
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="card-donate">
            <h3><i class="fas fa-hand-holding-medical me-2"></i>Donate Blood</h3>

            <?php if (isset($msg)) echo "<div class='alert " . (strpos($msg, 'Error') !== false ? 'alert-danger' : 'alert-success') . "'>$msg</div>"; ?>

            <?php if ($blood_group): ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-hospital me-2"></i>Select Hospital</label>
                        <select name="hospital_id" class="form-select" required>
                            <option value="">-- Select Hospital --</option>
                            <?php while ($row = $hospitals->fetch_assoc()): ?>
                                <option value="<?= $row['user_id']; ?>"><?= htmlspecialchars($row['name'] . ' - ' . $row['location']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <p class="text-center">Your Blood Group: <strong><?= htmlspecialchars($blood_group); ?></strong></p>
                    <p class="text-center">Quantity per donation: <strong>1 Unit</strong></p>

                    <button type="submit" name="donate" class="btn btn-custom"><i class="fas fa-paper-plane me-2"></i>Submit Donation</button>
                </form>
            <?php else: ?>
                <p class="text-center text-danger">You need to set your blood group in your profile to donate blood.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>