
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#C41E3A;">
  <div class="container">
    <!-- Logo / Brand -->
    <a class="navbar-brand fw-bold" href="index.php">
      <img src="assets/images/favicon.jpg" alt="Logo" width="40" height="40" class="d-inline-block align-text-middle me-2">
      Blood Bank
    </a>

    <!-- Mobile toggle button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="login.php" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            Login
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="login.php?role=user">User Login</a></li>
            <li><a class="dropdown-item" href="login.php?role=hospital">Hospital Login</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="register.php" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            Register
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="register.php?role=user">User Register</a></li>
            <li><a class="dropdown-item" href="register.php?role=hospital">Hospital Register</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
