<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping</title>
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

    <div class="container mt-5">
        <h2>Shopping Page</h2>

        <!-- Reflected XSS Vulnerability -->
        <form method="get" action="shopping.php">
            <div class="form-group">
                <label for="search_product">Search by Product Name:</label>
                <input type="text" class="form-control" id="search_product" name="search_product" value="<?php echo isset($_GET['search_product']) ? $_GET['search_product'] : ''; ?>">
                <button type="submit" class="btn btn-primary mt-2">Search</button>
            </div>
        </form>

	<?php

        

        // Check if 'search_product' exists in the request
        if (isset($_GET['search_product']) && $_GET['search_product'] !== '') {
            $search_input = $_GET['search_product'];

            // Display user input as-is (vulnerable to XSS)
            echo "<div class='alert alert-info'>Search Results for: " . $search_input . "</div>";

            // Directly reflect input inside the script tag
            echo "<script>" . $search_input . "</script>";
}

?>

        <!-- SQL Injection Vulnerability -->
        <form method="get" action="shopping.php">
            <div class="form-group">
                <label for="product_id">Search by Product ID:</label>
                <input type="text" class="form-control" id="product_id" name="product_id">
                <button type="submit" class="btn btn-primary mt-2">Search</button>
            </div>
        </form>

        <!-- Display Products -->
        <div class="row">
            <?php
            // SQL Injection Vulnerability Example
            if (isset($_GET['product_id'])) {
                $product_id = $_GET['product_id'];
                $query = "SELECT * FROM products WHERE id = '$product_id'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="col-md-4">
                        <div class="card">
                            <img src="view_image.php?image=' . urlencode($row['image']) . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '">
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
                                <p class="card-text">Price: $' . htmlspecialchars($row['price']) . '</p>
                                <form method="post" action="checkout.php">
                                    <input type="hidden" name="product_name" value="' . $row['name'] . '">
                                    <input type="hidden" name="price" value="' . $row['price'] . '">
                                    <div class="form-group">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" class="form-control" name="quantity" value="1" min="1">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Buy Now</button>
                                </form>
                                
                                <!-- Stored XSS Feedback -->
                                <form method="post" action="shopping.php">
                                    <div class="form-group mt-3">
                                        <label for="feedback_' . htmlspecialchars($row['id']) . '">Feedback:</label>
                                        <textarea class="form-control" id="feedback_' . htmlspecialchars($row['id']) . '" name="feedback_' . htmlspecialchars($row['id']) . '" rows="3"></textarea>
                                        <button type="submit" class="btn btn-secondary mt-2">Submit Feedback</button>
                                    </div>
                                    <input type="hidden" name="product_id" value="' . htmlspecialchars($row['id']) . '">
                                </form>

                                <!-- Displaying Feedback -->
                                <div class="mt-3">
                                    <h6>Customer Feedback:</h6>';
                                    // Fetch and display feedback for the product
                                    $feedback_query = "SELECT * FROM feedback WHERE product_id = '" . $row['id'] . "'";
                                    $feedback_result = mysqli_query($conn, $feedback_query);
                                    while ($feedback_row = mysqli_fetch_assoc($feedback_result)) {
                                        echo '<p><strong>' . $feedback_row['username'] . '</strong>: ' . $feedback_row['comment'] . '</p>';
                                    }
                                    echo '
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                // Display all products if no specific product ID is searched
                $query = "SELECT * FROM products";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '
                    <div class="col-md-4">
                        <div class="card">
                            <img src="view_image.php?image=' . urlencode($row['image']) . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '">
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
                                <p class="card-text">Price: $' . htmlspecialchars($row['price']) . '</p>
                                <form method="post" action="checkout.php">
                                    <input type="hidden" name="product_name" value="' . $row['name'] . '">
                                    <input type="hidden" name="price" value="' . $row['price'] . '">
                                    <div class="form-group">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" class="form-control" name="quantity" value="1" min="1">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Buy Now</button>
                                </form>
                                
                                <!-- Stored XSS Feedback -->
                                <form method="post" action="shopping.php">
                                    <div class="form-group mt-3">
                                        <label for="feedback_' . htmlspecialchars($row['id']) . '">Feedback:</label>
                                        <textarea class="form-control" id="feedback_' . htmlspecialchars($row['id']) . '" name="feedback_' . htmlspecialchars($row['id']) . '" rows="3"></textarea>
                                        <button type="submit" class="btn btn-secondary mt-2">Submit Feedback</button>
                                    </div>
                                    <input type="hidden" name="product_id" value="' . htmlspecialchars($row['id']) . '">
                                </form>

                                <!-- Displaying Feedback -->
                                <div class="mt-3">
                                    <h6>Customer Feedback:</h6>';
                                    // Fetch and display feedback for the product
                                    $feedback_query = "SELECT * FROM feedback WHERE product_id = '" . $row['id'] . "'";
                                    $feedback_result = mysqli_query($conn, $feedback_query);
                                    while ($feedback_row = mysqli_fetch_assoc($feedback_result)) {
                                        echo '<p><strong>' . $feedback_row['username'] . '</strong>: ' . $feedback_row['comment'] . '</p>';
                                    }
                                    echo '
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }


            // Process Feedback Submission (Stored XSS)
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
                $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
                $feedback = mysqli_real_escape_string($conn, $_POST['feedback_' . $product_id]);  // Intentionally vulnerable to XSS
                $query = "INSERT INTO feedback (product_id, comment) VALUES ('$product_id', '$feedback')";
                mysqli_query($conn, $query);
                echo "<script>alert('" . $feedback . "');</script>";  // Trigger XSS on feedback submission
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

