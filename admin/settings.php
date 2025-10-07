<?php
include_once "../includes/admin_auth.php";
check_admin_role('admin');
include_once "../includes/db.php";

// Fetch admin details
$admin_id = $_SESSION['user_id'];
$admin = $conn->query("SELECT * FROM users WHERE user_id=$admin_id")->fetch_assoc();

// Update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $admin['password'];

    $conn->query("UPDATE users SET name='$name', email='$email', password='$password' WHERE user_id=$admin_id");
    $_SESSION['name'] = $name; // update session too
    header("Location: settings.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Settings</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .settings-card {
        max-width: 600px;
        margin: auto;
        margin-top: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .settings-card .card-header {
        background: linear-gradient(135deg,#dc3545,#ff6b6b);
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
</style>
</head>
<body>
<?php include "header.php"; ?>

<div class="container">
    <div class="card settings-card">
        <div class="card-header">
            <i class="fas fa-cog me-2"></i> Admin Settings
        </div>
        <div class="card-body">
            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Profile updated successfully!</div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user me-1"></i>Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($admin['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-envelope me-1"></i>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock me-1"></i>New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter new password">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger"><i class="fas fa-save me-1"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
