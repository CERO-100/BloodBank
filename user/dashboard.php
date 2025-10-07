<?php
include_once "../includes/auth_check.php";
check_role('user');
include "../includes/db.php";

// Get logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_name = $user['name'] ?? "User";

// Fetch total donations by this user
$stmt = $conn->prepare("SELECT COUNT(*) as total_donations FROM donations WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$donation_result = $stmt->get_result()->fetch_assoc();
$total_donations = $donation_result['total_donations'] ?? 0;

// Fetch total requests by this user
$stmt = $conn->prepare("SELECT COUNT(*) as total_requests FROM requests WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$request_result = $stmt->get_result()->fetch_assoc();
$total_requests = $request_result['total_requests'] ?? 0;

// Fetch total available blood groups (distinct)
$blood_result = $conn->query("SELECT COUNT(DISTINCT blood_group) as available_groups FROM blood_stock WHERE quantity > 0");
$blood_data = $blood_result->fetch_assoc();
$available_groups = $blood_data['available_groups'] ?? 0;

// Fetch unread notifications count
$notification_stmt = $conn->prepare("SELECT COUNT(*) as unread_notifications FROM notifications WHERE user_id = ? AND status = 'unread'");
$notification_stmt->bind_param("i", $user_id);
$notification_stmt->execute();
$notification_result = $notification_stmt->get_result()->fetch_assoc();
$unread_notifications = $notification_result['unread_notifications'] ?? 0;

// Fetch community requests count (excluding user's own requests)
$community_requests_stmt = $conn->prepare("SELECT COUNT(*) as community_requests FROM requests WHERE hospital_id IS NULL AND user_id != ? AND status = 'pending'");
$community_requests_stmt->bind_param("i", $user_id);
$community_requests_stmt->execute();
$community_result = $community_requests_stmt->get_result()->fetch_assoc();
$community_requests = $community_result['community_requests'] ?? 0;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
        }

        a {
            text-decoration: none;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 220px;
            background-color: #1e1e2f;
            padding-top: 60px;
            transition: 0.3s;
        }

        .sidebar a {
            display: block;
            color: #cfd8dc;
            padding: 15px 20px;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #2a2a40;
            border-left: 4px solid #ff4c60;
            color: #fff;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 40px;
        }

        /* Top Cards */
        .card-stats {
            border-radius: 12px;
            padding: 25px;
            color: #fff;
            transition: 0.3s;
        }

        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .bg-donor {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
        }

        .bg-request {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
        }

        .bg-search {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .card-stats i {
            font-size: 2.5rem;
        }

        /* Action Cards */
        .action-card {
            border-radius: 12px;
            transition: 0.3s;
            background-color: #fff;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .action-card i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        /* Footer Tip */
        .dashboard-tip {
            background-color: #fff;
            padding: 20px;
            border-left: 5px solid #ff4c60;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>

        <a href="request_blood.php"><i class="fas fa-hand-holding-medical me-2"></i> Request Hospital</a>
        <a href="request_public.php"><i class="fas fa-hand-holding-medical me-2"></i> Request Public</a>
        <a href="donate_blood.php"><i class="fas fa-tint me-2"></i> Donate Blood</a>
        <a href="my_donations.php"><i class="fas fa-hand-holding-heart me-2"></i> My Donation</a>
        <a href="my_requests.php"><i class="fas fa-hand-holding-medical me-2"></i> My request</a>
        <a href="community_requests.php"><i class="fas fa-hands-helping me-2"></i> Community Requests</a>
        <a href="hospitals_map.php"><i class="fas fa-search-plus me-2"></i> Find Hospitals</a>

        <a href="notifications.php"><i class="fas fa-bell me-2"></i> Notifications <?php if ($unread_notifications > 0) echo "<span style='background:#ff4c60;color:white;border-radius:10px;padding:2px 8px;font-size:0.8em;margin-left:5px;'>$unread_notifications</span>"; ?></a>
        <a href="history.php"><i class="fas fa-history me-2"></i> My History</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Hello, <span class="text-primary"><?= htmlspecialchars($user_name) ?></span> 👋</h2>

        <!-- Top Stats -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card-stats bg-donor">
                    <i class="fas fa-tint"></i>
                    <h5 class="mt-3">Total Donations</h5>
                    <h3><?= $total_donations ?></h3>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card-stats bg-request">
                    <i class="fas fa-hand-holding-medical"></i>
                    <h5 class="mt-3">Requests Submitted</h5>
                    <h3><?= $total_requests ?></h3>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card-stats bg-search">
                    <i class="fas fa-search"></i>
                    <h5 class="mt-3">Available Blood Groups</h5>
                    <h3><?= $available_groups ?></h3>
                </div>
            </div>
        </div>

        <!-- Second Row of Stats -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card-stats" style="background: linear-gradient(135deg,#667eea,#764ba2);">
                    <i class="fas fa-bell"></i>
                    <h5 class="mt-3">Unread Notifications</h5>
                    <h3><?= $unread_notifications ?></h3>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card-stats" style="background: linear-gradient(135deg,#ffeaa7,#fab1a0);">
                    <i class="fas fa-hands-helping"></i>
                    <h5 class="mt-3">Community Requests</h5>
                    <h3><?= $community_requests ?></h3>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="action-card">
                    <i class="fas fa-search text-primary"></i>
                    <h5 class="mt-3">History</h5>
                    <p class="text-muted">Quickly find your History.</p>
                    <a href="history.php" class="btn btn-outline-primary btn-sm">Go</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="action-card">
                    <i class="fas fa-hand-holding-medical text-danger"></i>
                    <h5 class="mt-3">Request Blood</h5>
                    <p class="text-muted">Submit a blood request and track approval status.</p>
                    <a href="request_blood.php" class="btn btn-outline-danger btn-sm">Go</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="action-card">
                    <i class="fas fa-tint text-success"></i>
                    <h5 class="mt-3">Donate Blood</h5>
                    <p class="text-muted">Pledge a donation and help hospitals maintain stock.</p>
                    <a href="donate_blood.php" class="btn btn-outline-success btn-sm">Go</a>
                </div>
            </div>
        </div>

        <!-- Second Row of Action Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="action-card">
                    <i class="fas fa-hands-helping text-info"></i>
                    <h5 class="mt-3">Community Requests</h5>
                    <p class="text-muted">View and respond to blood requests from other community members.</p>
                    <a href="community_requests.php" class="btn btn-outline-info btn-sm">View Requests</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="action-card">
                    <i class="fas fa-users text-warning"></i>
                    <h5 class="mt-3">Public Request</h5>
                    <p class="text-muted">Request blood directly from community donors.</p>
                    <a href="request_public.php" class="btn btn-outline-warning btn-sm">Make Request</a>
                </div>
            </div>
        </div>

        <!-- Informative Tip -->
        <div class="dashboard-tip">
            <i class="fas fa-info-circle me-2"></i>
            Regular blood donations save lives! Always check availability before requesting. Your donation helps hospitals maintain sufficient blood stock for emergencies.
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>