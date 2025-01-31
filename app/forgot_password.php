<?php
include 'db.php';

$step = 1;
$message = '';
$stored_username = '';
$security_question = '';
$security_answer = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'])) {
        // Step 1: User submits the username
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $query = "SELECT security_question, security_answer FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $security_question = $user['security_question'];
            $stored_username = $username; // Store the username for later steps
            $security_answer = $user['security_answer'];
            $step = 2;
        } else {
            $message = "Username not found.";
        }
    } elseif (isset($_POST['security_answer']) && isset($_POST['stored_username']) && isset($_POST['security_question'])) {
        // Step 2: User submits the answer to the security question
        $stored_username = mysqli_real_escape_string($conn, $_POST['stored_username']);
        $security_question = mysqli_real_escape_string($conn, $_POST['security_question']);
        $submitted_answer = mysqli_real_escape_string($conn, $_POST['security_answer']);

        // Fetch the correct answer from the database
        $query = "SELECT security_answer FROM users WHERE username = '$stored_username'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if ($submitted_answer === $user['security_answer']) {
                $step = 3; // Correct answer, move to the next step
            } else {
                $message = "Incorrect answer to the security question.";
                $step = 2; // Stay on the same step
            }
        } else {
            $message = "An error occurred. Please try again.";
        }
    } elseif (isset($_POST['new_password']) && isset($_POST['stored_username'])) {
        // Step 3: User submits the new password
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $stored_username = mysqli_real_escape_string($conn, $_POST['stored_username']);
        $query = "UPDATE users SET password = '$new_password' WHERE username = '$stored_username'";
        if (mysqli_query($conn, $query)) {
            $message = "Password updated successfully!";
            header("Location: login.php"); // Redirect to login page
            exit;
        } else {
            $message = "Error updating password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Forgot Password</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($step == 1): ?>
            <form method="post">
                <div class="form-group">
                    <label for="username">Enter your username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <button type="submit" class="btn btn-primary">Next</button>
            </form>
            </br>
            <a href='login.php'>Go back to Login page</a>
        <?php elseif ($step == 2): ?>
            <form method="post">
                <div class="form-group">
                    <label for="security_question">Security Question:</label>
                    <input type="text" class="form-control" id="security_question" value="<?php echo htmlspecialchars($security_question); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="security_answer">Your Answer:</label>
                    <input type="text" class="form-control" id="security_answer" name="security_answer" required>
                </div>
                <input type="hidden" name="stored_username" value="<?php echo htmlspecialchars($stored_username); ?>">
                <input type="hidden" name="security_question" value="<?php echo htmlspecialchars($security_question); ?>">
                <button type="submit" class="btn btn-primary">Submit Answer</button>
            </form>
            </br>
            <a href='login.php'>Go back to Login page</a>
        <?php elseif ($step == 3): ?>
            <form method="post">
                <div class="form-group">
                    <label for="new_password">Enter your new password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <input type="hidden" name="stored_username" value="<?php echo htmlspecialchars($stored_username); ?>">
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
