<?php
session_start();
include "../includes/db.php";
include "../includes/auth_check.php";
check_role('hospital'); // change to 'user' if for user

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update Profile
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $update = $conn->prepare("UPDATE users SET name=?, phone=? WHERE user_id=?");
    $update->bind_param("ssi", $name, $phone, $user_id);

    if ($update->execute()) {
        $_SESSION['name'] = $name;
        $success = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile.";
    }
}

// Update Password
if (isset($_POST['update_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    $check = $conn->prepare("SELECT password FROM users WHERE user_id=?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $hashed = $check->get_result()->fetch_assoc()['password'];

    if (password_verify($current, $hashed)) {
        $new_hashed = password_hash($new, PASSWORD_DEFAULT);
        $update_pass = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
        $update_pass->bind_param("si", $new_hashed, $user_id);
        $update_pass->execute();
        $success = "Password updated successfully!";
    } else {
        $error = "Current password is incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | Blood Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include "header.php"; ?> <!-- Keep your navbar -->

<div class="container mt-5 pt-4">
    <h2 class="mb-4"><i class="fas fa-cog me-2"></i>Settings</h2>

    <?php if (isset($success)) { ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php } ?>
    <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php } ?>

    <div class="row">
        <!-- Profile Update -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-user-edit me-2"></i>Update Profile
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']); ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-danger w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Password Update -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-lock me-2"></i>Change Password
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <button type="submit" name="update_password" class="btn btn-dark w-100">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
s