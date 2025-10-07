<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

if(isset($_GET['id'])){
    $hospital_id = intval($_GET['id']);

    // Delete the hospital
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id=? AND role='hospital'");
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to manage hospitals
header("Location: manage_hospitals.php");
exit;
?>
