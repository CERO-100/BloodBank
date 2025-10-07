<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

// Mark all unread as read when admin opens page
$conn->query("UPDATE notifications SET status='read' WHERE status='unread'");

// Fetch notifications
$notifications = $conn->query("
    SELECT n.id, n.message, n.type, n.status, n.created_at, u.name AS sender 
    FROM notifications n 
    LEFT JOIN users u ON n.user_id = u.user_id 
    ORDER BY n.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Notifications</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-4">
    <h3><i class="fas fa-bell me-2 text-warning"></i> Notifications</h3>
    <div class="card shadow-sm mt-3">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                <?php if($notifications->num_rows > 0): ?>
                    <?php while($row = $notifications->fetch_assoc()): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">
                                    <?php if($row['type'] == 'request'): ?>
                                        <i class="fas fa-hand-holding-medical text-danger me-2"></i>
                                    <?php elseif($row['type'] == 'donation'): ?>
                                        <i class="fas fa-tint text-primary me-2"></i>
                                    <?php elseif($row['type'] == 'user'): ?>
                                        <i class="fas fa-user-plus text-success me-2"></i>
                                    <?php else: ?>
                                        <i class="fas fa-info-circle text-secondary me-2"></i>
                                    <?php endif; ?>
                                    <?php echo ucfirst($row['type']); ?>
                                </div>
                                <small class="text-muted">
                                    <?php echo $row['sender'] ? htmlspecialchars($row['sender']).' - ' : ''; ?>
                                    <?php echo htmlspecialchars($row['message']); ?>
                                </small>
                            </div>
                            <span class="badge rounded-pill bg-secondary">
                                <?php echo date("d M, H:i", strtotime($row['created_at'])); ?>
                            </span>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="list-group-item text-center text-muted py-4">
                        <i class="fas fa-check-circle me-2"></i>No new notifications
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
