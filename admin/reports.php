<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

// Example: blood stock counts by blood group
$stock_data = $conn->query("SELECT blood_group, SUM(quantity) as total FROM blood_stock GROUP BY blood_group");
$blood_groups = [];
$quantities = [];
while($row=$stock_data->fetch_assoc()){
    $blood_groups[] = $row['blood_group'];
    $quantities[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reports</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-4">
<h3><i class="fas fa-chart-bar me-2"></i>Reports</h3>
<canvas id="stockChart" height="100"></canvas>
</div>

<script>
const ctx = document.getElementById('stockChart').getContext('2d');
new Chart(ctx,{
    type: 'bar',
    data:{
        labels: <?php echo json_encode($blood_groups); ?>,
        datasets:[{
            label:'Blood Stock',
            data: <?php echo json_encode($quantities); ?>,
            backgroundColor:'#C41E3A'
        }]
    },
    options:{ responsive:true, plugins:{legend:{display:false}} }
});
</script>

<?php include "footer.php"; ?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
