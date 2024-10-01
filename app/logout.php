<?php
session_start();
//session_unset();
//session_destroy();

// Clear cookies
setcookie('user_session', '', time() - 3600, "/");
setcookie('is_admin', '', time() - 3600, "/");

header("Location: login.php");
exit;
?>
