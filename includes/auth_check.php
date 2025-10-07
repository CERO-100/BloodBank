<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require a specific role for accessing a page.
 *
 * @param string $role Required role ('admin', 'hospital', 'user')
 */
function check_role($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        // Destroy session for extra security
        session_destroy();
        // Redirect to the correct login page
        switch ($role) {
            case 'admin':
                header("Location: ../admin/admin_login.php?error=unauthorized");
                break;
            case 'hospital':
                header("Location: ../hospital/hospital_login.php?error=unauthorized");
                break;
            case 'user':
            default:
                header("Location: ../user/user_login.php?error=unauthorized");
                break;
        }
        exit();
    }
}

/**
 * Optional: General authentication check (any logged-in user)
 */
function check_authenticated() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: ../login.php?error=unauthorized");
        exit();
    }
}
