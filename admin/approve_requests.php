<?php
include_once "../includes/admin_auth.php";
check_admin_role('admin');
include_once "../includes/db.php";

if(isset($_GET['id'], $_GET['action'])){
    $id = intval($_GET['id']);
    $action = $_GET['action'] === 'approve' ? 'approved' : 'rejected';
    $stmt = $conn->prepare("UPDATE requests SET status=? WHERE request_id=?");
    $stmt->bind_param("si",$action,$id);
    $stmt->execute();
    header("Location: admin.php");
    exit;
}
?>
