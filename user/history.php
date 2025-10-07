<?php
include "../includes/auth_check.php";
check_role('user');
include "../includes/db.php";



$user_id = $_SESSION['user_id'];

// Fetch user requests
$requests = $conn->query("SELECT r.*, h.name AS hospital_name, h.location AS hospital_location 
                          FROM requests r 
                          JOIN users h ON r.hospital_id = h.user_id 
                          WHERE r.user_id=$user_id 
                          ORDER BY r.created_at DESC");

// Fetch user donations
$donations = $conn->query("SELECT d.*, h.name AS hospital_name, h.location AS hospital_location 
                           FROM donations d 
                           JOIN users h ON d.hospital_id = h.user_id 
                           WHERE d.user_id=$user_id 
                           ORDER BY d.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>History</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/user.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }

.container { margin-top: 60px; margin-bottom: 50px; }

h3 { font-weight: 700; color: #C41E3A; margin-bottom: 25px; text-align: center; }

/* Cards */
.card-history {
    background: linear-gradient(145deg,#ffffff,#f8f9fa);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card-history:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.2);
}

.card-history h5 {
    color: #C41E3A;
    font-weight: 700;
    margin-bottom: 10px;
}

.card-history p {
    margin-bottom: 5px;
    color: #555;
}

/* Status badges */
.badge-pending { background-color: #ffc107; color: #fff; }
.badge-approved { background-color: #28a745; color: #fff; }
.badge-rejected { background-color: #dc3545; color: #fff; }

/* Section titles */
.section-title {
    font-weight: 600;
    color: #C41E3A;
    margin-bottom: 20px;
    border-bottom: 2px solid #C41E3A;
    padding-bottom: 5px;
}

/* Responsive adjustments */
@media(max-width:768px){
    .card-history {
        padding: 20px;
    }
}
</style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <h3><i class="fas fa-history me-2"></i>My Blood History</h3>

    <!-- Requests Section -->
    <div class="mb-5">
        <h4 class="section-title"><i class="fas fa-hand-holding-medical me-2"></i>Requests</h4>
        <?php if($requests->num_rows > 0){
            while($row = $requests->fetch_assoc()){ 
                $status_class = $row['status']=='pending' ? 'badge-pending' : ($row['status']=='approved' ? 'badge-approved' : 'badge-rejected');
            ?>
            <div class="card-history">
                <h5><?php echo htmlspecialchars($row['hospital_name']); ?> - <?php echo htmlspecialchars($row['hospital_location']); ?></h5>
                <p><i class="fas fa-tint me-1"></i> Blood Group: <strong><?php echo $row['blood_group']; ?></strong></p>
                <p><i class="fas fa-boxes me-1"></i> Quantity: <strong><?php echo $row['quantity']; ?> Units</strong></p>
                <p>Status: <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></span></p>
                <p><i class="fas fa-calendar-alt me-1"></i> Requested on: <?php echo date('d M Y', strtotime($row['created_at'])); ?></p>
            </div>
        <?php } } else { ?>
            <p class="text-center text-muted">No requests found.</p>
        <?php } ?>
    </div>

    <!-- Donations Section -->
    <div>
        <h4 class="section-title"><i class="fas fa-hand-holding-heart me-2"></i>Donations</h4>
        <?php if($donations->num_rows > 0){
            while($row = $donations->fetch_assoc()){ 
                $status_class = $row['status']=='pending' ? 'badge-pending' : ($row['status']=='approved' ? 'badge-approved' : 'badge-rejected');
            ?>
            <div class="card-history">
                <h5><?php echo htmlspecialchars($row['hospital_name']); ?> - <?php echo htmlspecialchars($row['hospital_location']); ?></h5>
                <p><i class="fas fa-tint me-1"></i> Blood Group: <strong><?php echo $row['blood_group']; ?></strong></p>
                <p><i class="fas fa-boxes me-1"></i> Quantity: <strong><?php echo $row['quantity']; ?> Units</strong></p>
                <p>Status: <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></span></p>
                <p><i class="fas fa-calendar-alt me-1"></i> Donated on: <?php echo date('d M Y', strtotime($row['created_at'])); ?></p>
            </div>
        <?php } } else { ?>
            <p class="text-center text-muted">No donations found.</p>
        <?php } ?>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
