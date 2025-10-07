<?php
// functions.php
include_once "config.php"; // include DB connection

// =========================
// 1. Role Check Function
// =========================
function check_role($required_role) {
    if(!isset($_SESSION['role']) || $_SESSION['role'] != $required_role){
        header("Location: ../login.php"); // redirect if role doesn't match
        exit();
    }
}

// =========================
// 2. Sanitize Input
// =========================
function sanitize($data) {
    global $conn;
    return htmlspecialchars(strip_tags($conn->real_escape_string(trim($data))));
}

// =========================
// 3. Redirect with Message
// =========================
function redirect_with_msg($url, $msg, $type='success') {
    $_SESSION['flash_msg'] = ['msg'=>$msg, 'type'=>$type];
    header("Location: $url");
    exit();
}

// =========================
// 4. Display Flash Message
// =========================
function flash_msg() {
    if(isset($_SESSION['flash_msg'])){
        $type = $_SESSION['flash_msg']['type'];
        $msg = $_SESSION['flash_msg']['msg'];
        echo "<div class='alert alert-$type mt-3'>$msg</div>";
        unset($_SESSION['flash_msg']);
    }
}

// =========================
// 5. Log Activity
// =========================
function log_activity($user_id, $action) {
    $log_file = __DIR__ . '/activity.log';
    $timestamp = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $entry = "[$timestamp] UserID: $user_id, IP: $ip - $action" . PHP_EOL;
    file_put_contents($log_file, $entry, FILE_APPEND | LOCK_EX);
}

// =========================
// 6. Send Email Notification
// =========================
function send_email($to, $subject, $message) {
    $headers = "From: no-reply@bloodbank.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($to, $subject, $message, $headers);
}

// =========================
// 7. Generate Random ID
// =========================
function generate_random_id($length = 8) {
    return strtoupper(bin2hex(random_bytes($length/2)));
}

// =========================
// 8. Format Date
// =========================
function format_date($datetime){
    return date('d-M-Y H:i', strtotime($datetime));
}

// =========================
// 9. Check if User Logged In
// =========================
function is_logged_in(){
    return isset($_SESSION['user_id']);
}
?>
