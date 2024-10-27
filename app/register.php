<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $security_question = $_POST['security_question'];
    $security_answer = $_POST['security_answer'];

    // Vulnerable to SQL injection
    $query = "INSERT INTO users (username, email, mobile, password, security_question, security_answer, wallet_balance) VALUES ('$username', '$email', '$mobile', '$password', '$security_question', '$security_answer', 5000.00)";
    if (mysqli_query($conn, $query)) {
        header("Location: login.php");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
        <h1>Register</h1>
        <form method="post" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="text" class="form-control" name="mobile" id="mobile" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="security_question">Security Question:</label>
                <select class="form-control" name="security_question" id="security_question" required>
                    <option value="1">What is your favorite color?</option>
                    <option value="2">Who is your favorite in sports?</option>
                    <option value="3">What is your pet name?</option>
                    <option value="4">What is the first school you have attended?</option>
                    <option value="5">Which year were you born?</option>
                    <option value="6">What is your maiden name?</option>
                </select>
            </div>
            <div class="form-group">
                <label for="security_answer">Security Answer:</label>
                <input type="text" class="form-control" name="security_answer" id="security_answer" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
