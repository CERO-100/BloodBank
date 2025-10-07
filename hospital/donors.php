<?php
include "../includes/auth_check.php";
check_role('hospital');


include "../includes/db.php";

// Fetch all donors (users with role 'user')
$donors = $conn->query("
    SELECT user_id, name, blood_group, email, phone, location
    FROM users
    WHERE role='user'
    ORDER BY name ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Donors List</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/hospital.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-4">
    <h2><i class="fas fa-users me-2"></i>Donors</h2>

    <div class="row mt-3">
        <?php if($donors->num_rows > 0){
            while($row = $donors->fetch_assoc()){ ?>
                <div class="col-md-4">
                    <div class="card donor-card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-user me-2"></i><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text">
                                <strong>Blood Group:</strong> <span class="badge bg-danger"><?php echo $row['blood_group']; ?></span><br>
                                <strong>Email:</strong> <?php echo $row['email']; ?><br>
                                <strong>Phone:</strong> <?php echo $row['phone']; ?><br>
                                <strong>Location:</strong> <?php echo $row['location']; ?>
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="mailto:<?php echo $row['email']; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-envelope"></i> Email</a>
                            <a href="tel:<?php echo $row['phone']; ?>" class="btn btn-outline-success btn-sm"><i class="fas fa-phone"></i> Call</a>
                            <a href="donations.php?donor_id=<?php echo $row['user_id']; ?>" class="btn btn-outline-warning btn-sm"><i class="fas fa-history"></i> Donations</a>
                        </div>
                    </div>
                </div>
        <?php }} else { ?>
            <div class="col-12 text-center mt-5">
                <p class="text-muted">No donors found.</p>
            </div>
        <?php } ?>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
