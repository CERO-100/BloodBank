<?php
include "../includes/admin_auth.php";
check_admin_role();
include "../includes/db.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $hospital_id = intval($_GET['id']);

    // SECURITY FIX: Use prepared statement
    $stmt = $conn->prepare("UPDATE users SET status='approved' WHERE user_id=? AND role='hospital'");
    $stmt->bind_param("i", $hospital_id);
    $update = $stmt->execute();

    if ($update) {
        // Get hospital details
        $hosp_stmt = $conn->prepare("SELECT name FROM users WHERE user_id=?");
        $hosp_stmt->bind_param("i", $hospital_id);
        $hosp_stmt->execute();
        $hosp_result = $hosp_stmt->get_result()->fetch_assoc();

        // Create notification for approved hospital
        $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type) VALUES (?, ?, 'system')");
        $message = "Your hospital has been approved! You can now manage blood stock and receive requests.";
        $notif_stmt->bind_param("is", $hospital_id, $message);
        $notif_stmt->execute();

        $_SESSION['message'] = "Hospital '{$hosp_result['name']}' approved successfully!";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to approve hospital!";
        $_SESSION['alert_type'] = "danger";
    }
}
header("Location: manage_hospitals.php");
exit();
