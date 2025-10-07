<?php
include_once "../includes/auth_check.php";
check_role('user');
include_once "../includes/db.php";



$user_id = $_SESSION['user_id'];
$query = "SELECT d.donation_id, u.name AS hospital_name, u.location AS hospital_location, d.blood_group, d.quantity, d.status, d.created_at 
          FROM donations d
          JOIN users u ON d.hospital_id = u.user_id
          WHERE d.user_id = $user_id
          ORDER BY d.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Donations</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/user.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }

.container { margin-top: 60px; margin-bottom: 50px; }

h3 { color: #C41E3A; font-weight: 700; text-align: center; margin-bottom: 30px; }

/* Card Styles */
.card-donation {
    background: linear-gradient(145deg,#ffffff,#f8f9fa);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card-donation:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.2);
}

.card-donation h5 {
    color: #C41E3A;
    font-weight: 700;
    margin-bottom: 10px;
}

.card-donation p {
    margin-bottom: 6px;
    color: #555;
    font-size: 0.95rem;
}

/* Status badges */
.badge-pending { background: linear-gradient(135deg,#ffc107,#e0a800); color:#fff; }
.badge-approved { background: linear-gradient(135deg,#28a745,#218838); color:#fff; }
.badge-rejected { background: linear-gradient(135deg,#dc3545,#b02a37); color:#fff; }

/* Section title */
.section-title {
    font-weight: 600;
    color: #C41E3A;
    margin-bottom: 20px;
    border-bottom: 2px solid #C41E3A;
    padding-bottom: 5px;
    text-align: center;
}

/* Responsive adjustments */
@media(max-width:768px){
    .card-donation {
        padding: 20px;
    }
}
</style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <h3><i class="fas fa-hand-holding-heart me-2"></i>My Blood Donations</h3>

    <?php if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $status_class = $row['status']=='pending' ? 'badge-pending' : ($row['status']=='approved' ? 'badge-approved' : 'badge-rejected');
    ?>
    <div class="card-donation">
        <h5><i class="fas fa-hospital me-2"></i><?php echo htmlspecialchars($row['hospital_name']); ?> - <?php echo htmlspecialchars($row['hospital_location']); ?></h5>
        <p><i class="fas fa-tint me-1"></i> Blood Group: <strong><?php echo $row['blood_group']; ?></strong></p>
        <p><i class="fas fa-boxes me-1"></i> Quantity: <strong><?php echo $row['quantity']; ?> Units</strong></p>
        <p>Status: <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></span></p>
        <p><i class="fas fa-calendar-alt me-1"></i> Donated on: <?php echo date('d M Y', strtotime($row['created_at'])); ?></p>
    </div>
    <?php } } else { ?>
        <p class="text-center text-muted">You have not donated blood yet.</p>
    <?php } ?>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
