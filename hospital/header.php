<?php
if(session_status() == PHP_SESSION_NONE) session_start();
include_once "../includes/db.php";
$hospital_id = $_SESSION['user_id'] ?? 0;

// Counts
$req_count = $conn->query("SELECT COUNT(*) as total FROM requests WHERE hospital_id=$hospital_id AND status='pending'")->fetch_assoc()['total'] ?? 0;
$don_count = $conn->query("SELECT COUNT(*) as total FROM donations WHERE hospital_id=$hospital_id AND status='pending'")->fetch_assoc()['total'] ?? 0;
$name = $_SESSION['name'] ?? 'Hospital';
?>

<!-- Modern Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm fixed-top custom-navbar">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="dashboard.php">
            <i class="fas fa-tint me-2"></i>
            <span>BloodBank | Hospital</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#hospitalNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse" id="hospitalNavbar">
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- Requests -->
                <li class="nav-item me-3 position-relative">
                    <a class="nav-link" href="requests.php">
                        <i class="fas fa-hand-holding-medical me-1"></i>Requests
                        <?php if($req_count > 0): ?>
                        <span class="badge bg-warning rounded-pill notif-badge"><?php echo $req_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <!-- Donations -->
                <li class="nav-item me-3 position-relative">
                    <a class="nav-link" href="donations.php">
                        <i class="fas fa-donate me-1"></i>Donations
                        <?php if($don_count > 0): ?>
                        <span class="badge bg-success rounded-pill notif-badge"><?php echo $don_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" 
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i><?php echo htmlspecialchars($name); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger fw-bold" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- Add space for fixed navbar -->
<div style="padding-top: 75px;"></div>

<style>
/* Glassmorphism Navbar */
.custom-navbar {
    background: rgba(220, 53, 69, 0.9); /* Bootstrap Danger with transparency */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-bottom: 2px solid rgba(255,255,255,0.2);
}

.navbar-brand {
    font-size: 1.3rem;
    background: linear-gradient(45deg, #fff, #ffe6e9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.navbar-nav .nav-link {
    color: #fff;
    font-weight: 500;
    position: relative;
    transition: all 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: #ffe6e9;
    transform: translateY(-2px);
}

.notif-badge {
    font-size: 0.7rem;
    position: absolute;
    top: 2px;
    right: -8px;
    padding: 4px 6px;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.2); opacity: 0.8; }
  100% { transform: scale(1); opacity: 1; }
}
</style>
