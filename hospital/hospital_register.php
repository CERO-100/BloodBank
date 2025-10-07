<?php
session_start();
include "includes/db.php";

if(isset($_POST['register'])){
    $hname = $_POST['hname'];
    $place = $_POST['place'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO hospitals (hname,place,email,password,phone,status) VALUES (?,?,?,?,?,'pending')");
    $stmt->bind_param("sssss",$hname,$place,$email,$password,$phone);

    if($stmt->execute()){
        header("Location: hospital_login.php?msg=registered");
        exit();
    } else {
        $error = "Hospital Registration Failed!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hospital Registration | Blood Bank</title>
  <?php include "head.php"; ?>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <style>
    body { background:linear-gradient(135deg,#C41E3A,#FF512F); min-height:100vh; display:flex; justify-content:center; align-items:center; }
    .register-box { background:#fff; padding:40px; border-radius:15px; box-shadow:0 10px 25px rgba(0,0,0,0.3); max-width:450px; width:100%; }
    h2 { text-align:center; color:#C41E3A; margin-bottom:20px; font-weight:700; }
    input { width:100%; padding:12px; margin:8px 0; border:1px solid #ccc; border-radius:30px; }
    input:focus { border-color:#C41E3A; box-shadow:0 0 8px rgba(196,30,58,0.4); outline:none; }
    button { width:100%; padding:12px; border:none; border-radius:30px; background:linear-gradient(45deg,#C41E3A,#FF512F); color:#fff; font-size:1.1rem; font-weight:700; transition:.3s; }
    button:hover { transform:scale(1.05); box-shadow:0 8px 20px rgba(196,30,58,.4); }
    .link { display:block; margin-top:10px; text-align:center; color:#C41E3A; }
    .error { color:red; font-weight:600; text-align:center; margin-bottom:10px; }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>Hospital Registration</h2>
    <?php if(isset($error)): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form method="POST">
      <input type="text" name="hname" placeholder="Hospital Name" required>
      <input type="text" name="place" placeholder="Location / City" required>
      <input type="email" name="email" placeholder="Hospital Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="text" name="phone" placeholder="Phone Number" required>
      <button type="submit" name="register">Register</button>
    </form>
    <a href="hospital_login.php" class="link">Already registered? Login</a>
  </div>
</body>
</html>
