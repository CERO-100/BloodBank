<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

// Fetch only hospitals
$hospitals = $conn->query("SELECT * FROM users WHERE role='hospital'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Hospitals</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-4">
    <h3><i class="fas fa-hospital me-2"></i>Manage Hospitals</h3>

    <table class="table table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($hospitals && $hospitals->num_rows > 0): ?>
            <?php while($row = $hospitals->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td>
                        <?php if ($row['status'] == 'pending'): ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php elseif ($row['status'] == 'approved'): ?>
                            <span class="badge bg-success">Approved</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td>
    <a href="approve_h.php?id=<?php echo $row['user_id']; ?>&action=approve" class="btn btn-success btn-sm">
        <i class="fas fa-check"></i>
    </a>
    <a href="delete_h.php?id=<?php echo $row['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this hospital?');">
        <i class="fas fa-trash"></i>
    </a>
    <a href="edit_h.php?id=<?php echo $row['user_id']; ?>" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i>
    </a>
</td>

                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center">No hospitals found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include "footer.php"; ?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
