<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

$admin_id = $_SESSION['user_id'];
$admin = $conn->query("SELECT * FROM users WHERE user_id=$admin_id")->fetch_assoc();

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE user_id=?");
    $stmt->bind_param("ssi",$name,$email,$admin_id);
    $stmt->execute();
    $msg = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Profile</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-4">
<h3><i class="fas fa-user me-2"></i>My Profile</h3>
<?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>

<form method="POST" class="mt-3">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $admin['name']; ?>" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo $admin['email']; ?>" required>
    </div>
    <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
</form>
</div>

<?php include "footer.php"; ?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
