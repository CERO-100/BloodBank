<?php
include_once "../includes/auth_check.php";
check_role('user');
include_once "../includes/db.php";



$user_id = $_SESSION['user_id'];

// Fetch all requests - both to hospitals and to other users
$query = "SELECT r.request_id, 
                 COALESCE(h.name, u.name, 'Community Donor') AS recipient_name,
                 COALESCE(h.location, u.location, 'N/A') AS recipient_location,
                 r.blood_group, 
                 r.quantity, 
                 r.status, 
                 r.created_at,
                 CASE 
                    WHEN r.hospital_id IS NOT NULL THEN 'hospital'
                    WHEN r.donor_id IS NOT NULL THEN 'user'
                    ELSE 'public'
                 END as request_type
          FROM requests r
          LEFT JOIN users h ON r.hospital_id = h.user_id AND h.role = 'hospital'
          LEFT JOIN users u ON r.donor_id = u.user_id AND u.role = 'user'
          WHERE r.user_id = ?
          ORDER BY r.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Requests</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            margin-top: 60px;
            margin-bottom: 50px;
        }

        h3 {
            color: #C41E3A;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
        }

        .card-request {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card-request:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }

        .card-request h5 {
            color: #C41E3A;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-request p {
            margin-bottom: 6px;
            color: #555;
            font-size: 0.95rem;
        }

        .badge-pending {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #fff;
        }

        .badge-approved {
            background: linear-gradient(135deg, #28a745, #218838);
            color: #fff;
        }

        .badge-rejected {
            background: linear-gradient(135deg, #dc3545, #b02a37);
            color: #fff;
        }

        .badge-hospital {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #fff;
        }

        .badge-user {
            background: linear-gradient(135deg, #17a2b8, #117a8b);
            color: #fff;
        }

        .badge-public {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: #fff;
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <h3><i class="fas fa-list-alt me-2"></i>My Blood Requests</h3>

        <?php if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status_class = $row['status'] == 'pending' ? 'badge-pending' : ($row['status'] == 'approved' ? 'badge-approved' : 'badge-rejected');
                $type_class = $row['request_type'] == 'hospital' ? 'badge-hospital' : ($row['request_type'] == 'user' ? 'badge-user' : 'badge-public');
                $type_icon = $row['request_type'] == 'hospital' ? 'fa-hospital' : 'fa-user';
        ?>
                <div class="card-request">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5><i class="fas <?php echo $type_icon; ?> me-2"></i><?php echo htmlspecialchars($row['recipient_name']); ?></h5>
                        <span class="badge <?php echo $type_class; ?>"><?php echo ucfirst($row['request_type']); ?></span>
                    </div>
                    <p><i class="fas fa-map-marker-alt me-1"></i> Location: <strong><?php echo htmlspecialchars($row['recipient_location']); ?></strong></p>
                    <p><i class="fas fa-tint me-1"></i> Blood Group: <strong><?php echo htmlspecialchars($row['blood_group']); ?></strong></p>
                    <p><i class="fas fa-boxes me-1"></i> Quantity: <strong><?php echo htmlspecialchars($row['quantity']); ?> Units</strong></p>
                    <p>Status: <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($row['status']); ?></span></p>
                    <p><i class="fas fa-calendar-alt me-1"></i> Requested on: <?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></p>
                </div>
            <?php }
        } else { ?>
            <p class="text-center text-muted">You haven't made any blood requests yet.</p>
        <?php } ?>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>