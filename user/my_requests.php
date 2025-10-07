<?php
include_once "../includes/auth_check.php";
check_role('user');
include_once "../includes/db.php";



$user_id = $_SESSION['user_id'];
$query = "SELECT r.request_id, u.name AS hospital_name, r.blood_group, r.quantity, r.status, r.created_at 
          FROM requests r
          JOIN users u ON r.hospital_id = u.user_id
          WHERE r.user_id = $user_id
          ORDER BY r.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Requests</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/user.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-5">
    <h3>My Blood Requests</h3>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Hospital</th>
                <th>Blood Group</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $row['hospital_name']; ?></td>
                        <td><?php echo $row['blood_group']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>
                            <?php 
                                if($row['status']=='pending') echo "<span class='badge bg-warning'>Pending</span>";
                                elseif($row['status']=='approved') echo "<span class='badge bg-success'>Approved</span>";
                                else echo "<span class='badge bg-danger'>Rejected</span>";
                            ?>
                        </td>
                        <td><?php echo date('d-M-Y', strtotime($row['created_at'])); ?></td>
                    </tr>
            <?php } } else { ?>
                <tr><td colspan="5" class="text-center">No requests found</td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
