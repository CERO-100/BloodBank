<?php
include 'db.php';

// Get filter values
$district = isset($_GET['district']) ? $_GET['district'] : '';
$blood_type = isset($_GET['blood_type']) ? $_GET['blood_type'] : '';

$sql = "SELECT hospital_name, district, blood_type, latitude, longitude 
        FROM hospitals WHERE 1";

if ($district != '') {
    $sql .= " AND district = '" . $conn->real_escape_string($district) . "'";
}
if ($blood_type != '') {
    $sql .= " AND blood_type = '" . $conn->real_escape_string($blood_type) . "'";
}

$result = $conn->query($sql);

$hospitals = [];
while ($row = $result->fetch_assoc()) {
    $hospitals[] = $row;
}

echo json_encode($hospitals);
?>
