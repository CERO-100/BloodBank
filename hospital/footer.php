<footer class="bg-dark text-white mt-5 pt-4 pb-2">
    <div class="container">
        <div class="row">

            <!-- About Hospital Dashboard -->
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold">Hospital Dashboard</h5>
                <p class="small text-muted">
                    Manage blood stock, monitor requests, view donors, and track donations with ease. This system helps hospitals streamline blood management efficiently.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="dashboard.php" class="text-white text-decoration-none"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                    <li><a href="stock.php" class="text-white text-decoration-none"><i class="fas fa-vials me-2"></i> Blood Stock</a></li>
                    <li><a href="requests.php" class="text-white text-decoration-none"><i class="fas fa-hand-holding-medical me-2"></i> Requests</a></li>
                    <li><a href="donors.php" class="text-white text-decoration-none"><i class="fas fa-users me-2"></i> Donors</a></li>
                    <li><a href="donations.php" class="text-white text-decoration-none"><i class="fas fa-history me-2"></i> Donations</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold">Contact Info</h5>
                <p class="small mb-1"><i class="fas fa-map-marker-alt me-2"></i> 123 Blood St, City, Country</p>
                <p class="small mb-1"><i class="fas fa-phone me-2"></i> +123 456 7890</p>
                <p class="small mb-1"><i class="fas fa-envelope me-2"></i> hospital@example.com</p>
                <div class="mt-2">
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter fa-lg"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
            </div>

        </div>

        <hr class="border-top border-secondary">

        <div class="row">
            <div class="col-12 text-center">
                <p class="small mb-0">&copy; <?php echo date('Y'); ?> Hospital Blood Bank Management System. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<style>
footer a:hover {
    text-decoration: underline;
}
</style>
