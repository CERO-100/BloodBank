<?php
include "../includes/auth_check.php";
check_role('user');
include "../includes/db.php";

$msg = "";
$selected_group = '';

// Handle blood request to a specific public donor
if (isset($_POST['request_to_donor'])) {
    $user_id = $_SESSION['user_id'];
    $donor_id = intval($_POST['donor_id']);
    $blood_group = $_POST['blood_group'];
    $quantity = 1; // Always 1 unit
    $status = 'pending';
    $hospital_id = NULL; // No hospital

    // Get requester's information
    $requester_stmt = $conn->prepare("SELECT name, phone, email FROM users WHERE user_id = ?");
    $requester_stmt->bind_param("i", $user_id);
    $requester_stmt->execute();
    $requester_info = $requester_stmt->get_result()->fetch_assoc();
    $requester_stmt->close();

    $stmt = $conn->prepare("INSERT INTO requests (user_id, hospital_id, blood_group, quantity, status, donor_id) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("iisisi", $user_id, $hospital_id, $blood_group, $quantity, $status, $donor_id);

    if ($stmt->execute()) {
        // Create notification for the donor
        $notification_msg = "Blood request from {$requester_info['name']} - They need {$blood_group} blood. Contact: {$requester_info['phone']}, Email: {$requester_info['email']}";
        $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type, status) VALUES (?, ?, 'request', 'unread')");
        $notif_stmt->bind_param("is", $donor_id, $notification_msg);
        $notif_stmt->execute();
        $notif_stmt->close();

        $msg = "Request sent to the donor successfully!";
    } else {
        $msg = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// If blood group is selected, fetch matching donors
if (isset($_POST['filter'])) {
    $selected_group = $_POST['blood_group'];
    $stmt = $conn->prepare("SELECT user_id, name, blood_group, phone, email FROM users WHERE role='user' AND blood_group=?");
    $stmt->bind_param("s", $selected_group);
    $stmt->execute();
    $donors = $stmt->get_result();
} else {
    $donors = $conn->query("SELECT user_id, name, blood_group, phone, email FROM users WHERE role='user' AND blood_group IS NOT NULL");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Request Blood from Public</title>
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
            max-width: 900px;
        }

        .card-request {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
        }

        .card-request h3 {
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

        .alert-success {
            text-align: center;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px;
        }

        .card-donor {
            border-radius: 15px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-donor h5 {
            color: #C41E3A;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .card-donor p {
            margin: 0;
        }

        .donor-info {
            flex: 1;
        }

        .donor-actions {
            text-align: right;
        }

        .donor-actions form,
        .donor-actions a {
            margin-left: 5px;
        }

        .donor-actions button {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <?php include "header.php"; ?>

    <div class="container">
        <div class="card-request">
            <h3><i class="fas fa-users me-2"></i>Request Blood from Public</h3>

            <?php if ($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>

            <!-- Blood Group Filter -->
            <form method="POST" class="mb-4">
                <div class="mb-3">
                    <select name="blood_group" class="form-select" required>
                        <option value="">Select Blood Group</option>
                        <?php
                        $groups = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];
                        foreach ($groups as $bg) {
                            $sel = ($bg == $selected_group) ? "selected" : "";
                            echo "<option value='$bg' $sel>$bg</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="filter" class="btn btn-custom"><i class="fas fa-filter me-2"></i>Filter Donors</button>
            </form>

            <!-- Donor Cards -->
            <?php if ($donors->num_rows > 0): ?>
                <?php while ($row = $donors->fetch_assoc()): ?>
                    <div class="card-donor">
                        <div class="donor-info">
                            <h5><?= htmlspecialchars($row['name']) ?></h5>
                            <p><i class="fas fa-tint me-1"></i> Blood Group: <?= htmlspecialchars($row['blood_group']) ?></p>
                            <p><i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($row['email']) ?></p>
                            <p><i class="fas fa-phone me-1"></i> <?= htmlspecialchars($row['phone']) ?></p>
                        </div>
                        <div class="donor-actions">
                            <form method="POST">
                                <input type="hidden" name="donor_id" value="<?= $row['user_id'] ?>">
                                <input type="hidden" name="blood_group" value="<?= $row['blood_group'] ?>">
                                <button type="submit" name="request_to_donor" class="btn btn-sm btn-success mb-1"><i class="fas fa-paper-plane me-1"></i> Request</button>
                            </form>
                            <a href="tel:<?= $row['phone'] ?>" class="btn btn-sm btn-primary mb-1"><i class="fas fa-phone me-1"></i> Call</a>
                            <a href="mailto:<?= $row['email'] ?>" class="btn btn-sm btn-warning mb-1"><i class="fas fa-envelope me-1"></i> Message</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-muted">No donors found for this blood group.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>