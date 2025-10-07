<?php

$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';



?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?php
            if($role=='admin') { echo 'admin/dashboard.php'; }
            elseif($role=='hospital') { echo 'hospital/dashboard.php'; }
            else { echo 'dashboard.php'; }
        ?>">Blood Bank</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if($role){ ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                <?php } ?>

                <li class="nav-item">
                    <a class="btn btn-danger btn-sm ms-2" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
