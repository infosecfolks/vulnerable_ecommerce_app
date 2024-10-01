<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    
    // Fetch the username from the session
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonymous';

    // Insert feedback along with the username into the database
    $query = "INSERT INTO feedback (product_id, username, comment) VALUES ('$product_id', '$username', '$feedback')";
    
    if (mysqli_query($conn, $query)) {
        // Feedback successfully inserted, redirect to the shopping page
        header("Location: shopping.php?product_id=$product_id");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
