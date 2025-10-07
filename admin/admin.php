<?php
include "../includes/admin_auth.php";
check_admin_role(); // This replaces check_role('admin')
include "../includes/db.php";
// Fetch stats
$total_users = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='user'")->fetch_assoc()['total'];
$total_hospitals = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='hospital'")->fetch_assoc()['total'];
$pending_requests = $conn->query("SELECT COUNT(*) as total FROM requests WHERE status='pending'")->fetch_assoc()['total'];
$recent_donations = $conn->query("SELECT d.donation_id, u.name AS donor_name, d.blood_group, d.quantity, d.created_at
                                  FROM donations d
                                  JOIN users u ON d.user_id = u.user_id
                                  ORDER BY d.created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { background: #f4f6f9; font-family: 'Poppins', sans-serif; }
.card { border-radius: 12px; transition: 0.3s; }
.card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
.badge { font-size: 0.9rem; }
.stats-card { background: linear-gradient(135deg, #ff4b2b, #ff416c); color: #fff; }
.stats-card i { font-size: 1.5rem; }
</style>
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-4">

<h2 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h2>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Total Users</h5>
                    <h3><?php echo $total_users; ?></h3>
                </div>
                <i class="fas fa-users"></i>
            </div>
            <a href="manage_users.php" class="btn btn-light btn-sm mt-2 w-100">Manage Users</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card p-3" style="background: linear-gradient(135deg,#28a745,#218838);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Total Hospitals</h5>
                    <h3><?php echo $total_hospitals; ?></h3>
                </div>
                <i class="fas fa-hospital"></i>
            </div>
            <a href="manage_hospitals.php" class="btn btn-light btn-sm mt-2 w-100">Manage Hospitals</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card p-3" style="background: linear-gradient(135deg,#ffc107,#ff9800);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Pending Requests</h5>
                    <h3><?php echo $pending_requests; ?></h3>
                </div>
                <i class="fas fa-hand-holding-medical"></i>
            </div>
            <a href="view_requests.php" class="btn btn-light btn-sm mt-2 w-100">View Requests</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card p-3" style="background: linear-gradient(135deg,#6c63ff,#4b3ca7);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Recent Donations</h5>
                    <h3><?php echo $recent_donations->num_rows; ?></h3>
                </div>
                <i class="fas fa-donate"></i>
            </div>
            <a href="donations.php" class="btn btn-light btn-sm mt-2 w-100">View Donations</a>
        </div>
    </div>
</div>

<!-- Recent Donations Table -->
<div class="card p-3">
    <h4 class="mb-3"><i class="fas fa-history me-2"></i>Latest 5 Donations</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Donor</th>
                <th>Blood Group</th>
                <th>Quantity</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if($recent_donations->num_rows>0){
                while($row = $recent_donations->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $row['donor_name']; ?></td>
                        <td><?php echo $row['blood_group']; ?></td>
                        <td><?php echo $row['quantity']; ?> Units</td>
                        <td><?php echo date('d-M-Y', strtotime($row['created_at'])); ?></td>
                    </tr>
            <?php }} else { ?>
                <tr><td colspan="4" class="text-center">No recent donations</td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</div>

<?php include "footer.php"; ?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
