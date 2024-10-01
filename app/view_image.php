<?php
if (isset($_GET['image'])) {
    $image = $_GET['image'];

    // Check if the image parameter is a URL (RFI)
    if (filter_var($image, FILTER_VALIDATE_URL)) {
        // Remote File Inclusion (RFI)
        $imagePath = $image;
    } else {
        // Local File Inclusion (LFI)
        $imagePath = 'images/' . $image;
    }

    // Read and output the file content
    if (file_exists($imagePath) || filter_var($imagePath, FILTER_VALIDATE_URL)) {
        // Check if the file is an image
        $imageInfo = @getimagesize($imagePath);
        if ($imageInfo !== false) {
            header('Content-Type: ' . $imageInfo['mime']);
            readfile($imagePath);
            exit;
        } else {
            // If not an image, still try to read and display the content (for LFI/RFI)
            $content = @file_get_contents($imagePath);
            if ($content !== false) {
                echo nl2br(htmlspecialchars($content));
            } else {
                echo "File not found.";
            }
        }
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}
?>
