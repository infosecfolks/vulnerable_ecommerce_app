<?php
session_start();
include 'db.php';

// Generate a new CAPTCHA value (e.g., a random number) on each load
$new_captcha = rand(1000, 9999); // Simple numeric CAPTCHA

// Store the new CAPTCHA and keep history of previous ones
if (!isset($_SESSION['captcha_history'])) {
    $_SESSION['captcha_history'] = [];
}
$_SESSION['captcha_history'][] = $new_captcha;

// Update the current CAPTCHA to be displayed
$_SESSION['captcha'] = $new_captcha;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha_input = $_POST['captcha'];

    // Vulnerable CAPTCHA validation logic
    if (in_array($captcha_input, $_SESSION['captcha_history'])) {
        // CAPTCHA is correct (even if it's an old one, due to replay attack vulnerability)
        // Proceed with login logic

        // SQL Injection Vulnerability in Login Query
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Set session variables
            $_SESSION['username'] = $user['username'];
            
            // Fetch the domain dynamically based on the host
	    $cookieDomain = ($_SERVER['HTTP_HOST'] !== 'localhost') ? $_SERVER['HTTP_HOST'] : true;

            // Handle insecure deserialization for admin privileges
            $user_preferences = [
                'role' => $user['role']
            ];
            $serialized_preferences = serialize($user_preferences);
            setcookie('user_prefs', $serialized_preferences, time() + (86400 * 30), "/", "", false, false); // 30-day cookie

            // Set is_admin cookie based on user privilege
            $isAdmin = ($user['role'] === 'admin') ? 'true' : 'false';
            setcookie('is_admin', $isAdmin, time() + (86400 * 30), "/", "", false, false); // 30-day cookie

            // Redirect to admin page if the user is an admin
            if ($isAdmin === 'true') {
                header("Location: admin.php");
                exit;
            }

            // Otherwise, redirect to the index page
            header("Location: index.php");
            exit;
        } else {
            echo "Invalid login credentials.";
        }
    } else {
        echo "Invalid CAPTCHA. Please try again.";
    }
}
?>

	<?php
	if (isset($_GET['redirect'])) {
    	    $redirect = $_GET['redirect'];
    	    echo "<script>window.location.href='$redirect';</script>";
    	    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
</head>
<body>
    <header>
       <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="index.php">INFOSEC FOLKS E-Commerce</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shopping.php">Shopping</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <h2>Login</h2>
        <form method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <!-- CAPTCHA field -->
            <div class="form-group">
                <label for="captcha">Enter the CAPTCHA: <?php echo $_SESSION['captcha']; ?></label>
                <input type="text" class="form-control" id="captcha" name="captcha" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
            
            <!-- Forget Password Button -->
            <div class="form-group">
	    <a href="?redirect=forgot_password.php" class="btn btn-warning">Forgot Password?</a>
	    </div>
        </form>
        

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
