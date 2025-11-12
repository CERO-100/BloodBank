<?php

/**
 * Unified Header Component for Blood Bank System
 * Supports: Admin, Hospital, User, and Public pages
 * 
 * Usage: include 'includes/unified_header.php';
 * Requires: Session with 'role', 'user_id', 'name'
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determine user role and name
$role = $_SESSION['role'] ?? 'public';
$user_name = $_SESSION['name'] ?? ($_SESSION['admin_name'] ?? 'Guest');
$user_id = $_SESSION['user_id'] ?? 0;

// Base path detection
$base_path = '';
if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) {
    $base_path = '../';
} elseif (strpos($_SERVER['PHP_SELF'], '/hospital/') !== false) {
    $base_path = '../';
} elseif (strpos($_SERVER['PHP_SELF'], '/user/') !== false) {
    $base_path = '../';
}

// Get notification counts based on role
$notification_count = 0;
if ($role == 'hospital' && $user_id > 0) {
    include_once $base_path . "includes/db.php";
    // SECURITY FIX: Use prepared statements
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM requests WHERE hospital_id=? AND status='pending'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $req_count = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM donations WHERE hospital_id=? AND status='pending'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $don_count = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

    $notification_count = $req_count + $don_count;
} elseif ($role == 'user' && $user_id > 0) {
    include_once $base_path . "includes/db.php";
    // SECURITY FIX: Use prepared statement
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM notifications WHERE user_id=? AND status='unread'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $notification_count = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
} elseif ($role == 'admin' && $user_id > 0) {
    include_once $base_path . "includes/db.php";
    // SECURITY FIX: Use correct column names and prepared statements
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE status='pending' AND role='user'");
    $stmt->execute();
    $pending_users = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE status='pending' AND role='hospital'");
    $stmt->execute();
    $pending_hospitals = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

    $notification_count = $pending_users + $pending_hospitals;
}

// Navigation items by role
$nav_items = [];

if ($role == 'admin') {
    $nav_items = [
        ['url' => 'admin.php', 'icon' => 'tachometer-alt', 'label' => 'Dashboard'],
        ['url' => 'manage_users.php', 'icon' => 'users', 'label' => 'Users'],
        ['url' => 'manage_hospitals.php', 'icon' => 'hospital', 'label' => 'Hospitals'],
        ['url' => 'view_requests.php', 'icon' => 'hand-holding-medical', 'label' => 'Requests'],
        ['url' => 'reports.php', 'icon' => 'chart-bar', 'label' => 'Reports'],
    ];
} elseif ($role == 'hospital') {
    $nav_items = [
        ['url' => 'dashboard.php', 'icon' => 'tachometer-alt', 'label' => 'Dashboard'],
        ['url' => 'stock.php', 'icon' => 'boxes', 'label' => 'Stock'],
        ['url' => 'requests.php', 'icon' => 'hand-holding-medical', 'label' => 'Requests'],
        ['url' => 'donations.php', 'icon' => 'donate', 'label' => 'Donations'],
        ['url' => 'donors.php', 'icon' => 'users', 'label' => 'Donors'],
    ];
} elseif ($role == 'user') {
    $nav_items = [
        ['url' => 'dashboard.php', 'icon' => 'tachometer-alt', 'label' => 'Dashboard'],
        ['url' => 'search_blood.php', 'icon' => 'search', 'label' => 'Search Blood'],
        ['url' => 'request_blood.php', 'icon' => 'hand-holding-medical', 'label' => 'Request Blood'],
        ['url' => 'donate_blood.php', 'icon' => 'donate', 'label' => 'Donate Blood'],
        ['url' => 'my_requests.php', 'icon' => 'list', 'label' => 'My Requests'],
    ];
} else {
    // Public pages
    $nav_items = [
        ['url' => 'index.php', 'icon' => 'home', 'label' => 'Home'],
        ['url' => 'about.php', 'icon' => 'info-circle', 'label' => 'About'],
        ['url' => 'contact.php', 'icon' => 'envelope', 'label' => 'Contact'],
    ];
}

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <nav class="bb-navbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between w-100">

                <!-- Logo & Brand -->
                <a class="navbar-brand" href="<?php echo $role == 'public' ? 'index.php' : 'dashboard.php'; ?>">
                    <i class="fas fa-tint"></i>
                    <span>BloodBank</span>
                    <?php if ($role != 'public'): ?>
                        <span style="font-size: 0.85rem; opacity: 0.9;"> | <?php echo ucfirst($role); ?></span>
                    <?php endif; ?>
                </a>

                <!-- Mobile Menu Toggle -->
                <button class="d-lg-none" id="mobileMenuToggle" style="background: rgba(255,255,255,0.2); border: none; color: white; padding: 8px 12px; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Desktop Navigation -->
                <div class="d-none d-lg-flex align-items-center gap-md" id="mainNav">

                    <!-- Nav Links -->
                    <div class="d-flex align-items-center gap-sm">
                        <?php foreach ($nav_items as $item): ?>
                            <a href="<?php echo $item['url']; ?>"
                                class="nav-link <?php echo ($current_page == $item['url']) ? 'active' : ''; ?>">
                                <i class="fas fa-<?php echo $item['icon']; ?>"></i>
                                <span class="d-none d-xl-inline ms-1"><?php echo $item['label']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- Right Side Items -->
                    <div class="d-flex align-items-center gap-sm">

                        <?php if ($role != 'public'): ?>

                            <!-- Notifications -->
                            <a href="notifications.php" class="nav-link position-relative">
                                <i class="fas fa-bell"></i>
                                <?php if ($notification_count > 0): ?>
                                    <span class="notification-badge"><?php echo $notification_count; ?></span>
                                <?php endif; ?>
                            </a>

                            <!-- Profile Dropdown -->
                            <div class="dropdown">
                                <button class="nav-link dropdown-toggle d-flex align-items-center gap-sm"
                                    type="button"
                                    id="profileDropdown"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    style="background: transparent; border: none; cursor: pointer;">
                                    <i class="fas fa-user-circle" style="font-size: 1.25rem;"></i>
                                    <span class="d-none d-xxl-inline"><?php echo htmlspecialchars($user_name); ?></span>
                                    <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="profile.php">
                                            <i class="fas fa-user me-2"></i>Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="settings.php">
                                            <i class="fas fa-cog me-2"></i>Settings
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="<?php echo $base_path; ?>logout.php">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        <?php else: ?>

                            <!-- Public Login/Register -->
                            <div class="dropdown">
                                <button class="nav-link dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="login.php">User Login</a></li>
                                    <li><a class="dropdown-item" href="login.php">Hospital Login</a></li>
                                    <li><a class="dropdown-item" href="admin/admin_login.php">Admin Login</a></li>
                                </ul>
                            </div>

                            <div class="dropdown">
                                <button class="nav-link dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-user-plus me-1"></i>Register
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="register.php">User Register</a></li>
                                    <li><a class="dropdown-item" href="hospital/hospital_register.php">Hospital Register</a></li>
                                </ul>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- Mobile Menu (Slide-in) -->
    <div id="mobileMenu" style="position: fixed; top: 0; left: -100%; width: 280px; height: 100vh; background: white; box-shadow: 2px 0 10px rgba(0,0,0,0.1); z-index: 9999; transition: 0.3s; padding: 20px; overflow-y: auto;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--primary-red);">Menu</h5>
            <button id="closeMobileMenu" style="background: none; border: none; font-size: 1.5rem; color: var(--primary-red); cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <?php if ($role != 'public'): ?>
            <div class="mb-4 pb-3" style="border-bottom: 2px solid var(--light-gray);">
                <div class="d-flex align-items-center gap-sm mb-2">
                    <i class="fas fa-user-circle" style="font-size: 2rem; color: var(--primary-red);"></i>
                    <div>
                        <div style="font-weight: 600;"><?php echo htmlspecialchars($user_name); ?></div>
                        <div style="font-size: 0.85rem; color: var(--medium-gray);"><?php echo ucfirst($role); ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="d-flex flex-column gap-sm">
            <?php foreach ($nav_items as $item): ?>
                <a href="<?php echo $item['url']; ?>"
                    style="padding: 12px 16px; border-radius: 8px; color: var(--text-dark); font-weight: 500; display: flex; align-items: center; gap: 12px; <?php echo ($current_page == $item['url']) ? 'background: rgba(196,30,58,0.1); color: var(--primary-red);' : ''; ?>">
                    <i class="fas fa-<?php echo $item['icon']; ?>" style="width: 20px;"></i>
                    <span><?php echo $item['label']; ?></span>
                </a>
            <?php endforeach; ?>

            <?php if ($role != 'public'): ?>
                <a href="notifications.php" style="padding: 12px 16px; border-radius: 8px; color: var(--text-dark); font-weight: 500; display: flex; align-items: center; gap: 12px; position: relative;">
                    <i class="fas fa-bell" style="width: 20px;"></i>
                    <span>Notifications</span>
                    <?php if ($notification_count > 0): ?>
                        <span style="margin-left: auto; background: var(--danger); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;"><?php echo $notification_count; ?></span>
                    <?php endif; ?>
                </a>

                <hr style="margin: 8px 0;">

                <a href="profile.php" style="padding: 12px 16px; border-radius: 8px; color: var(--text-dark); font-weight: 500; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-user" style="width: 20px;"></i>
                    <span>Profile</span>
                </a>

                <a href="settings.php" style="padding: 12px 16px; border-radius: 8px; color: var(--text-dark); font-weight: 500; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-cog" style="width: 20px;"></i>
                    <span>Settings</span>
                </a>

                <a href="<?php echo $base_path; ?>logout.php" style="padding: 12px 16px; border-radius: 8px; color: var(--danger); font-weight: 600; display: flex; align-items-center; gap: 12px;">
                    <i class="fas fa-sign-out-alt" style="width: 20px;"></i>
                    <span>Logout</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenuOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9998; display: none;"></div>

    <script>
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.style.left = '0';
                mobileMenuOverlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        }

        if (closeMobileMenu) {
            closeMobileMenu.addEventListener('click', () => {
                mobileMenu.style.left = '-100%';
                mobileMenuOverlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        }

        if (mobileMenuOverlay) {
            mobileMenuOverlay.addEventListener('click', () => {
                mobileMenu.style.left = '-100%';
                mobileMenuOverlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        }
    </script>

    <script src="<?php echo $base_path; ?>assets/js/bootstrap.bundle.min.js"></script>