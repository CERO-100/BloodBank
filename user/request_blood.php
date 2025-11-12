<?php
include "../includes/auth_check.php";
check_role('user');
include "../includes/db.php";

$msg = "";

// Handle blood request submission
if (isset($_POST['request'])) {
    $user_id = $_SESSION['user_id'];
    $hospital_id = intval($_POST['hospital_id']);
    $blood_group = $_POST['blood_group'];
    $quantity = intval($_POST['quantity']);
    $status = 'pending';

    // Check if hospital has the blood group in stock
    $stock_check = $conn->prepare("SELECT quantity FROM blood_stock WHERE hospital_id = ? AND blood_group = ?");
    $stock_check->bind_param("is", $hospital_id, $blood_group);
    $stock_check->execute();
    $stock_result = $stock_check->get_result()->fetch_assoc();
    $stock_check->close();

    if (!$stock_result) {
        $msg = "❌ Error: This hospital doesn't have " . $blood_group . " blood group in stock.";
        $msg_type = "error";
    } elseif ($stock_result['quantity'] < $quantity) {
        $msg = "❌ Error: Hospital only has " . $stock_result['quantity'] . " units of " . $blood_group . " available. You requested " . $quantity . " units.";
        $msg_type = "error";
    } else {
        // Get requester and hospital information
        $requester_stmt = $conn->prepare("SELECT name, phone, email FROM users WHERE user_id = ?");
        $requester_stmt->bind_param("i", $user_id);
        $requester_stmt->execute();
        $requester_info = $requester_stmt->get_result()->fetch_assoc();
        $requester_stmt->close();

        $stmt = $conn->prepare("INSERT INTO requests (user_id, hospital_id, blood_group, quantity, status) VALUES (?,?,?,?,?)");
        if (!$stmt) {
            die("Database Error: " . $conn->error);
        }
        $stmt->bind_param("iisis", $user_id, $hospital_id, $blood_group, $quantity, $status);

        if ($stmt->execute()) {
            // Create notification for the hospital
            $notification_msg = "New blood request from {$requester_info['name']} for {$blood_group} ({$quantity} units). Contact: {$requester_info['phone']}";
            $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type, status) VALUES (?, ?, 'request', 'unread')");
            $notif_stmt->bind_param("is", $hospital_id, $notification_msg);
            $notif_stmt->execute();
            $notif_stmt->close();

            $msg = "✅ Your blood request has been submitted successfully! Available stock: " . $stock_result['quantity'] . " units";
            $msg_type = "success";
        } else {
            $msg = "❌ Error: " . $stmt->error;
            $msg_type = "error";
        }
        $stmt->close();
    }
}

// Fetch hospitals
$hospitals = $conn->query("SELECT user_id, name, location, phone, email FROM users WHERE role='hospital'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Request Blood to Hospital</title>
    <?php include "head.php"; ?>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            margin-top: 40px;
        }

        .hospital-card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .hospital-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .hospital-card h5 {
            font-weight: 700;
            color: #C41E3A;
            margin-bottom: 10px;
        }

        .blood-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 8px;
            background: #f8f9fa;
            margin: 3px;
            font-size: 14px;
            border: 1px solid #ddd;
        }

        .action-icons button,
        .action-icons a {
            margin-right: 10px;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 50%;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }

        .action-icons .request {
            color: #fff;
            background: #C41E3A;
        }

        .action-icons .call {
            color: #28a745;
            border: 1px solid #28a745;
            background: #fff;
        }

        .action-icons .mail {
            color: #007bff;
            border: 1px solid #007bff;
            background: #fff;
        }
    </style>
</head>

