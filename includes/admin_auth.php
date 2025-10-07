<?php
if (session_status() == PHP_SESSION_NONE) session_start();

// Extra security: regenerate session ID every 10 minutes
if (!isset($_SESSION['regenerated']) || (time() - $_SESSION['regenerated'] > 600)) {
    session_regenerate_id(true);
    $_SESSION['regenerated'] = time();
}

function check_admin_role() {
    // 1. Authentication check
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: admin_login.php?error=unauthorized");
        exit();
    }

    // 2. Role check
    if ($_SESSION['role'] !== 'admin') {
        header("Location: admin_login.php?error=access_denied");
        exit();
    }

    // 3. Session hijacking protection
    if (!isset($_SESSION['ip'])) $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    if (!isset($_SESSION['user_agent'])) $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

    if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        $_SESSION = []; // clear session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: admin_login.php?error=session_mismatch");
        exit();
    }

    // 4. Auto logout after inactivity (30 minutes)
    $timeout = 30 * 60;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        $_SESSION = [];
        session_destroy();
        header("Location: admin_login.php?error=session_timeout");
        exit();
    }
    $_SESSION['last_activity'] = time();
}
?>
