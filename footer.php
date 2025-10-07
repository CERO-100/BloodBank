
<footer class="bg-dark text-light pt-4">
    <div class="container">
        <div class="row">

            <!-- About section -->
            <div class="col-md-4 mb-3">
                <h5 class="text-primary fw-bold">About Blood Bank</h5>
                <p>Blood Bank Management System helps you donate, request, and manage blood efficiently. Every drop counts — save lives today.</p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 mb-3">
                <h5 class="text-primary fw-bold">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-light text-decoration-none">Home</a></li>
                    <li><a href="about.php" class="text-light text-decoration-none">About</a></li>
                    <li><a href="contact.php" class="text-light text-decoration-none">Contact</a></li>
                    <li><a href="login.php?role=user" class="text-light text-decoration-none">User Login</a></li>
                    <li><a href="login.php?role=hospital" class="text-light text-decoration-none">Hospital Login</a></li>
                </ul>
            </div>

            <!-- Contact info -->
            <div class="col-md-4 mb-3">
                <h5 class="text-primary fw-bold">Contact Us</h5>
                <p>Email: <a href="mailto:support@bloodbank.com" class="text-light">gc@bloodbank.com</a></p>
                <p>Phone: <a href="tel:+1234567890" class="text-light">+1 234 567 890</a></p>
                <p>Address: 123 Blood Street, City, Country</p>
            </div>

        </div>

        <!-- Google Map Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-map-marker-alt me-2"></i> Find Us on Google Maps
                    </div>
                    <div class="card-body p-0">
                        <!-- Replace the src below with your Google Map Embed link -->
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3890.123456789!2d76.567890123!3d9.267890123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b07d2a123456789%3A0xabcdef123456789!2sYour%20Blood%20Bank!5e0!3m2!1sen!2sin!4v1701234567890!5m2!1sen!2sin" 
                            width="100%" 
                            height="300" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-light mt-4">

        <div class="text-center pb-3">
            &copy; <?= date('Y'); ?> Blood Bank Management System. All rights reserved.
        </div>
    </div>
</footer>
