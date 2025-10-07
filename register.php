<?php
session_start();
include "includes/db.php";

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $location = $_POST['location'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'] ?? NULL;
    $gender = $_POST['gender'] ?? NULL;
    $emergency_contact = $_POST['emergency_contact'] ?? NULL;
    $notes = $_POST['notes'] ?? NULL;
    $district = $_POST['district'] ?? NULL;
    $hospital_id = ($role == 'hospital') ? $_POST['hospital_id'] : NULL;
    $blood_group = ($role == 'user') ? $_POST['blood_group'] : NULL;
    $latitude = ($role == 'hospital') ? $_POST['latitude'] : NULL;
    $longitude = ($role == 'hospital') ? $_POST['longitude'] : NULL;

    // Validate hospital coordinates if role is hospital
    if($role == 'hospital' && (empty($latitude) || empty($longitude))){
    $error = "Please select your hospital location on the map.";
} else {
    $stmt = $conn->prepare("INSERT INTO users (name,email,password,role,phone,location,blood_group,hospital_id,dob,gender,emergency_contact,notes,district,latitude,longitude) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    if(!$stmt){
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssssssssdd",
        $name,
        $email,
        $password,
        $role,
        $phone,
        $location,
        $blood_group,
        $hospital_id,
        $dob,
        $gender,
        $emergency_contact,
        $notes,
        $district,
        $latitude,
        $longitude
    );

    if($stmt->execute()){
        $_SESSION['message'] = "🎉 Registration Successful! Welcome to Blood Bank.";
        $_SESSION['alert_type'] = "success";
        header("Location: register.php?success=1");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | Blood Bank</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    background: linear-gradient(135deg, #FF4D4D, #C41E3A);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.register-card {
    background: #fff;
    padding: 40px 35px;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    width: 100%;
    max-width: 550px;
    text-align: center;
}
.register-card h2 {
    margin-bottom: 25px;
    color: #C41E3A;
    font-weight: 700;
}
.icon-input {
    position: relative;
    margin-bottom: 15px;
}
.icon-input i {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #C41E3A;
}
.register-card input,
.register-card select,
.register-card textarea {
    width: 100%;
    padding: 12px 15px 12px 45px;
    border: 1px solid #ddd;
    border-radius: 50px;
    outline: none;
    transition: 0.3s;
}
.register-card input:focus,
.register-card select:focus,
.register-card textarea:focus {
    border-color: #C41E3A;
    box-shadow: 0 0 12px rgba(196,30,58,0.3);
}
.register-card button {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 50px;
    background: linear-gradient(45deg, #C41E3A, #FF4D4D);
    color: #fff;
    font-size: 1.2rem;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
}
.register-card button:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(196,30,58,0.4);
}
.register-card .login-link {
    display: block;
    margin-top: 15px;
    font-size: 0.95rem;
    color: #C41E3A;
    text-decoration: none;
}
.register-card .login-link:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="register-card">
    <h2>Register</h2>

    <?php if(isset($error)) { ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php } ?>

    <?php if(isset($_GET['success'])) { ?>
        <div class="alert alert-success animate__animated animate__fadeInDown">
            🎉 Registration Successful! You can <a href="login.php">Login</a> now.
        </div>
    <?php } ?>

    <form action="" method="POST" autocomplete="off">
        <!-- Inside your form -->
        <!-- Inside your form -->
        <div class="icon-input"><i class="fas fa-user"></i>
            <input type="text" name="name" placeholder="Full Name / Hospital Name" required>
        </div>
        <div class="icon-input"><i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="icon-input"><i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="icon-input"><i class="fas fa-map-marker-alt"></i>
            <input type="text" name="location" placeholder="Address / Place">
        </div>
        <div class="icon-input"><i class="fas fa-phone"></i>
            <input type="text" name="phone" pattern="[6-9][0-9]{9}" maxlength="10" placeholder="Phone" required>
        </div>
        <div class="icon-input" id="dobField">
            <i class="fas fa-calendar-alt"></i>
            <input type="date" name="dob" placeholder="Date of Birth">
        </div>
        <div class="icon-input">
            <i class="fas fa-venus-mars"></i>
            <select name="gender">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="icon-input" id="emergencyField">
            <i class="fas fa-user-friends"></i>
            <input type="text" name="emergency_contact" placeholder="Emergency Contact Number">
        </div>
        <div class="icon-input" id="notesField">
            <i class="fas fa-sticky-note"></i>
            <textarea name="notes" rows="2" placeholder="Notes / Additional Info"></textarea>
        </div>
        <div class="icon-input"><i class="fas fa-city"></i>
            <select name="district">
                <option value="">Select District</option>
                <option value="Alappuzha">Alappuzha</option>
                <option value="Ernakulam">Ernakulam</option>
                <option value="Idukki">Idukki</option>
                <option value="Kannur">Kannur</option>
                <option value="Kasaragod">Kasaragod</option>
                <option value="Kollam">Kollam</option>
                <option value="Kottayam">Kottayam</option>
                <option value="Kozhikode">Kozhikode</option>
                <option value="Malappuram">Malappuram</option>
                <option value="Palakkad">Palakkad</option>
                <option value="Pathanamthitta">Pathanamthitta</option>
                <option value="Thiruvananthapuram">Thiruvananthapuram</option>
                <option value="Thrissur">Thrissur</option>
                <option value="Wayanad">Wayanad</option>
            </select>
        </div>
        <div class="icon-input" id="latitudeField">
            <i class="fas fa-map-pin"></i>
            <input type="text" name="latitude" placeholder="Latitude">
        </div>
        <div class="icon-input" id="longitudeField">
            <i class="fas fa-map-pin"></i>
            <input type="text" name="longitude" placeholder="Longitude">
        </div>
        <div class="icon-input"><i class="fas fa-user-tag"></i>
            <select name="role" id="roleSelect" required>
                <option value="">Select Role</option>
                <option value="user">Donor/Recipient</option>
                <option value="hospital">Hospital</option>
            </select>
        </div>
        <div class="icon-input" id="hospitalField">
            <i class="fas fa-id-badge"></i>
            <input type="text" name="hospital_id" placeholder="Hospital ID">
        </div>
        <div class="icon-input" id="bloodGroupField">
            <i class="fas fa-tint"></i>
            <select name="blood_group">
                <option value="">Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
            </select>
        </div>
        <!-- Submit button -->
<button type="submit" name="register">Register</button>
<a href="login.php" class="login-link">Already have an account? Login</a>

        <script>
        const roleSelect = document.getElementById('roleSelect');

        const dobField = document.getElementById('dobField');
        const emergencyField = document.getElementById('emergencyField');
        const notesField = document.getElementById('notesField');
        const latitudeField = document.getElementById('latitudeField');
        const longitudeField = document.getElementById('longitudeField');
        const hospitalField = document.getElementById('hospitalField');
        const bloodGroupField = document.getElementById('bloodGroupField');

        function toggleFields() {
            if(roleSelect.value === 'hospital'){
                // Show hospital fields
                latitudeField.style.display = 'block';
                longitudeField.style.display = 'block';
                emergencyField.style.display = 'block';
                notesField.style.display = 'block';
                hospitalField.style.display = 'block';
                // Hide donor fields
                bloodGroupField.style.display = 'none';
                dobField.style.display = 'none';
            } else if(roleSelect.value === 'user'){
                // Show donor fields
                bloodGroupField.style.display = 'block';
                dobField.style.display = 'block';
                // Hide hospital fields
                latitudeField.style.display = 'none';
                longitudeField.style.display = 'none';
                emergencyField.style.display = 'none';
                notesField.style.display = 'none';
                hospitalField.style.display = 'none';
            } else {
                // Default show both
                latitudeField.style.display = 'block';
                longitudeField.style.display = 'block';
                emergencyField.style.display = 'block';
                notesField.style.display = 'block';
                hospitalField.style.display = 'block';
                bloodGroupField.style.display = 'block';
                dobField.style.display = 'block';
            }
        }

        // Initialize
        toggleFields();
        roleSelect.addEventListener('change', toggleFields);
        </script>


</body>
</html>