<?php
// Start the session at the very beginning
session_start();
error_reporting(0);  // Disable warnings or errors that interfere with headers
ob_start(); 


// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login if the session is not set
    header("Location: login.php");
    exit;
}

// Include the database connection
include 'db.php';

// Retrieve the logged-in user's details
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // If user not found in the database, destroy the session and redirect to login
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$user = mysqli_fetch_assoc($result);

// Initialize variables for user data and messages
$userData = null;
$successMessage = "";
$errorMessage = "";

// Handle IDOR for viewing profile details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['view_user_details'])) {
    $user_id = intval($_POST['user_id']); // IDOR vulnerability
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    } else {
        $errorMessage = "User not found!";
    }
}

// Handle password change (CSRF vulnerability)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    $user_id = intval($_POST['user_id']); // CSRF vulnerability
    $query = "UPDATE users SET password='$new_password' WHERE id=$user_id";
    if (mysqli_query($conn, $query)) {
        $successMessage = "Password updated successfully!";
    } else {
        $errorMessage = "Error updating password: " . mysqli_error($conn);
    }
}

// Handle file upload (Unrestricted File Upload vulnerability)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

    // Ensure the uploads directory exists and set permissions
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);  // Create directory if it doesn't exist
        chmod($target_dir, 0777);        // Ensure the directory has proper permissions
    }

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $successMessage = "File uploaded successfully: " . htmlspecialchars($target_file);
    } else {
        $errorMessage = "Sorry, there was an error uploading your file.";
    }
}


// Handle fetching image from URL (SSRF vulnerability)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fetch_image'])) {
    $image_url = $_POST['image_url'];
    $image_content = file_get_contents($image_url); // SSRF vulnerability
    if ($image_content) {
        file_put_contents('uploads/remote_image.jpg', $image_content);
        $successMessage = "Image fetched from remote URL and saved as uploads/remote_image.jpg.";
    } else {
        $errorMessage = "Sorry, there was an error fetching the image.";
    }
}

// Simulate storing user preferences in a serialized object
$user_preferences = [
    'theme' => 'dark',
    'notifications' => 'enabled',
    'role' => 'user'
];

$serialized_preferences = serialize($user_preferences);
setcookie('user_prefs', $serialized_preferences, time() + (86400 * 30), "/"); // 30-day cookie

// Deserializing the cookie
if (isset($_COOKIE['user_prefs'])) {
    $deserialized_preferences = unserialize($_COOKIE['user_prefs']);
    
    // Check for role after deserialization (vulnerable to tampering)
    if ($deserialized_preferences['role'] === 'admin') {
        setcookie('is_admin', 'true', time() + (86400 * 30), "/");
        header("Location: admin.php");
        exit;
    }
    
    // Display user preferences
    echo "<h2>Your Preferences:</h2>";
    echo "<p>Theme: " . htmlspecialchars($deserialized_preferences['theme']) . "</p>";
    echo "<p>Notifications: " . htmlspecialchars($deserialized_preferences['notifications']) . "</p>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
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
        <h1>My Profile</h1>

        <h2>View Profile Details</h2>
        <form method="post" action="profile.php">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>"> <!-- IDOR vulnerability -->
            <button type="submit" name="view_user_details" class="btn btn-primary">View Profile Details</button>
        </form>

        <?php if ($userData): ?>
            <h3>Profile Details</h3>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($userData['mobile']); ?></p>
            <p><strong>Wallet-Balance:</strong> <?php echo htmlspecialchars($userData['wallet_balance']); ?></p>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="alert alert-success mt-4">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php elseif ($errorMessage): ?>
            <div class="alert alert-danger mt-4">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <h2 class="mt-5">Change Password</h2>
        <form method="post" action="profile.php">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>"> <!-- IDOR vulnerability -->
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
        </form>

        <h2 class="mt-5">Update Profile Picture</h2>
        <form method="post" enctype="multipart/form-data" action="profile.php">
            <div class="form-group">
                <label for="profile_image">Upload Photo:</label>
                <input type="file" class="form-control" name="profile_image" id="profile_image">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <h2 class="mt-5">Fetch Image from URL</h2>
        <form method="post" action="profile.php">
            <div class="form-group">
                <label for="image_url">Remote Image URL:</label>
                <input type="text" class="form-control" id="image_url" name="image_url" required>
            </div>
            <button type="submit" name="fetch_image" class="btn btn-primary">Fetch Image</button>
        </form>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
