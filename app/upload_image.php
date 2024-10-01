<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

// Unrestricted File Upload Vulnerability
if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars(basename($_FILES["profile_image"]["name"])). " has been uploaded.";
} else {
    echo "Sorry, there was an error uploading your file.";
}
?>
