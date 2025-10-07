<?php
include_once "../includes/auth_check.php";
check_role('user');
include_once "../includes/db.php";

$msg = "";
$user_id = $_SESSION['user_id'];

// Handle blood request response (when user wants to help someone)
if (isset($_POST['respond_to_request'])) {
    $request_id = $_POST['request_id'];
    $requester_id = $_POST['requester_id'];
    $blood_group = $_POST['blood_group'];

    // Check if current user has the matching blood group
    $user_blood_query = $conn->prepare("SELECT blood_group FROM users WHERE user_id = ?");
    $user_blood_query->bind_param("i", $user_id);
    $user_blood_query->execute();
    $user_blood = $user_blood_query->get_result()->fetch_assoc();

    if ($user_blood && $user_blood['blood_group'] == $blood_group) {
        // Get current user's contact info
        $donor_info_query = $conn->prepare("SELECT name, phone, email FROM users WHERE user_id = ?");
        $donor_info_query->bind_param("i", $user_id);
        $donor_info_query->execute();
        $donor_info = $donor_info_query->get_result()->fetch_assoc();

        // Create a notification for the requester that someone wants to help
        $notification_msg = "Good news! {$donor_info['name']} (Blood Group: {$blood_group}) wants to help with your blood request. Contact them: Phone - {$donor_info['phone']}, Email - {$donor_info['email']}";
        $notification_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type, status) VALUES (?, ?, 'request', 'unread')");
        $notification_stmt->bind_param("is", $requester_id, $notification_msg);

        if ($notification_stmt->execute()) {
            $msg = "Response sent successfully! Your contact information has been shared with the requester.";
        } else {
            $msg = "Error: " . $notification_stmt->error;
        }
    } else {
        $msg = "You cannot respond to this request as your blood group doesn't match.";
    }
}

// Fetch public blood requests from other users (excluding current user's requests)
$query = "SELECT r.request_id, u.name AS requester_name, u.phone, u.email, r.blood_group, r.quantity, r.created_at, u.user_id as requester_id
          FROM requests r
          JOIN users u ON r.user_id = u.user_id
          WHERE r.hospital_id IS NULL 
          AND r.user_id != ? 
          AND r.status = 'pending'
          ORDER BY r.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Get current user's blood group for matching
$user_blood_stmt = $conn->prepare("SELECT blood_group FROM users WHERE user_id = ?");
$user_blood_stmt->bind_param("i", $user_id);
$user_blood_stmt->execute();
$current_user = $user_blood_stmt->get_result()->fetch_assoc();
$current_user_blood = $current_user['blood_group'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Blood Requests from Community</title>
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
            max-width: 1000px;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .request-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .request-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .requester-name {
            color: #C41E3A;
            font-weight: 700;
            font-size: 1.2em;
        }

        .blood-group-badge {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1.1em;
        }

        .request-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .contact-info {
            flex: 1;
        }

        .contact-info p {
            margin: 5px 0;
            color: #555;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-respond {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-respond:hover {
            background: linear-gradient(135deg, #0d7377, #2dd4bf);
            transform: translateY(-2px);
        }

        .btn-respond:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        .btn-contact {
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 0.9em;
            margin-right: 5px;
        }

        .match-indicator {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.9em;
            font-weight: 600;
        }

        .no-match-indicator {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.9em;
        }

        .alert-success,
        .alert-danger {
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            text-align: center;
        }

        .no-requests {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .no-requests i {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="page-header">
            <h2><i class="fas fa-hands-helping me-3"></i>Community Blood Requests</h2>
            <p class="mb-0">Help fellow community members by responding to their blood requests</p>
        </div>

        <?php if ($msg): ?>
            <div class="alert <?= strpos($msg, 'Error') !== false || strpos($msg, 'cannot') !== false ? 'alert-danger' : 'alert-success' ?>">
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="request-card">
                    <div class="request-header">
                        <div class="requester-name">
                            <i class="fas fa-user me-2"></i><?= htmlspecialchars($row['requester_name']) ?>
                        </div>
                        <div class="blood-group-badge">
                            <i class="fas fa-tint me-2"></i><?= htmlspecialchars($row['blood_group']) ?>
                        </div>
                    </div>

                    <div class="request-details">
                        <div class="contact-info">
                            <p><i class="fas fa-calendar me-2"></i><strong>Requested:</strong> <?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></p>
                            <p><i class="fas fa-phone me-2"></i><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                            <p><i class="fas fa-envelope me-2"></i><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>

                            <?php if ($current_user_blood == $row['blood_group']): ?>
                                <div class="match-indicator">
                                    <i class="fas fa-check-circle me-2"></i>You can help! Your blood group matches.
                                </div>
                            <?php else: ?>
                                <div class="no-match-indicator">
                                    <i class="fas fa-times-circle me-2"></i>Blood group doesn't match (You: <?= $current_user_blood ?: 'Not set' ?>)
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="action-buttons">
                            <?php if ($current_user_blood == $row['blood_group']): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                                    <input type="hidden" name="requester_id" value="<?= $row['requester_id'] ?>">
                                    <input type="hidden" name="blood_group" value="<?= $row['blood_group'] ?>">
                                    <button type="submit" name="respond_to_request" class="btn btn-respond">
                                        <i class="fas fa-heart me-2"></i>I Can Help
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-respond" disabled>
                                    <i class="fas fa-times me-2"></i>Cannot Help
                                </button>
                            <?php endif; ?>

                            <a href="tel:<?= $row['phone'] ?>" class="btn btn-primary btn-contact">
                                <i class="fas fa-phone me-1"></i>Call
                            </a>
                            <a href="mailto:<?= $row['email'] ?>" class="btn btn-warning btn-contact">
                                <i class="fas fa-envelope me-1"></i>Email
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-requests">
                <i class="fas fa-heart"></i>
                <h4>No Blood Requests Found</h4>
                <p>There are currently no pending blood requests from the community.<br>
                    Check back later or encourage others to use the platform!</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>