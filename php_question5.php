/*
Asynchronous Processing
A user uploads a file, and your PHP script processes it. How would you handle this asynchronously to prevent timeout issues?
*/

<?php
// Ensure the uploads directory exists
$uploadDir = __DIR__ . "/uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Check if a file is uploaded
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $filePath = $uploadDir . basename($_FILES['file']['name']);

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
        // Run file processing in the background
        $command = "php -r 'sleep(10); file_put_contents(\"processing.log\", \"Processed: {$filePath}\\n\", FILE_APPEND);' > /dev/null 2>&1 &";
        exec($command);

        echo "File uploaded successfully. Processing in the background.";
    } else {
        echo "File upload failed.";
    }
} else {
    echo "No file uploaded or an error occurred.";
}
?>
