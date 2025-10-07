<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

$requests = $conn->query("SELECT r.*, u.name AS user_name, h.name AS hospital_name 
                          FROM requests r
                          JOIN users u ON r.user_id=u.user_id
                          JOIN users h ON r.hospital_id=h.user_id
                          ORDER BY r.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Blood Requests</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .table thead {
        background: #C41E3A;
        color: white;
    }
    .badge-pending {
        background-color: #ffc107;
        color: #000;
    }
    .badge-approved {
        background-color: #28a745;
    }
    .badge-rejected {
        background-color: #dc3545;
    }
    .btn {
        border-radius: 8px;
    }
    .btn-sm i {
        font-size: 14px;
    }
</style>
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-hand-holding-medical me-2 text-danger"></i>Blood Requests</h4>
            <a href="admin.php" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Hospital</th>
                            <th>Blood Group</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($row = $requests->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['request_id']; ?></td>
                            <td><i class="fas fa-user text-muted me-2"></i><?php echo $row['user_name']; ?></td>
                            <td><i class="fas fa-hospital text-muted me-2"></i><?php echo $row['hospital_name']; ?></td>
                            <td><span class="fw-bold text-danger"><?php echo $row['blood_group']; ?></span></td>
                            <td><?php echo $row['quantity']; ?> units</td>
                            <td>
                                <?php if($row['status']=='pending'): ?>
                                    <span class="badge badge-pending">Pending</span>
                                <?php elseif($row['status']=='approved'): ?>
                                    <span class="badge badge-approved">Approved</span>
                                <?php else: ?>
                                    <span class="badge badge-rejected">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($row['status']=='pending'): ?>
                                    <a href="approve_requests.php?id=<?php echo $row['request_id']; ?>&action=approve" 
                                       class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Approve Request">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="approve_requests.php?id=<?php echo $row['request_id']; ?>&action=reject" 
                                       class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Reject Request">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
</body>
</html>
