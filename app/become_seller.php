<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $gst_number = $_POST['gst_number'];
    $product_catalog = $_POST['product_catalog'];

    // Display browser level popup
    echo "<script>alert('Your seller enrollment request has been submitted.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Become a Seller</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* Ensure the page takes up the full height */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        /* Make the main content grow to fill available space */
        .content {
            flex: 1;
        }

        /* Header and Footer styling */
        header, footer {
            background-color: Gainsboro;
            color: black;
        }

        /* Navbar menu styling */
        .navbar-nav .nav-link {
            color: black !important;
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Hover effect for menu items */
        .navbar-nav .nav-link:hover {
            background-color: #b0b0b0;
            color: white !important;
        }

        /* Center footer content */
        footer {
            text-align: center;
            padding: 20px 0;
        }

        footer h5 {
            margin-bottom: 10px;
        }

        footer div a {
            margin: 0 15px;
        }
    </style>
    <script>
        function pingDomain() {
            const domain = document.getElementById('domain').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'ping.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('pingOutput').innerHTML = `<pre>${xhr.responseText}</pre>`;
                }
            };
            xhr.send('domain=' + encodeURIComponent(domain));
        }

        function parseXML() {
            const productCatalog = document.getElementById('product_catalog').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'parse_xml.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('xmlOutput').innerHTML = `<pre>${xhr.responseText}</pre>`;
                }
            };
            xhr.send('product_catalog=' + encodeURIComponent(productCatalog));
        }
    </script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="index.php">INFOSEC FOLKS E-Commerce</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="shopping.php">Shopping</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item"><a class="nav-link" href="profile.php">My Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="become_seller.php?<?php echo session_name() . '=' . session_id(); ?>">Become a Seller</a></li>
                        <?php if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] === 'true'): ?>
                            <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container mt-5">
        <h1>Become a Seller</h1>
        <form method="post" action="become_seller.php">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" class="form-control" name="full_name" id="full_name" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" name="address" id="address" required>
            </div>
            <div class="form-group">
                <label for="gst_number">GST Number:</label>
                <input type="text" class="form-control" name="gst_number" id="gst_number" required>
            </div>
            <div class="form-group">
                <label for="product_catalog">Product Catalog (XML):</label>
                <textarea class="form-control" name="product_catalog" id="product_catalog" rows="3" required></textarea>
                <button type="button" class="btn btn-secondary mt-2" onclick="parseXML()">Parse XML</button>
                <div id="xmlOutput" class="mt-3"></div>
            </div>
            <div class="form-group">
                <label for="domain">Webiste Domain (If already selling online):</label>
                <input type="text" class="form-control" name="domain" id="domain" required>
                <button type="button" class="btn btn-secondary mt-2" onclick="pingDomain()">Ping Domain</button>
                <div id="pingOutput" class="mt-3"></div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
