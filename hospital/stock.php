<?php
include "../includes/auth_check.php";
check_role('hospital');
include "../includes/db.php";

// Handle Add/Edit/Delete actions
if(isset($_POST['add_stock'])){
    $blood_group = $_POST['blood_group'];
    $quantity = $_POST['quantity'];
    $hospital_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO blood_stock (hospital_id, blood_group, quantity) VALUES (?,?,?)");
    $stmt->bind_param("isi", $hospital_id, $blood_group, $quantity);
    $stmt->execute();
    $stmt->close();
    $msg = "Blood stock added successfully!";
}

if(isset($_POST['update_stock'])){
    $stock_id = $_POST['stock_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("UPDATE blood_stock SET quantity=? WHERE stock_id=?");
    $stmt->bind_param("ii", $quantity, $stock_id);
    $stmt->execute();
    $stmt->close();
    $msg = "Blood stock updated successfully!";
}

if(isset($_GET['delete'])){
    $stock_id = $_GET['delete'];
    $conn->query("DELETE FROM blood_stock WHERE stock_id=$stock_id");
    $msg = "Blood stock deleted successfully!";
}

// Fetch all blood stock for this hospital
$hospital_id = $_SESSION['user_id'];
$stocks = $conn->query("SELECT * FROM blood_stock WHERE hospital_id=$hospital_id ORDER BY blood_group ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Blood Stock</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/hospital.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="container mt-5">
    <h3><i class="fas fa-vials me-2"></i>Manage Blood Stock</h3>

    <?php if(isset($msg)) echo "<div class='alert alert-success mt-3'>$msg</div>"; ?>

    <!-- Add Blood Stock Form -->
    <div class="card mt-4 mb-4 shadow-sm">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-plus-circle me-2"></i>Add Blood Stock
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <select name="blood_group" class="form-select" required>
                        <option value="">Select Blood Group</option>
                        <?php
                        $bloods = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                        foreach($bloods as $bg) echo "<option value='$bg'>$bg</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" name="quantity" class="form-control" placeholder="Quantity (Units)" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="add_stock" class="btn btn-danger"><i class="fas fa-plus me-1"></i>Add Stock</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Blood Stock Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-vials me-2"></i>Current Blood Stock
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Blood Group</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($stocks->num_rows>0){ $i=1;
                        while($row = $stocks->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['blood_group']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td>
                                <!-- Edit Modal Trigger -->
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['stock_id']; ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?delete=<?php echo $row['stock_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure to delete?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo $row['stock_id']; ?>" tabindex="-1">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Stock - <?php echo $row['blood_group']; ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <form method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="stock_id" value="<?php echo $row['stock_id']; ?>">
                                    <div class="mb-3">
                                        <label>Quantity</label>
                                        <input type="number" name="quantity" class="form-control" value="<?php echo $row['quantity']; ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="update_stock" class="btn btn-danger"><i class="fas fa-save me-1"></i>Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                    <?php }} else { ?>
                        <tr><td colspan="4" class="text-center">No blood stock available</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
