<?php
include "../includes/admin_auth.php";
check_admin_role();
include "../includes/db.php";

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    // SECURITY FIX: Use prepared statements to prevent SQL injection
    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE users SET status='approved' WHERE user_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Get user details for notification
        $user_stmt = $conn->prepare("SELECT name, email FROM users WHERE user_id=?");
        $user_stmt->bind_param("i", $id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result()->fetch_assoc();

        // Create notification for approved user
        $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type) VALUES (?, ?, 'system')");
        $message = "Your account has been approved! You can now access all features.";
        $notif_stmt->bind_param("is", $id, $message);
        $notif_stmt->execute();

        $_SESSION['message'] = "User '{$user_result['name']}' approved successfully!";
        $_SESSION['alert_type'] = "success";
    } elseif ($action == 'reject') {
        $stmt = $conn->prepare("UPDATE users SET status='rejected' WHERE user_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Get user details
        $user_stmt = $conn->prepare("SELECT name FROM users WHERE user_id=?");
        $user_stmt->bind_param("i", $id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result()->fetch_assoc();

        // Create notification for rejected user
        $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type) VALUES (?, ?, 'system')");
        $message = "Your account registration has been rejected. Please contact admin for more information.";
        $notif_stmt->bind_param("is", $id, $message);
        $notif_stmt->execute();

        $_SESSION['message'] = "User '{$user_result['name']}' rejected.";
        $_SESSION['alert_type'] = "warning";
    }
}
header("Location: manage_users.php");
exit();
