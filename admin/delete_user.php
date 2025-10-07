<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

if(isset($_GET['id'])){
    $user_id = intval($_GET['id']);

    // Delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=? AND role='user'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to manage users
header("Location: manage_users.php");
exit;
?>
