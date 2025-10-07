<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = intval($_GET['id']);

// Update user status to rejected
$stmt = $conn->prepare("UPDATE users SET status='rejected' WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

header("Location: manage_users.php?msg=rejected");
exit();
