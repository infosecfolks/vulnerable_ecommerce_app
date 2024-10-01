<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_url = $_POST['image_url']; // SSRF Vulnerability
    
    $image_content = file_get_contents($image_url);
    if ($image_content) {
        file_put_contents('uploads/remote_image.jpg', $image_content);
        echo "Image fetched from remote URL and saved as uploads/remote_image.jpg.";
    } else {
        echo "Sorry, there was an error fetching the image.";
    }
}
?>
