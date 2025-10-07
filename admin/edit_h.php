<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: manage_hospitals.php");
    exit();
}

$hospital_id = intval($_GET['id']);

// Fetch hospital details
$result = $conn->query("SELECT * FROM users WHERE user_id=$hospital_id AND role='hospital'");
if ($result->num_rows == 0) {
    header("Location: manage_hospitals.php");
    exit();
}
$hospital = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $status = $conn->real_escape_string($_POST['status']);

    $update = $conn->query("UPDATE users 
                            SET name='$name', email='$email', phone='$phone', status='$status'
                            WHERE user_id=$hospital_id AND role='hospital'");

    if ($update) {
        $_SESSION['message'] = "Hospital updated successfully!";
        $_SESSION['alert_type'] = "success";
        header("Location: manage_hospitals.php");
        exit();
    } else {
        $error = "Failed to update hospital.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Hospital</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-4">
    <h3><i class="fas fa-hospital me-2"></i>Edit Hospital</h3>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Hospital Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($hospital['name']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($hospital['email']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($hospital['phone']); ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="pending" <?php if($hospital['status']=='pending') echo 'selected'; ?>>Pending</option>
                <option value="approved" <?php if($hospital['status']=='approved') echo 'selected'; ?>>Approved</option>
                <option value="rejected" <?php if($hospital['status']=='rejected') echo 'selected'; ?>>Rejected</option>
            </select>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="manage_hospitals.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
