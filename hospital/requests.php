<?php
include "../includes/auth_check.php";
check_role('hospital');
include "../includes/db.php";

// Handle Approve/Reject
if (isset($_GET['action'], $_GET['request_id'])) {
    $request_id = (int) $_GET['request_id'];
    $action = $_GET['action'];
    $hospital_id = $_SESSION['user_id'];

    if (in_array($action, ['approved', 'rejected'])) {
        // Get hospital and request details
        $info_stmt = $conn->prepare("SELECT r.user_id, r.blood_group, r.quantity, h.name as hospital_name 
                                       FROM requests r 
                                       JOIN users h ON r.hospital_id = h.user_id 
                                       WHERE r.request_id = ? AND r.hospital_id = ?");
        $info_stmt->bind_param("ii", $request_id, $hospital_id);
        $info_stmt->execute();
        $request_info = $info_stmt->get_result()->fetch_assoc();
        $info_stmt->close();

        if ($action == 'approved' && $request_info) {
            // Check if hospital has enough stock
            $stock_check = $conn->prepare("SELECT stock_id, quantity FROM blood_stock WHERE hospital_id = ? AND blood_group = ?");
            $stock_check->bind_param("is", $hospital_id, $request_info['blood_group']);
            $stock_check->execute();
            $stock_result = $stock_check->get_result()->fetch_assoc();
            $stock_check->close();

            if (!$stock_result) {
                $msg = "❌ Error: No stock available for " . $request_info['blood_group'];
            } elseif ($stock_result['quantity'] < $request_info['quantity']) {
                $msg = "❌ Error: Insufficient stock. Available: " . $stock_result['quantity'] . " units, Requested: " . $request_info['quantity'] . " units";
            } else {
                // Deduct stock
                $new_quantity = $stock_result['quantity'] - $request_info['quantity'];
                $update_stock = $conn->prepare("UPDATE blood_stock SET quantity = ? WHERE stock_id = ?");
                $update_stock->bind_param("ii", $new_quantity, $stock_result['stock_id']);
                $update_stock->execute();
                $update_stock->close();

                // Update request status
                $stmt = $conn->prepare("UPDATE requests SET status=? WHERE request_id=? AND hospital_id=?");
                $stmt->bind_param("sii", $action, $request_id, $hospital_id);
                $stmt->execute();
                $stmt->close();

                // Create notification for the user
                $notification_msg = "✅ Your blood request for {$request_info['blood_group']} ({$request_info['quantity']} units) at {$request_info['hospital_name']} has been approved. Stock deducted successfully.";
                $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type, status) VALUES (?, ?, 'request', 'unread')");
                $notif_stmt->bind_param("is", $request_info['user_id'], $notification_msg);
                $notif_stmt->execute();
                $notif_stmt->close();

                $msg = "✅ Request approved successfully! Stock updated: " . $stock_result['quantity'] . " → " . $new_quantity . " units";
            }
        } elseif ($action == 'rejected' && $request_info) {
            // Update request status for rejection
            $stmt = $conn->prepare("UPDATE requests SET status=? WHERE request_id=? AND hospital_id=?");
            $stmt->bind_param("sii", $action, $request_id, $hospital_id);
            $stmt->execute();
            $stmt->close();

            // Create notification for the user
            $notification_msg = "Your blood request for {$request_info['blood_group']} ({$request_info['quantity']} units) at {$request_info['hospital_name']} has been rejected.";
            $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type, status) VALUES (?, ?, 'request', 'unread')");
            $notif_stmt->bind_param("is", $request_info['user_id'], $notification_msg);
            $notif_stmt->execute();
            $notif_stmt->close();

            $msg = "Request has been rejected successfully!";
        }
    }
}

// Fetch all requests for this hospital
$hospital_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT r.request_id, u.name AS user_name, u.blood_group, u.phone, u.email, 
           r.blood_group AS requested_blood, r.quantity, r.status, r.created_at
    FROM requests r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.hospital_id = ?
    ORDER BY r.created_at DESC
");
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$requests = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Blood Requests</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/hospital.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container mt-5">
        <h3 class="mb-4"><i class="fas fa-hand-holding-medical me-2 text-danger"></i>Blood Requests</h3>

        <?php if (isset($msg)) : ?>
            <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <div class="card shadow-lg">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-list me-2"></i>All Requests
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Requested Blood</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($requests->num_rows > 0): $i = 1; ?>
                            <?php while ($row = $requests->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['user_name']); ?></strong><br>
                                        <small><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($row['email']); ?></small><br>
                                        <small><i class="fas fa-phone me-1"></i><?= htmlspecialchars($row['phone']); ?></small>
                                    </td>
                                    <td><span class="badge bg-danger"><?= htmlspecialchars($row['requested_blood']); ?></span></td>
                                    <td><?= htmlspecialchars($row['quantity']); ?> Units</td>
                                    <td>
                                        <?php if ($row['status'] == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php elseif ($row['status'] == 'approved'): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d-M-Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <!-- Approve Button -->
                                        <?php if ($row['status'] != 'approved'): ?>
                                            <a href="?action=approved&request_id=<?= $row['request_id']; ?>"
                                                class="btn btn-sm btn-success mb-1"><i class="fas fa-check"></i></a>
                                        <?php endif; ?>
                                        <!-- Reject Button -->
                                        <?php if ($row['status'] != 'rejected'): ?>
                                            <a href="?action=rejected&request_id=<?= $row['request_id']; ?>"
                                                class="btn btn-sm btn-danger mb-1"><i class="fas fa-times"></i></a>
                                        <?php endif; ?>
                                        <!-- Edit Button -->
                                        <a href="edit_request.php?request_id=<?= $row['request_id']; ?>"
                                            class="btn btn-sm btn-primary mb-1"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-2"></i>No blood requests found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>\
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>s
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>

</html>