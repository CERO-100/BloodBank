<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>LifeLink Blood Bank | About</title>
    <?php include 'head.php'; ?>

    <link rel="stylesheet" href="style.css">

    <style>
        /* Header Styling */
        .header {
            background: linear-gradient(90deg, #b30000, #ff4d4d);
            color: #fff;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        .header p {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* About Section */
        .about-section {
            padding: 50px 15px;
            background-color: #f9f9f9;
            text-align: center;
        }

        .section-title {
            color: #b30000;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .about-section p {
            max-width: 800px;
            margin: 10px auto;
            font-size: 1.1rem;
            color: #555;
            line-height: 1.6;
        }

        .about-image {
            margin-top: 30px;
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .about-image:hover {
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-title {
                font-size: 1.6rem;
            }

            .about-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php 
    
    require 'header.php';
    ?>

    <!-- Header -->
    <header class="header">
        <h1>LifeLink Blood Bank</h1>
        <p>About Us</p>
    </header>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <h2 class="section-title">Saving Lives Through Safe Blood Donation</h2>
            <p>For over two decades, LifeLink Blood Bank has been a trusted guardian of life, connecting generous donors with patients in critical need. We operate with the highest medical standards, ensuring every unit of blood is safe, tested, and ready to save lives.</p>
            <p><strong>Mission:</strong> To provide safe, reliable blood products while building a community of life-saving donors.</p>
            <img src="assets/images/favicon.jpg" alt="Blood donation" class="about-image">
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

</body>
</html>
