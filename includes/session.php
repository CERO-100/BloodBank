<?php
// session.php

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// =========================
// 1. Check if user is logged in
// =========================
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// =========================
// 2. Get logged-in user ID
// =========================
function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

// =========================
// 3. Get logged-in user role
// =========================
function get_user_role() {
    return $_SESSION['role'] ?? null;
}

// =========================
// 4. Login user
// =========================
function login_user($user_id, $role, $name=null) {
    session_regenerate_id(true); // prevent session fixation
    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = $role;
    if($name) $_SESSION['name'] = $name;
}

// =========================
// 5. Logout user
// =========================
function logout_user() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: ../login.php");
    exit();
}

// =========================
// 6. Protect page by role
// =========================
function protect_page($role) {
    if(!is_logged_in() || get_user_role() != $role){
        header("Location: ../login.php");
        exit();
    }
}
?>
