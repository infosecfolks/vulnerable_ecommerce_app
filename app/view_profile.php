<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['user_id']); // IDOR Vulnerability
    
    $query = "SELECT * FROM users WHERE id=$user_id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        echo "Username: " . htmlspecialchars($user['username']) . "<br>";
        echo "Email: " . htmlspecialchars($user['email']) . "<br>";
        echo "Mobile: " . htmlspecialchars($user['mobile']) . "<br>";
        echo "Wallet-Balance: " . htmlspecialchars($user['wallet_balance']) . "<br>";
    } else {
        echo "User not found!";
    }
}
?>
