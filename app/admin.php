<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_COOKIE['is_admin']) || $_COOKIE['is_admin'] !== 'true') {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Check if the user is an admin
if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] === 'true') {
    echo "<h1>Welcome to the Admin Panel!</h1>";
    // Admin functionalities go here...
} else {
    echo "<h1>Access Denied. You are not an admin.</h1>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container mt-5">
        <h1>Admin Page</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?>! You have admin access.</p>
        <p>Here you can manage the application and view sensitive information.</p>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
