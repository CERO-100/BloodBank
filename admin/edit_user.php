<?php
include "../includes/admin_auth.php";
check_admin_role('admin');
include "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = intval($_GET['id']);
$user = $conn->query("SELECT * FROM users WHERE user_id=$user_id")->fetch_assoc();

if (!$user) {
    header("Location: manage_users.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = $conn->real_escape_string($_POST['name']);
    $email       = $conn->real_escape_string($_POST['email']);
    $blood_group = $conn->real_escape_string($_POST['blood_group']);
    $status      = $conn->real_escape_string($_POST['status']);

    $conn->query("UPDATE users SET 
                    name='$name',
                    email='$email',
                    blood_group='$blood_group',
                    status='$status'
                  WHERE user_id=$user_id");

    header("Location: manage_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        background: #f9f9f9;
    }
    .card {
        border-radius: 12px;
    }
</style>
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit User</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Blood Group</label>
                    <select name="blood_group" class="form-select" required>
                        <?php
                        $groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                        foreach ($groups as $g) {
                            $selected = ($user['blood_group'] == $g) ? 'selected' : '';
                            echo "<option value='$g' $selected>$g</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" <?= ($user['status']=='pending')?'selected':''; ?>>Pending</option>
                        <option value="approved" <?= ($user['status']=='approved')?'selected':''; ?>>Approved</option>
                        <option value="rejected" <?= ($user['status']=='rejected')?'selected':''; ?>>Rejected</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="manage_users.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
