<?php
function downloadFile($filePath)
{
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
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fileSource = $_FILES["fileToDownload"]["name"];
    echo $fileSource . "<br>";
    if (!empty($fileSource)) {
        downloadFile($fileSource);
    } else {
        echo "Error: No File Selected.";
    }
} else {
    echo "Error in Form submission, or invalid request.";
}