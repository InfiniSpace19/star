<?php
include('../config/connect.php');
global $conn;
function downloadFile($filePath) {
    if (file_exists($filePath)) {
    $fileName = basename($filePath);
    $fileLength = filesize($filePath);

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header("Content-Length: $fileLength");
    header("Content-Description: File Transfer");
    header("Expires: 0");
    header("Cache-Control: must-revalidate");
    header("Pragma: private");

    ob_clean();
    flush();
    readfile($filePath);
    flush();
    exit();
} else {
    echo "Error: File does not exist.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST["file_id"])) {
    $file_id = $_POST["file_id"];
    // Fetch image name from the database
    $fetch_image = "SELECT image_name FROM image WHERE ID = :file_id";
    $stmt = $conn->prepare($fetch_image);
    $stmt->bindParam(':file_id', $file_id);
    $stmt->execute();
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($file) {
        $fileSource = 'uploads/' . $file["image_name"];;
        echo $fileSource . "<br>";
        if (!empty($fileSource)) {
        downloadFile($fileSource);
    } else {
            echo "Error: No File Selected.";
        }
    }
} else {
    echo "Error in Form submission, or invalid request.";
}

