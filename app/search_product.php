<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: shopping.php");
    exit;
}

$product_id = $_GET['id']; // Vulnerable to SQL injection
$query = "SELECT * FROM products WHERE id = '$product_id'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="container mt-5">
        <h1>Product Details</h1>
        <?php
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo "<p>Product ID: " . $row['id'] . "<br>Product Name: " . $row['name'] . "<br>Description: " . $row['description'] . "<br>Price: $" . $row['price'] . "</p>";
        } else {
            echo "<p>No product found with ID: " . htmlspecialchars($product_id) . "</p>";
        }
        ?>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
