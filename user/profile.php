<?php
include "../includes/auth_check.php";
include "../includes/db.php";



$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$msg = '';

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE user_id=?");
    $stmt->bind_param("sssi", $name, $email, $phone, $user_id);

    if($stmt->execute()){
        $msg = "Profile updated successfully!";
    } else {
        $msg = "Error: " . $stmt->error;
    }
}

$result = $conn->query("SELECT * FROM users WHERE user_id=$user_id");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <?php
        if($role=='user') echo '<link rel="stylesheet" href="../assets/css/user.css">';
        elseif($role=='hospital') echo '<link rel="stylesheet" href="../assets/css/hospital.css">';
        elseif($role=='admin') echo '<link rel="stylesheet" href="../assets/css/admin.css">';
    ?>
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-5">
    <h3>My Profile</h3>
    <?php if($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>">
        </div>

        <button type="submit" name="update" class="btn btn-custom">Update Profile</button>
    </form>
</div>

<?php include "footer.php"; ?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
