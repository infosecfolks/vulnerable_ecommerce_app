<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $domain = $_POST['domain'];

    // OS Command Injection vulnerability: Unsafe usage of domain input
    $output = shell_exec("ping -c 4 $domain 2>&1");
    
    echo $output;
}
?>
