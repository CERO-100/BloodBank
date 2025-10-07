<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $hospital_id = intval($_GET['id']);

    $update = $conn->query("UPDATE users SET status='approved' WHERE user_id=$hospital_id AND role='hospital'");

    if ($update) {
        $_SESSION['message'] = "Hospital approved successfully!";
        $_SESSION['alert_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to approve hospital!";
        $_SESSION['alert_type'] = "danger";
    }
}
header("Location: manage_hospitals.php");
exit();
