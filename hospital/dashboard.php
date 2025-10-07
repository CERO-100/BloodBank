<?php
include "../includes/auth_check.php";
check_role('hospital');
include "../includes/db.php";

// Hospital Info
$hospital_id = $_SESSION['user_id'];
$hospital_name = $conn->query("SELECT name FROM users WHERE user_id=$hospital_id")->fetch_assoc()['name'] ?? 'Hospital';

// Stats
$stock_count = $conn->query("SELECT SUM(quantity) AS total_stock FROM blood_stock WHERE hospital_id=$hospital_id")->fetch_assoc()['total_stock'] ?? 0;
$request_count = $conn->query("SELECT COUNT(*) AS total_requests FROM requests WHERE hospital_id=$hospital_id AND status='pending'")->fetch_assoc()['total_requests'] ?? 0;
$donations_count = $conn->query("SELECT SUM(quantity) AS total_donations FROM donations WHERE hospital_id=$hospital_id")->fetch_assoc()['total_donations'] ?? 0;

// Blood group stock
$group_stock = $conn->query("
    SELECT blood_group, SUM(quantity) as qty
    FROM blood_stock 
    WHERE hospital_id = $hospital_id
    GROUP BY blood_group
");

// Recent Requests
$recent_requests = $conn->query("
    SELECT r.request_id, u.name AS user_name, r.blood_group, r.quantity, r.status, r.created_at
    FROM requests r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.hospital_id = $hospital_id
    ORDER BY r.created_at DESC LIMIT 5
");

// Donors & Recipients
$users_query = $conn->query("
    SELECT DISTINCT u.name, u.role, u.blood_group, u.phone
    FROM users u
    JOIN donations d ON u.user_id = d.user_id
    WHERE d.hospital_id = $hospital_id
    UNION
    SELECT DISTINCT u.name, u.role, u.blood_group, u.phone
    FROM users u
    JOIN requests r ON u.user_id = r.user_id
    WHERE r.hospital_id = $hospital_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hospital Dashboard</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; }
    .card { border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
    .card-title { font-weight: 600; }
    .display-6 { font-weight: bold; }
    .badge-pending { background: orange; }
    .badge-approved { background: green; }
    .badge-rejected { background: red; }
</style>
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-4">
    <h2>Welcome, <?php echo htmlspecialchars($hospital_name); ?> <i class="fas fa-hospital ms-2"></i></h2>

    <div class="row mt-4">
        <!-- Blood Stock -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-vials me-2"></i>Blood Stock</h5>
                    <p class="display-6"><?php echo $stock_count ?: 0; ?> Units</p>
                    <table class="table table-sm mt-3">
                        <thead class="table-light">
                            <tr><th>Group</th><th>Units</th></tr>
                        </thead>
                        <tbody>
                            <?php if($group_stock->num_rows > 0){ 
                                while($row = $group_stock->fetch_assoc()){ ?>
                                    <tr>
                                        <td><?php echo $row['blood_group']; ?></td>
                                        <td><?php echo $row['qty']; ?></td>
                                    </tr>
                            <?php }} else { ?>
                                <tr><td colspan="2">No stock</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <a href="stock.php" class="btn btn-primary btn-sm">Manage Stock</a>
                </div>
            </div>
        </div>
        <!-- Pending Requests -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-hand-holding-medical me-2"></i>Pending Requests</h5>
                    <p class="display-6"><?php echo $request_count; ?></p>
                    <a href="requests.php" class="btn btn-danger btn-sm">View Requests</a>
                </div>
            </div>
        </div>
        <!-- Donations -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-donate me-2"></i>Total Donations</h5>
                    <p class="display-6"><?php echo $donations_count ?: 0; ?> Units</p>
                    <a href="donations.php" class="btn btn-success btn-sm">View Donations</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests Table -->
    <div class="mt-5">
        <h4><i class="fas fa-list me-2"></i>Recent Requests</h4>
        <table class="table table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Blood Group</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($recent_requests->num_rows>0){
                    while($row = $recent_requests->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo $row['blood_group']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>
                                <?php
                                if($row['status']=='pending') echo "<span class='badge badge-pending'>Pending</span>";
                                elseif($row['status']=='approved') echo "<span class='badge badge-approved'>Approved</span>";
                                else echo "<span class='badge badge-pending'>Pending</span>";
                                ?>
                            </td>
                            <td><?php echo date('d-M-Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <?php if($row['status']=='pending'){ ?>
                                <a href="requests.php?action=approved&request_id=<?php echo $row['request_id']; ?>" class="btn btn-success btn-sm"><i class="fas fa-check"></i></a>
                                <a href="requests.php?action=rejected&request_id=<?php echo $row['request_id']; ?>" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></a>
                                <?php } else { echo "-"; } ?>
                            </td>
                        </tr>
                <?php }} else { ?>
                    <tr><td colspan="6" class="text-center">No recent requests found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Donors & Recipients -->
    <div class="mt-5">
        <h4><i class="fas fa-users me-2"></i> Donors & Recipients</h4>
        <table class="table table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Blood Group</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php if($users_query->num_rows > 0){
                    while($row = $users_query->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo ucfirst($row['role']); ?></td>
                            <td><?php echo $row['blood_group'] ?? '-'; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                        </tr>
                <?php }} else { ?>
                    <tr><td colspan="4" class="text-center">No donors/recipients found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
