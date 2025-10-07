<?php
include_once "../includes/auth_check.php";
check_role('user');
include_once "../includes/db.php";

// Get filters
$blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';
$search_text = isset($_GET['search_text']) ? $_GET['search_text'] : '';

// Hospitals with blood stock
$hospital_query = "SELECT u.user_id AS hospital_id, u.name AS hospital_name, u.location, u.phone, u.email, b.blood_group, b.quantity 
                   FROM blood_stock b
                   JOIN users u ON b.hospital_id = u.user_id
                   WHERE u.role='hospital'";
if($blood_group != '') $hospital_query .= " AND b.blood_group='$blood_group'";
if($search_text != '') $hospital_query .= " AND (u.name LIKE '%$search_text%' OR u.location LIKE '%$search_text%')";
$hospital_result = $conn->query($hospital_query);
if(!$hospital_result) die("Query failed: ".$conn->error);

// Donors
$donor_query = "SELECT u.user_id, u.name AS donor_name, u.location, u.email, u.phone, u.blood_group
                FROM users u
                WHERE u.role='user'";
if($blood_group != '') $donor_query .= " AND u.blood_group='$blood_group'";
if($search_text != '') $donor_query .= " AND (u.name LIKE '%$search_text%' OR u.location LIKE '%$search_text%')";
$donor_result = $conn->query($donor_query);
if(!$donor_result) die("Query failed: ".$conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search Blood</title>
<?php include "head.php"; ?>
<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/user.css">
<style>
body { background-color: #f0f2f5; font-family: 'Poppins', sans-serif; }
.container { margin-top: 60px; margin-bottom: 50px; }
h3 { color: #C41E3A; font-weight: 700; margin-bottom: 25px; text-align:center; }

/* Filters */
.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 20px;
}
.filters .form-select,
.filters .form-control { height: 45px; width: 200px; }
.btn-custom { background-color: #C41E3A; color: #fff; border-radius: 6px; border: none; transition: 0.3s; }
.btn-custom:hover { background-color: #900020; }

/* Toggle buttons */
.toggle-btns { display: flex; justify-content: center; margin-bottom: 20px; gap: 10px; }
.toggle-btns button { border-radius: 6px; font-weight: 600; width: 120px; }
.toggle-active { background-color: #C41E3A; color: #fff; border: none; }
.toggle-inactive { background-color: #fff; color: #C41E3A; border: 2px solid #C41E3A; }

/* Card Styles */
.blood-card {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    margin-bottom: 20px;
}
.blood-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}
.blood-card h5 { color: #C41E3A; font-weight: 700; margin-bottom: 10px; }
.blood-info { font-size: 0.95rem; color: #555; margin-bottom: 6px; }

/* Badges */
.blood-group-badge { font-weight: 600; padding: 5px 12px; border-radius: 12px; color: #fff; background: linear-gradient(135deg,#ff416c,#ff4b2b); }
.quantity-badge { font-weight: 600; padding: 5px 12px; border-radius: 12px; color: #fff; background: linear-gradient(135deg,#28a745,#218838); }

/* Card Footer */
.card-footer { margin-top: 10px; }
.card-footer .btn { margin-right: 5px; margin-top: 5px; }

/* No results */
.no-results { margin-top: 40px; font-size: 1.1rem; color: #888; text-align:center; }

/* Responsive */
@media(max-width:768px){
    .filters { flex-direction: column; align-items: center; }
    .filters .form-select,
    .filters .form-control { width: 100%; max-width: 300px; }
}
</style>
</head>
<body>
<?php include "header.php"; ?>

<div class="container">
<h3><i class="fas fa-search me-2"></i> Search Blood</h3>

<!-- Filters -->
<div class="filters">
    <select id="bloodSelect" class="form-select">
        <option value="">Select Blood Group</option>
        <?php
        $bloods = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
        foreach($bloods as $bg){
            $sel = ($bg==$blood_group) ? 'selected' : '';
            echo "<option value='$bg' $sel>$bg</option>";
        }
        ?>
    </select>
    <input type="text" id="searchInput" class="form-control" placeholder="Search by hospital or donor name/location">
</div>

<!-- Toggle Buttons -->
<div class="toggle-btns">
    <button id="btnHospital" class="toggle-active">Hospitals</button>
    <button id="btnDonor" class="toggle-inactive">Donors</button>
</div>

<!-- Hospitals -->
<div id="hospitalSection">
<h4>Hospitals</h4>
<div class="row" id="hospitalCards">
<?php if($hospital_result->num_rows>0){
    while($row = $hospital_result->fetch_assoc()){ ?>
        <div class="col-md-4 blood-item" data-blood="<?php echo $row['blood_group']; ?>" data-name="<?php echo strtolower($row['hospital_name']); ?>" data-location="<?php echo strtolower($row['location']); ?>">
            <div class="blood-card">
                <h5><i class="fas fa-hospital me-2"></i><?php echo htmlspecialchars($row['hospital_name']); ?></h5>
                <p class="blood-info"><i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($row['location']); ?></p>
                <p class="blood-info">
                    <span class="blood-group-badge"><?php echo $row['blood_group']; ?></span>
                    &nbsp; <span class="quantity-badge"><?php echo $row['quantity']; ?> Units</span>
                </p>
                <div class="card-footer">
                    <a href="request_blood.php?hospital_id=<?php echo $row['hospital_id']; ?>&blood_group=<?php echo $row['blood_group']; ?>" class="btn btn-custom btn-sm"><i class="fas fa-hand-holding-medical me-1"></i> Request</a>
                    <a href="tel:<?php echo $row['phone'] ?? '1234567890'; ?>" class="btn btn-outline-success btn-sm"><i class="fas fa-phone"></i></a>
                    <a href="mailto:<?php echo $row['email'] ?? 'hospital@example.com'; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>
<?php }} else { ?>
    <div class="col-12 no-results">No hospitals found.</div>
<?php } ?>
</div>
</div>

<!-- Donors -->
<div id="donorSection" style="display:none;">
<h4 class="mt-4">Donors</h4>
<div class="row" id="donorCards">
<?php if($donor_result->num_rows>0){
    while($row = $donor_result->fetch_assoc()){ ?>
        <div class="col-md-4 blood-item" data-blood="<?php echo $row['blood_group']; ?>" data-name="<?php echo strtolower($row['donor_name']); ?>" data-location="<?php echo strtolower($row['location']); ?>">
            <div class="blood-card">
                <h5><i class="fas fa-user me-2"></i><?php echo htmlspecialchars($row['donor_name']); ?></h5>
                <p class="blood-info"><i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($row['location']); ?></p>
                <p class="blood-info">
                    Blood Group: <span class="blood-group-badge"><?php echo $row['blood_group']; ?></span><br>
                    Email: <?php echo $row['email']; ?><br>
                    Phone: <?php echo $row['phone']; ?>
                </p>
                <div class="card-footer">
                    <a href="request_public.php?donor_id=<?php echo $row['user_id']; ?>&blood_group=<?php echo $row['blood_group']; ?>" class="btn btn-custom btn-sm"><i class="fas fa-hand-holding-medical me-1"></i> Request</a>
                    <a href="tel:<?php echo $row['phone']; ?>" class="btn btn-outline-success btn-sm"><i class="fas fa-phone"></i></a>
                    <a href="mailto:<?php echo $row['email']; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>
<?php }} else { ?>
    <div class="col-12 no-results">No donors found.</div>
<?php } ?>
</div>
</div>

</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle between Hospitals and Donors
const btnHospital = document.getElementById('btnHospital');
const btnDonor = document.getElementById('btnDonor');
const hospitalSection = document.getElementById('hospitalSection');
const donorSection = document.getElementById('donorSection');

btnHospital.addEventListener('click', () => {
    hospitalSection.style.display = 'block';
    donorSection.style.display = 'none';
    btnHospital.classList.add('toggle-active');
    btnHospital.classList.remove('toggle-inactive');
    btnDonor.classList.remove('toggle-active');
    btnDonor.classList.add('toggle-inactive');
});

btnDonor.addEventListener('click', () => {
    hospitalSection.style.display = 'none';
    donorSection.style.display = 'block';
    btnDonor.classList.add('toggle-active');
    btnDonor.classList.remove('toggle-inactive');
    btnHospital.classList.remove('toggle-active');
    btnHospital.classList.add('toggle-inactive');
});

// Live search filter
const searchInput = document.getElementById('searchInput');
const bloodSelect = document.getElementById('bloodSelect');
const hospitalCards = document.querySelectorAll('#hospitalCards .blood-item');
const donorCards = document.querySelectorAll('#donorCards .blood-item');

function filterCards() {
    const searchText = searchInput.value.toLowerCase();
    const selectedBlood = bloodSelect.value;

    [hospitalCards, donorCards].forEach(list => {
        list.forEach(card => {
            const name = card.getAttribute('data-name');
            const location = card.getAttribute('data-location');
            const blood = card.getAttribute('data-blood');
            if((selectedBlood === '' || blood === selectedBlood) &&
               (name.includes(searchText) || location.includes(searchText))) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
}

searchInput.addEventListener('input', filterCards);
bloodSelect.addEventListener('change', filterCards);
</script>
</body>
</html>
