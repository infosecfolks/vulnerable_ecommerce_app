<?php
// Update the hostname 'db' to match your MySQL service in docker-compose.yml
$servername = "db";  // Docker service name, should match service name in docker-compose.yml
$username = "root";
$password = "password";
$dbname = "vuln_ecommerce";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
