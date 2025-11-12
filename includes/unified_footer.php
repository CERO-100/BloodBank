<?php

/**
 * Unified Footer Component for Blood Bank System
 * Consistent footer across all pages
 */

// Determine base path
$base_path = '';
if (
    strpos($_SERVER['PHP_SELF'], '/admin/') !== false ||
    strpos($_SERVER['PHP_SELF'], '/hospital/') !== false ||
    strpos($_SERVER['PHP_SELF'], '/user/') !== false
) {
    $base_path = '../';
}

$current_year = date('Y');
?>

<footer style="background: var(--primary-gradient); color: var(--white); padding: 3rem 0 1.5rem; margin-top: auto;">
    <div class="container">
        <div class="row g-4">

            <!-- About Section -->
            <div class="col-lg-4 col-md-6">
                <h5 style="color: var(--white); font-weight: var(--font-weight-bold); margin-bottom: var(--spacing-md); display: flex; align-items: center; gap: var(--spacing-sm);">
                    <i class="fas fa-tint"></i>
                    Blood Bank System
                </h5>
                <p style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); line-height: 1.8;">
                    Connecting donors with those in need. Our platform makes blood donation and requests simple, efficient, and transparent. Save lives today.
                </p>
                <div class="d-flex gap-sm mt-3">
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--white); transition: var(--transition-fast);"
                        onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--white); transition: var(--transition-fast);"
                        onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--white); transition: var(--transition-fast);"
                        onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: var(--white); transition: var(--transition-fast);"
                        onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 style="color: var(--white); font-weight: var(--font-weight-semibold); margin-bottom: var(--spacing-md); text-transform: uppercase; letter-spacing: 0.5px; font-size: var(--font-sm);">
                    Quick Links
                </h6>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: var(--spacing-sm);">
                        <a href="<?php echo $base_path; ?>index.php" style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); transition: var(--transition-fast);"
                            onmouseover="this.style.color='var(--white)'; this.style.paddingLeft='8px'"
                            onmouseout="this.style.color='rgba(255,255,255,0.9)'; this.style.paddingLeft='0'">
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px;"></i>Home
                        </a>
                    </li>
                    <li style="margin-bottom: var(--spacing-sm);">
                        <a href="<?php echo $base_path; ?>about.php" style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); transition: var(--transition-fast);"
                            onmouseover="this.style.color='var(--white)'; this.style.paddingLeft='8px'"
                            onmouseout="this.style.color='rgba(255,255,255,0.9)'; this.style.paddingLeft='0'">
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px;"></i>About Us
                        </a>
                    </li>
                    <li style="margin-bottom: var(--spacing-sm);">
                        <a href="<?php echo $base_path; ?>contact.php" style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); transition: var(--transition-fast);"
                            onmouseover="this.style.color='var(--white)'; this.style.paddingLeft='8px'"
                            onmouseout="this.style.color='rgba(255,255,255,0.9)'; this.style.paddingLeft='0'">
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px;"></i>Contact
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $base_path; ?>register.php" style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); transition: var(--transition-fast);"
                            onmouseover="this.style.color='var(--white)'; this.style.paddingLeft='8px'"
                            onmouseout="this.style.color='rgba(255,255,255,0.9)'; this.style.paddingLeft='0'">
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px;"></i>Register
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Services -->
            <div class="col-lg-3 col-md-6">
                <h6 style="color: var(--white); font-weight: var(--font-weight-semibold); margin-bottom: var(--spacing-md); text-transform: uppercase; letter-spacing: 0.5px; font-size: var(--font-sm);">
                    Services
                </h6>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: var(--spacing-sm);">
                        <a href="#" style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); transition: var(--transition-fast);"
                            onmouseover="this.style.color='var(--white)'; this.style.paddingLeft='8px'"
                            onmouseout="this.style.color='rgba(255,255,255,0.9)'; this.style.paddingLeft='0'">
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px;"></i>Search Blood
                        </a>
                    </li>
                    <li style="margin-bottom: var(--spacing-sm);">
                        <a href="#" style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); transition: var(--transition-fast);"
                            onmouseover="this.style.color='var(--white)'; this.style.paddingLeft='8px'"
                            onmouseout="this.style.color='rgba(255,255,255,0.9)'; this.style.paddingLeft='0'">
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px;"></i>Request Blood
                        </a>
                    </li>
                    <li style="margin-bottom: var(--spacing-sm);">
                        <a href="#" style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); transition: var(--transition-fast);"
                            onmouseover="this.style.color='var(--white)'; this.style.paddingLeft='8px'"
                            onmouseout="this.style.color='rgba(255,255,255,0.9)'; this.style.paddingLeft='0'">
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px;"></i>Donate Blood
                        </a>
                    </li>
                    <li>
                        <a href="#" style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); transition: var(--transition-fast);"
                            onmouseover="this.style.color='var(--white)'; this.style.paddingLeft='8px'"
                            onmouseout="this.style.color='rgba(255,255,255,0.9)'; this.style.paddingLeft='0'">
                            <i class="fas fa-chevron-right" style="font-size: 0.7rem; margin-right: 8px;"></i>Blood Stock
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h6 style="color: var(--white); font-weight: var(--font-weight-semibold); margin-bottom: var(--spacing-md); text-transform: uppercase; letter-spacing: 0.5px; font-size: var(--font-sm);">
                    Contact Info
                </h6>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: var(--spacing-md); color: rgba(255,255,255,0.9); font-size: var(--font-sm); display: flex; gap: var(--spacing-sm);">
                        <i class="fas fa-map-marker-alt" style="margin-top: 4px;"></i>
                        <span>123 Blood Bank Street, Medical District, City 12345</span>
                    </li>
                    <li style="margin-bottom: var(--spacing-md); color: rgba(255,255,255,0.9); font-size: var(--font-sm); display: flex; gap: var(--spacing-sm);">
                        <i class="fas fa-phone" style="margin-top: 4px;"></i>
                        <span>+1 (234) 567-8900</span>
                    </li>
                    <li style="margin-bottom: var(--spacing-md); color: rgba(255,255,255,0.9); font-size: var(--font-sm); display: flex; gap: var(--spacing-sm);">
                        <i class="fas fa-envelope" style="margin-top: 4px;"></i>
                        <span>info@bloodbank.com</span>
                    </li>
                    <li style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); display: flex; gap: var(--spacing-sm);">
                        <i class="fas fa-clock" style="margin-top: 4px;"></i>
                        <span>24/7 Emergency Service</span>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.2); text-align: center;">
            <p style="color: rgba(255,255,255,0.9); font-size: var(--font-sm); margin: 0;">
                &copy; <?php echo $current_year; ?> Blood Bank Management System. All Rights Reserved.
                <span style="margin: 0 8px;">|</span>
                <a href="#" style="color: rgba(255,255,255,0.9); transition: var(--transition-fast);" onmouseover="this.style.color='var(--white)'" onmouseout="this.style.color='rgba(255,255,255,0.9)'">Privacy Policy</a>
                <span style="margin: 0 8px;">|</span>
                <a href="#" style="color: rgba(255,255,255,0.9); transition: var(--transition-fast);" onmouseover="this.style.color='var(--white)'" onmouseout="this.style.color='rgba(255,255,255,0.9)'">Terms of Service</a>
            </p>
            <p style="color: rgba(255,255,255,0.7); font-size: 0.8rem; margin-top: 8px;">
                <i class="fas fa-heart" style="color: #FFD700; animation: heartbeat 1.5s infinite;"></i>
                Made with love to save lives
            </p>
        </div>
    </div>
