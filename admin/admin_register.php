<?php
include "../includes/db.php";

$msg = '';

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password){
        $msg = "Passwords do not match!";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO admins (name,email,password) VALUES (?,?,?)");
        $stmt->bind_param("sss",$name,$email,$hash);

        if($stmt->execute()){
            $msg = "Admin registered successfully!";
        } else {
            $msg = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Registration</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="container mt-5" style="max-width:500px;">
<h3 class="mb-4">Admin Registration</h3>
<?php if($msg) echo "<div class='alert alert-info'>$msg</div>"; ?>
<form method="POST">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
    <p class="mt-3 text-center">Already registered? <a href="admin_login.php">Login here</a></p>
</form>
</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
