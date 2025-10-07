<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

// Fetch all users with role user
$users = $conn->query("SELECT * FROM users WHERE role='user'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | Admin</title>
<?php include "head.php"; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        background: #f9f9f9;
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    .badge-pending {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-approved {
        background-color: #28a745;
    }
    .badge-rejected {
        background-color: #dc3545;
    }
    .action-btns a {
        margin-right: 6px;
    }
    .table-hover tbody tr:hover {
        background: #f1f1f1;
    }
</style>
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-users me-2"></i>Manage Users</h4>
        </div>
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Blood Group</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['user_id']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><span class="fw-bold"><?= htmlspecialchars($row['blood_group']); ?></span></td>
                        <td>
                            <?php if($row['status']=='pending'): ?>
                                <span class="badge badge-pending">Pending</span>
                            <?php elseif($row['status']=='approved'): ?>
                                <span class="badge badge-approved">Approved</span>
                            <?php else: ?>
                                <span class="badge badge-rejected">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center action-btns">
                            <a href="approve_user.php?id=<?= $row['user_id']; ?>&action=approve" 
                               class="btn btn-success btn-sm" title="Approve">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="delete_user.php?id=<?= $row['user_id']; ?>" 
   class="btn btn-danger btn-sm" 
   onclick="return confirm('Are you sure you want to delete this user?');" 
   title="Delete">
    <i class="fas fa-trash"></i>
</a>


                            <a href="edit_user.php?id=<?= $row['user_id']; ?>" 
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
