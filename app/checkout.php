<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price']; // This should be passed as a hidden field in the form and is vulnerable to tampering
    $quantity = $_POST['quantity']; // This should also be passed as a form field

    // Calculate the total price (vulnerable to tampering)
    $total_price = $price * $quantity;

    // Store the order in the database (for demonstration purposes)
    $query = "INSERT INTO orders (username, product_name, quantity, total_price) VALUES ('{$_SESSION['username']}', '$product_name', '$quantity', '$total_price')";
    mysqli_query($conn, $query);

    echo "<h2>Checkout Summary</h2>";
    echo "<p>Product: " . htmlspecialchars($product_name) . "</p>";
    echo "<p>Quantity: " . htmlspecialchars($quantity) . "</p>";
    echo "<p>Total Amount: $" . htmlspecialchars($total_price) . "</p>";
    echo "<p>Mode: Cash on Delivery</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <a href="shopping.php" class="btn btn-secondary">Back to Shopping</a>
    </div>
</body>
</html>
