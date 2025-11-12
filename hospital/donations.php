<?php
include "../includes/auth_check.php";
check_role('hospital');
include "../includes/db.php";

// Hospital ID
$hospital_id = $_SESSION['user_id'];

// Handle approve/reject actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $donation_id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve' || $action === 'reject') {
        $status = ($action === 'approve') ? 'approved' : 'rejected';

        // Get donation and hospital details
        $info_stmt = $conn->prepare("SELECT d.user_id, d.blood_group, d.quantity, h.name as hospital_name 
                                       FROM donations d 
                                       JOIN users h ON d.hospital_id = h.user_id 
                                       WHERE d.donation_id = ? AND d.hospital_id = ?");
        $info_stmt->bind_param("ii", $donation_id, $hospital_id);
        $info_stmt->execute();
        $donation_info = $info_stmt->get_result()->fetch_assoc();
        $info_stmt->close();

        // Update donation status
        $stmt = $conn->prepare("UPDATE donations SET status=? WHERE donation_id=? AND hospital_id=?");
        $stmt->bind_param("sii", $status, $donation_id, $hospital_id);

        if ($stmt->execute() && $donation_info) {
            // Create notification for the donor
            $notification_msg = "Your blood donation of {$donation_info['blood_group']} ({$donation_info['quantity']} units) at {$donation_info['hospital_name']} has been " . $status . ". Thank you for your generosity!";
            $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type, status) VALUES (?, ?, 'donation', 'unread')");
            $notif_stmt->bind_param("is", $donation_info['user_id'], $notification_msg);
            $notif_stmt->execute();
            $notif_stmt->close();
        }
        $stmt->close();

        header("Location: donations.php");
        exit;
    }
}

// Fetch all donations related to this hospital
$donations = $conn->query("
    SELECT d.donation_id, u.name AS donor_name, d.blood_group, d.quantity, d.status, d.created_at
    FROM donations d
    JOIN users u ON d.user_id = u.user_id
    WHERE d.hospital_id = $hospital_id
    ORDER BY d.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Donations</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/hospital.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-approved {
            background-color: #28a745;
        }

        .badge-rejected {
            background-color: #dc3545;
        }
    </style>
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container mt-4">
        <h2><i class="fas fa-donate me-2"></i>Donations</h2>

        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Donor</th>
                        <th>Blood Group</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($donations->num_rows > 0): ?>
                        <?php while ($row = $donations->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['donor_name']) ?></td>
                                <td><?= $row['blood_group'] ?></td>
                                <td><?= $row['quantity'] ?> Units</td>
                                <td>
                                    <?php
                                    if ($row['status'] == 'pending') echo "<span class='badge badge-pending'>Pending</span>";
                                    elseif ($row['status'] == 'approved') echo "<span class='badge badge-approved'>Approved</span>";
                                    else echo "<span class='badge badge-rejected'>Rejected</span>";
                                    ?>
                                </td>
                                <td><?= date('d-M-Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <a href="donations.php?action=approve&id=<?= $row['donation_id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this donation?');">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="donations.php?action=reject&id=<?= $row['donation_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject this donation?');">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No donations found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>