</footer>

<style>
    @keyframes heartbeat {

        0%,
        100% {
            transform: scale(1);
        }

        25% {
            transform: scale(1.2);
        }

        50% {
            transform: scale(1);
        }
    }
</style>

<!-- Scroll to Top Button -->
<button id="scrollToTop"
    style="position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; background: var(--primary-gradient); color: var(--white); border: none; border-radius: 50%; box-shadow: var(--shadow-lg); cursor: pointer; display: none; z-index: 1000; transition: var(--transition-normal);"
    onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
    <i class="fas fa-arrow-up"></i>
</button>

<script>
    // Scroll to top button visibility
    window.addEventListener('scroll', function() {
        const scrollButton = document.getElementById('scrollToTop');
        if (scrollButton) {
            if (window.pageYOffset > 300) {
                scrollButton.style.display = 'flex';
                scrollButton.style.alignItems = 'center';
                scrollButton.style.justifyContent = 'center';
            } else {
                scrollButton.style.display = 'none';
            }
        }
    });

    // Hover effect for scroll button
    const scrollButton = document.getElementById('scrollToTop');
    if (scrollButton) {
        scrollButton.addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-5px)';
        });
        scrollButton.addEventListener('mouseout', function() {
            this.style.transform = 'translateY(0)';
        });
    }
</script>

</body>

</html>