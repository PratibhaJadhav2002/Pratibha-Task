/*
File Handling & Security
Write a secure PHP function to upload an image file, ensuring it prevents security risks like script execution.
*/

<?php
// Secure image upload function
function uploadImage($file, $uploadDir = "uploads/") {
    // Allowed MIME types (only images)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    // Validate if file was uploaded properly
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Error uploading file.";
    }

    // Verify MIME type
    $fileMime = mime_content_type($file['tmp_name']);
    if (!in_array($fileMime, $allowedTypes)) {
        return "Invalid file type.";
    }

    // Check file extension (secondary validation)
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        return "Invalid file extension.";
    }

    // Prevent oversized uploads (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        return "File is too large. Max 2MB allowed.";
    }

    // Ensure upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique file name to prevent overwriting
    $newFileName = uniqid() . '.' . $extension;
    $destination = $uploadDir . $newFileName;

    // Move file to the secure upload directory
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return "Failed to save file.";
    }

    return "File uploaded successfully: " . $newFileName;
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    echo uploadImage($_FILES['image']);
}
