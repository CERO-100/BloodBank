<?php
if(session_status() == PHP_SESSION_NONE) session_start();
?>
<nav class="navbar navbar-expand-lg shadow-sm sticky-top" style="background: linear-gradient(90deg, #C41E3A, #8B0000);">
    <div class="container-fluid">
        <!-- Logo + Brand -->
        <a class="navbar-brand d-flex align-items-center fw-bold text-white" href="admin.php">
            
            <span><i class="fas fa-tint me-1"></i> BloodBank <span class="text-warning">Admin</span></span>
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Links -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item mx-1">
                    <a class="nav-link text-white" href="admin.php"><i class="fas fa-tachometer-alt me-1"></i>Dashboard</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link text-white" href="manage_hospitals.php"><i class="fas fa-hospital me-1"></i>Hospitals</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link text-white" href="manage_users.php"><i class="fas fa-users me-1"></i>Users</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link text-white" href="view_requests.php"><i class="fas fa-hand-holding-medical me-1"></i>Requests</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link text-white" href="reports.php"><i class="fas fa-chart-bar me-1"></i>Reports</a>
                </li>
                

                <!-- Dropdown for Profile -->
                <li class="nav-item dropdown mx-1">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i><?php echo $_SESSION['admin_name'] ?? 'Admin'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger fw-bold" href="admin_logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar-nav .nav-link {
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .navbar-nav .nav-link:hover {
        color: #FFD700 !important;
        transform: translateY(-2px);
    }
    .dropdown-menu {
        border-radius: 12px;
        overflow: hidden;
    }
    .badge {
        font-size: 0.7rem;
    }
</style>
