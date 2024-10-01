<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['user_id']); // IDOR Vulnerability
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']); // CSRF Vulnerability

    $query = "UPDATE infosecfolks_users SET password='$new_password' WHERE id=$user_id";
    if (mysqli_query($conn, $query)) {
        echo "Password updated successfully!";
    } else {
        echo "Error updating password: " . mysqli_error($conn);
    }
}
?>
