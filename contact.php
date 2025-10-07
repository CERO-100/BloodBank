<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php $title = "Bloodbank | Contact page"; ?>
  <?php require 'head.php'; ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Keep your external styles but the theme rules work independently -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    :root {
      --primary-red: #C41E3A;
      --secondary-blue: #2C5F8B;
      --background-light: #F8F9FA;
      --gray-light: #E9ECEF;
      --white: #FFF;
      --text-dark: #2D3748;
    }

    body {
      background: var(--background-light);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-dark);
    }

    .navbar {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.035);
    }

    .jumbotron {
      background: linear-gradient(90deg, var(--primary-red) 60%, var(--secondary-blue) 100%);
      color: var(--white);
      border-radius: 0 0 24px 24px;
      margin-bottom: 2rem;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.14);
    }

    .jumbotron h1 {
      font-weight: 800;
      font-size: 2.7rem;
      margin-bottom: 0.5rem;
    }

    .contact-section {
      background: var(--white);
      border-radius: 14px;
      box-shadow: 0 6px 20px rgba(67, 36, 100, 0.065);
      max-width: 630px;
      margin: 0 auto 2.2rem;
      padding: 2rem 1.5rem;
    }

    .contact-section h2 {
      font-size: 2rem;
      color: var(--primary-red);
      font-weight: 700;
      margin-bottom: 1.5rem;
    }

    .contact-list {
      margin: 0 auto;
      text-align: left;
    }

    .contact-item {
      display: flex;
      align-items: center;
      padding: 0.8rem 0 0.8rem 0.4rem;
      border-bottom: 1px solid var(--gray-light);
      font-size: 1.13rem;
    }

    .contact-item:last-child {
      border-bottom: none;
    }

    .contact-name {
      color: var(--secondary-blue);
      font-weight: 600;
      flex: 1 1 110px;
    }

    .contact-phone {
      margin-left: 10px;
      font-weight: 500;
      color: var(--primary-red);
      font-size: 1.08rem;
    }

    .contact-email {
      margin-left: 14px;
      font-size: 0.98rem;
      color: #465c8b;
    }

    @media (max-width: 650px) {
      .jumbotron {
        padding: 1.2rem 0.5rem;
      }

      .contact-section {
        padding: 1.1rem 0.4rem;
      }

      .contact-item {
        flex-direction: column;
        align-items: flex-start;
        font-size: 1rem;
      }

      .contact-phone,
      .contact-email {
        margin-left: 0;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--primary-red);">
    <a class="navbar-brand" href="#"><b>Blood Bank</b></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="main.php">Home</a></li>
        <li class="nav-item active"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php">Login/Register</a></li>
      </ul>
    </div>
  </nav>

  <div class="jumbotron text-center">
    <h1>Anytime!</h1>
    <p>We are always here to help you!</p>
  </div>

  <div class="contact-section">
    <h2 class="text-center">Contact Us</h2>
    <div class="contact-list">
      <div class="contact-item">
        <span class="contact-name">Arun </span>
        <span class="contact-phone">+91-9902477354</span>
        <span class="contact-email">arun@gmail.com</span>
      </div>
      <div class="contact-item">
        <span class="contact-name">Jerin</span>
        <span class="contact-phone">+91-9380073437</span>
        <span class="contact-email">jerin@gmail.com</span>
      </div>
      <div class="contact-item">
        <span class="contact-name">tito</span>
        <span class="contact-phone">+91-9110342158</span>
        <span class="contact-email">tito@gmail.com</span>
      </div>

    </div>
  </div>
  <?php require 'footer.php'; ?>
</body>

</html>