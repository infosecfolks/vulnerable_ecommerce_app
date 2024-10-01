<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Vulnerable E-Commerce Application</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">INFOSEC_FOLKS E-Commerce</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shopping.php">Shopping</a>
                    </li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="become_seller.php?<?php echo session_name() . '=' . session_id(); ?>">Become a Seller</a>
                        </li>
                        <?php if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] === 'true'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php">Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <h2>Welcome to Vulnerable E-Commerce Web Application</h2>
        <p>This lab is designed for educational purposes, to help you understand how to discover and exploit vulnerabilities in real-world applications. Do not attempt these approaches on live applications hosted on the internet without proper consent.</p>
        <p>This application is designed & maintained by "INFOSEC FOLKS" security community. You can find the source image on <a href="https://infosecfolks.org">INFOSEC FOLKS</a>.</p>
        <div style="text-align: center;">
    <img src="logo.jpg" alt="Logo" height="150" width="150" />
</div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <footer style="background-color: Gainsboro; padding: 10px; text-align: center; color: black; margin-top: 100px;">
    <h5>Stay Connected</h5>
    <div>
        <a href="https://infosecfolks.org" target="_blank" style="margin: 0 10px; color: black;">
            <i class="fas fa-globe fa-2x"></i>
        </a>
        <a href="https://www.linkedin.com/company/infosecfolks-global/" target="_blank" style="margin: 0 10px; color: black;">
            <i class="fab fa-linkedin fa-2x"></i>
        </a>
        <a href="https://www.youtube.com/@infosecfolks-global" target="_blank" style="margin: 0 10px; color: black;">
            <i class="fab fa-youtube fa-2x"></i>
        </a>
    </div>
</footer>

</body>
</html>