<body>

    <?php include "header.php"; ?>

    <div class="container">
        <h3 class="mb-4 text-center"><i class="fas fa-clinic-medical me-2"></i>Available Hospitals</h3>
        <div class="row">
            <?php while ($h = $hospitals->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="hospital-card">
                        <h5><i class="fas fa-hospital me-2"></i><?= htmlspecialchars($h['name']); ?></h5>
                        <p><i class="fas fa-map-marker-alt me-2"></i><?= htmlspecialchars($h['location']); ?></p>
                        <p><i class="fas fa-phone me-2"></i><?= htmlspecialchars($h['phone']); ?></p>
                        <p><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($h['email']); ?></p>

                        <?php
                        $stock = $conn->query("SELECT blood_group, quantity FROM blood_stock WHERE hospital_id=" . (int)$h['user_id']);
                        ?>
                        <div class="blood-stock">
                            <strong>Stock:</strong><br>
                            <?php if ($stock->num_rows > 0): ?>
                                <?php while ($s = $stock->fetch_assoc()): ?>
                                    <span class="blood-badge"><?= htmlspecialchars($s['blood_group']); ?> : <?= htmlspecialchars($s['quantity']); ?> Units</span>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <span class="text-muted">No stock available</span>
                            <?php endif; ?>
                        </div>

                        <div class="action-icons mt-3">
                            <button type="button" class="request" onclick="openRequestPopup(<?= $h['user_id']; ?>)" title="Request">
                                <i class="fas fa-hand-holding-medical"></i>
                            </button>
                            <a href="tel:<?= $h['phone']; ?>" class="call"><i class="fas fa-phone"></i></a>
                            <a href="mailto:<?= $h['email']; ?>" class="mail"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Store hospital stocks
        const hospitalStocks = {};

        <?php
        // Generate JavaScript object with all hospital stocks
        $hospitals_list = $conn->query("SELECT user_id, name FROM users WHERE role='hospital'");
        while ($h = $hospitals_list->fetch_assoc()) {
            $hospital_id = $h['user_id'];
            $stocks = $conn->query("SELECT blood_group, quantity FROM blood_stock WHERE hospital_id=" . (int)$hospital_id);
            echo "hospitalStocks[{$hospital_id}] = {";
            $stock_arr = [];
            while ($s = $stocks->fetch_assoc()) {
                $stock_arr[] = "'{$s['blood_group']}': {$s['quantity']}";
            }
            echo implode(',', $stock_arr);
            echo "};\n";
        }
        ?>

        function openRequestPopup(hospitalId) {
            const stocks = hospitalStocks[hospitalId] || {};
            const bloodGroups = Object.keys(stocks);

            if (bloodGroups.length === 0) {
                Swal.fire({
                    title: 'No Stock Available',
                    text: 'This hospital currently has no blood stock available.',
                    icon: 'warning',
                    confirmButtonColor: '#C41E3A'
                });
                return;
            }

            let bloodGroupOptions = '<option value="">Choose...</option>';
            bloodGroups.forEach(bg => {
                bloodGroupOptions += `<option value="${bg}">${bg} (${stocks[bg]} units available)</option>`;
            });

            Swal.fire({
                title: 'Request Blood',
                html: `
      <form id="bloodRequestForm" method="POST">
        <input type="hidden" name="hospital_id" value="${hospitalId}">
        <div class="mb-3 text-start">
          <label class="form-label fw-bold">Select Blood Group</label>
          <select name="blood_group" id="bloodGroupSelect" class="form-select" required onchange="updateMaxQuantity(${hospitalId})">
            ${bloodGroupOptions}
          </select>
        </div>
        <div class="mb-3 text-start">
          <label class="form-label fw-bold">Quantity (Units)</label>
          <input type="number" name="quantity" id="quantityInput" class="form-control" min="1" max="1" required>
          <small id="availableText" class="text-muted"></small>
        </div>
        <input type="hidden" name="request" value="1">
      </form>
    `,
                showCancelButton: true,
                confirmButtonText: 'Submit Request',
                confirmButtonColor: '#C41E3A',
                focusConfirm: false,
                didOpen: () => {
                    // Set up event listener for blood group change
                    document.getElementById('bloodGroupSelect').addEventListener('change', function() {
                        updateMaxQuantity(hospitalId);
                    });
                },
                preConfirm: () => {
                    const form = document.getElementById('bloodRequestForm');
                    const bloodGroup = document.getElementById('bloodGroupSelect').value;
                    const quantity = parseInt(document.getElementById('quantityInput').value);

                    if (!form.reportValidity()) {
                        Swal.showValidationMessage(`Please fill all fields correctly.`);
                        return false;
                    }

                    if (!bloodGroup) {
                        Swal.showValidationMessage(`Please select a blood group.`);
                        return false;
                    }

                    if (quantity > stocks[bloodGroup]) {
                        Swal.showValidationMessage(`Maximum ${stocks[bloodGroup]} units available for ${bloodGroup}.`);
                        return false;
                    }

                    form.submit();
                }
            });
        }

        function updateMaxQuantity(hospitalId) {
            const bloodGroup = document.getElementById('bloodGroupSelect').value;
            const quantityInput = document.getElementById('quantityInput');
            const availableText = document.getElementById('availableText');

            if (bloodGroup && hospitalStocks[hospitalId] && hospitalStocks[hospitalId][bloodGroup]) {
                const maxQty = hospitalStocks[hospitalId][bloodGroup];
                quantityInput.max = maxQty;
                quantityInput.value = Math.min(1, maxQty);
                availableText.textContent = `Maximum ${maxQty} units available`;
                availableText.style.color = maxQty > 0 ? '#28a745' : '#dc3545';
            } else {
                quantityInput.max = 1;
                quantityInput.value = 1;
                availableText.textContent = '';
            }
        }
    </script>

    <?php if (isset($msg)): ?>
        <script>
            Swal.fire({
                title: '<?php echo isset($msg_type) && $msg_type == "error" ? "Error!" : "Success!"; ?>',
                text: "<?= str_replace('"', '\"', $msg) ?>",
                icon: '<?php echo isset($msg_type) && $msg_type == "error" ? "error" : "success"; ?>',
                confirmButtonColor: '#C41E3A'
            });
        </script>
    <?php endif; ?>

</body>

</html>