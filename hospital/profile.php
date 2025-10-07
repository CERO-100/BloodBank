<?php
include "../includes/auth_check.php";
check_role('hospital');
include "../includes/db.php";

$hospital_id = $_SESSION['user_id'];

// Fetch hospital details
$hospital = $conn->query("SELECT * FROM users WHERE user_id=$hospital_id")->fetch_assoc();

// Handle profile update
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, location=? WHERE user_id=?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $location, $hospital_id);

    if($stmt->execute()){
        $msg = "Profile updated successfully!";
        $hospital = $conn->query("SELECT * FROM users WHERE user_id=$hospital_id")->fetch_assoc(); // Refresh data
    } else {
        $msg = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hospital Profile</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/hospital.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
.container { margin-top: 60px; }
.profile-card { background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
.profile-card h3 { color: #C41E3A; margin-bottom: 20px; }
.form-control { border-radius: 8px; }
.btn-custom { background-color: #C41E3A; color: #fff; border-radius: 8px; border: none; transition: 0.3s; }
.btn-custom:hover { background-color: #900020; }
</style>
</head>
<body>
<?php include "header.php"; ?>

<div class="container">
    <div class="profile-card mx-auto" style="max-width:600px;">
        <h3><i class="fas fa-user me-2"></i>My Profile</h3>

        <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Hospital Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($hospital['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($hospital['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($hospital['phone']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($hospital['location']); ?>" required>
            </div>

            <button type="submit" name="update" class="btn btn-custom mt-2"><i class="fas fa-save me-1"></i> Update Profile</button>
        </form>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
s
</body>
</html>
