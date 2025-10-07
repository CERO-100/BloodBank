<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'approve') {
        $conn->query("UPDATE users SET status='approved' WHERE user_id=$id");
    } elseif ($action == 'reject') {
        $conn->query("UPDATE users SET status='rejected' WHERE user_id=$id");
    }
}
header("Location: manage_users.php");
exit();
