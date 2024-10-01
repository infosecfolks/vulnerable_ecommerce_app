<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';
include 'db.php';

$search = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = $_POST['search'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Search for Products</h1>
        </header>
        <main>
            <form method="post" action="search.php">
                <input type="text" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>"><br>
                <input type="submit" name="search" value="Search">
            </form>
            <?php
            if ($search) {
                $query = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>" . htmlspecialchars($row['name']) . ": " . htmlspecialchars($row['description']) . "</p>";
                }
            }
            ?>
            <a href="dashboard.php">Go to Dashboard</a>
        </main>
    </div>
</body>
</html>
