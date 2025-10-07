<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $hospital_id = intval($_GET['id']);

    $update = $conn->query("UPDATE users SET status='rejected' WHERE user_id=$hospital_id AND role='hospital'");

    if ($update) {
        $_SESSION['message'] = "Hospital rejected!";
        $_SESSION['alert_type'] = "warning";
    } else {
        $_SESSION['message'] = "Failed to reject hospital!";
        $_SESSION['alert_type'] = "danger";
    }
}
header("Location: manage_hospitals.php");
exit();
