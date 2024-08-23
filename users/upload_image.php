<?php
session_start();
include('../config/connect.php');
global $conn;
$email = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
echo $user_id;
echo "<br>";
echo $email;
echo "<br>";

$target_dir = "./uploads/";
$filename = basename($_FILES["fileToUpload"]["name"]);
$unique_image_name = pathinfo($filename, PATHINFO_FILENAME) . '_' . $user_id . '.' . pathinfo($filename,
        PATHINFO_EXTENSION);
$target_file = $target_dir . $unique_image_name;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //Insert image into database
        $insert_image = "INSERT INTO `image` (image_name, user_id ) VALUES (:image_name, :user_id)";
        $stmt = $conn->prepare($insert_image);
        $stmt->bindParam(':image_name', $unique_image_name);
        $stmt->bindParam(':user_id', $user_id);
        if ($stmt->execute()) {
            echo "File " . htmlspecialchars($filename) . " is successfully uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}