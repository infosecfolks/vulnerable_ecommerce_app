<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $new_password = $_POST['new_password'];
    $username = $_SESSION['username'];
    $query = "UPDATE users SET password='$new_password' WHERE username='$username'";
    mysqli_query($conn, $query);
    echo "Password updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Update Password</h1>
        </header>
        <main>
            <form method="post" action="update_password.php">
                <input type="password" name="new_password" placeholder="New Password" required><br>
                <input type="submit" name="update_password" value="Update Password">
            </form>
            <a href="dashboard.php">Go back to Dashboard</a>
        </main>
    </div>
</body>
</html>
