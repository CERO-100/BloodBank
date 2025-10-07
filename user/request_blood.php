<?php
include "../includes/auth_check.php";
check_role('user');
include "../includes/db.php";

$msg = "";

// Handle blood request submission
if(isset($_POST['request'])){
    $user_id = $_SESSION['user_id'];
    $hospital_id = $_POST['hospital_id'];
    $blood_group = $_POST['blood_group'];
    $quantity = $_POST['quantity'];
    $status = 'pending';

    $stmt = $conn->prepare("INSERT INTO requests (user_id, hospital_id, blood_group, quantity, status) VALUES (?,?,?,?,?)");
    if(!$stmt){
        die("Database Error: " . $conn->error);
    }
    $stmt->bind_param("iissi", $user_id, $hospital_id, $blood_group, $quantity, $status);

    if($stmt->execute()){
        $msg = "✅ Your blood request has been submitted successfully!";
    } else {
        $msg = "❌ Error: " . $stmt->error;
    }
}

// Fetch hospitals
$hospitals = $conn->query("SELECT user_id, name, location, phone, email FROM users WHERE role='hospital'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Request Blood to Hospital</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }
.container { margin-top: 40px; }
.hospital-card {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}
.hospital-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.2);
}
.hospital-card h5 {
    font-weight: 700;
    color: #C41E3A;
    margin-bottom: 10px;
}
.blood-badge {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 8px;
    background: #f8f9fa;
    margin: 3px;
    font-size: 14px;
    border: 1px solid #ddd;
}
.action-icons button, .action-icons a {
    margin-right: 10px;
    font-size: 16px;
    padding: 8px 12px;
    border-radius: 50%;
    transition: 0.3s;
    border: none;
    cursor: pointer;
}
.action-icons .request {
    color: #fff;
    background: #C41E3A;
}
.action-icons .call {
    color: #28a745;
    border: 1px solid #28a745;
    background: #fff;
}
.action-icons .mail {
    color: #007bff;
    border: 1px solid #007bff;
    background: #fff;
}
</style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">
    <h3 class="mb-4 text-center"><i class="fas fa-clinic-medical me-2"></i>Available Hospitals</h3>
    <div class="row">
        <?php while($h = $hospitals->fetch_assoc()): ?>
        <div class="col-md-6">
            <div class="hospital-card">
                <h5><i class="fas fa-hospital me-2"></i><?= htmlspecialchars($h['name']); ?></h5>
                <p><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($h['location']); ?></p>
                <p><i class="fas fa-phone me-2"></i><?= htmlspecialchars($h['phone']); ?></p>
                <p><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($h['email']); ?></p>

                <?php
                $stock = $conn->query("SELECT blood_group, quantity FROM blood_stock WHERE hospital_id=" . (int)$h['user_id']);
                ?>
                <div class="blood-stock">
                    <strong>Stock:</strong><br>
                    <?php if($stock->num_rows > 0): ?>
                        <?php while($s = $stock->fetch_assoc()): ?>
                            <span class="blood-badge"><?= htmlspecialchars($s['blood_group']); ?> : <?= htmlspecialchars($s['quantity']); ?> Units</span>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <span class="text-muted">No stock available</span>
                    <?php endif; ?>
                </div>

                <div class="action-icons mt-3">
                    <button type="button" class="request" onclick="openRequestPopup(<?= $h['user_id']; ?>)" title="Request">
                        <i class="fas fa-hand-holding-medical"></i>
                    </button>
                    <a href="tel:<?= $h['phone']; ?>" class="call"><i class="fas fa-phone"></i></a>
                    <a href="mailto:<?= $h['email']; ?>" class="mail"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function openRequestPopup(hospitalId) {
  Swal.fire({
    title: 'Request Blood',
    html: `
      <form id="bloodRequestForm" method="POST">
        <input type="hidden" name="hospital_id" value="${hospitalId}">
        <div class="mb-3 text-start">
          <label class="form-label fw-bold">Select Blood Group</label>
          <select name="blood_group" class="form-select" required>
            <option value="">Choose...</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
          </select>
        </div>
        <div class="mb-3 text-start">
          <label class="form-label fw-bold">Quantity (Units)</label>
          <input type="number" name="quantity" class="form-control" min="1" max="10" required>
        </div>
        <input type="hidden" name="request" value="1">
      </form>
    `,
    showCancelButton: true,
    confirmButtonText: 'Submit Request',
    confirmButtonColor: '#C41E3A',
    focusConfirm: false,
    preConfirm: () => {
      const form = document.getElementById('bloodRequestForm');
      if (!form.reportValidity()) {
        Swal.showValidationMessage(`Please fill all fields correctly.`);
        return false;
      }
      form.submit(); // Submit to same PHP file
    }
  });
}
</script>

<?php if($msg): ?>
<script>
Swal.fire({
  title: 'Success!',
  text: "<?= $msg ?>",
  icon: 'success',
  confirmButtonColor: '#C41E3A'
});
</script>
<?php endif; ?>

</body>
</html>
