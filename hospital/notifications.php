<?php
include_once "../includes/auth_check.php";
check_role('hospital');
include_once "../includes/db.php";

$hospital_id = $_SESSION['user_id'];

// Mark notification as read if requested
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $notification_id = intval($_GET['mark_read']);
    $update_stmt = $conn->prepare("UPDATE notifications SET status = 'read' WHERE id = ? AND user_id = ?");
    $update_stmt->bind_param("ii", $notification_id, $hospital_id);
    $update_stmt->execute();
    $update_stmt->close();
    header("Location: notifications.php");
    exit();
}

// Mark all notifications as read
if (isset($_POST['mark_all_read'])) {
    $update_all_stmt = $conn->prepare("UPDATE notifications SET status = 'read' WHERE user_id = ? AND status = 'unread'");
    $update_all_stmt->bind_param("i", $hospital_id);
    $update_all_stmt->execute();
    $update_all_stmt->close();
    header("Location: notifications.php");
    exit();
}

// Fetch notifications for current hospital
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY id DESC LIMIT 50";
$stmt = $conn->prepare($notifications_query);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$notifications = $stmt->get_result();
$stmt->close();

// Count unread notifications
$unread_query = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND status = 'unread'";
$unread_stmt = $conn->prepare($unread_query);
$unread_stmt->bind_param("i", $hospital_id);
$unread_stmt->execute();
$unread_count = $unread_stmt->get_result()->fetch_assoc()['unread_count'];
$unread_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hospital Notifications</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/hospital.css">
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

        .page-header {
            background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .unread-badge {
            position: absolute;
            top: 20px;
            right: 30px;
            background: #ff4757;
            color: white;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 0.9em;
            font-weight: 600;
        }

        .notification-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #ddd;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            position: relative;
        }

        .notification-card.unread {
            border-left-color: #C41E3A;
            background: #fff8f8;
        }

        .notification-card:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .notification-type {
            font-size: 0.8em;
            padding: 4px 10px;
            border-radius: 15px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-request {
            background: #fee2e2;
            color: #dc2626;
        }

        .type-donation {
            background: #dcfce7;
            color: #16a34a;
        }

        .type-system {
            background: #e0e7ff;
            color: #4338ca;
        }

        .notification-time {
            color: #6b7280;
            font-size: 0.9em;
        }

        .notification-message {
            color: #374151;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .mark-read-btn {
            background: #f3f4f6;
            border: none;
            color: #6b7280;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.8em;
            transition: 0.3s;
        }

        .mark-read-btn:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .unread-indicator {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 10px;
            height: 10px;
            background: #C41E3A;
            border-radius: 50%;
        }

        .no-notifications {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .no-notifications i {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .action-buttons {
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-mark-all {
            background: linear-gradient(135deg, #C41E3A, #8B0000);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-mark-all:hover {
            background: linear-gradient(135deg, #8B0000, #C41E3A);
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="page-header">
            <h2><i class="fas fa-bell me-3"></i>Hospital Notifications</h2>
            <p class="mb-0">Stay updated with blood requests and donation responses</p>
            <?php if ($unread_count > 0): ?>
                <div class="unread-badge">
                    <?= $unread_count ?> New
                </div>
            <?php endif; ?>
        </div>

        <?php if ($unread_count > 0): ?>
            <div class="action-buttons">
                <form method="POST" style="display: inline;">
                    <button type="submit" name="mark_all_read" class="btn btn-mark-all">
                        <i class="fas fa-check-double me-2"></i>Mark All as Read
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($notifications->num_rows > 0): ?>
            <?php while ($notification = $notifications->fetch_assoc()): ?>
                <div class="notification-card <?= $notification['status'] == 'unread' ? 'unread' : '' ?>">
                    <?php if ($notification['status'] == 'unread'): ?>
                        <div class="unread-indicator"></div>
                    <?php endif; ?>

                    <div class="notification-header">
                        <span class="notification-type type-<?= htmlspecialchars($notification['type']) ?>">
                            <?= htmlspecialchars($notification['type']) ?>
                        </span>
                        <span class="notification-time">
                            <?= date('d M Y, h:i A', strtotime($notification['created_at'] ?? 'now')) ?>
                        </span>
                    </div>

                    <div class="notification-message">
                        <?= htmlspecialchars($notification['message']) ?>
                    </div>

                    <?php if ($notification['status'] == 'unread'): ?>
                        <div class="notification-actions">
                            <a href="?mark_read=<?= $notification['id'] ?>" class="mark-read-btn">
                                <i class="fas fa-check me-1"></i>Mark as Read
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-notifications">
                <i class="fas fa-bell-slash"></i>
                <h4>No Notifications</h4>
                <p>You don't have any notifications yet.<br>
                    When users make blood requests or donations, they'll appear here.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>