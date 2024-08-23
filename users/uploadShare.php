<?php
session_start();
include('../config/connect.php');
global $conn;

$email = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$friend_ids = $_SESSION['friend_ids'];
echo $user_id;
echo "<br>";
echo $email;
echo "<br>";

$target_dir = "./uploads/";
$uploadOk = 1;

if (isset($_POST["share_multi_images"]) && isset($_POST['friend_id'])) {
    $friend_id = $_POST['friend_id'];
    // Check whether any files were uploaded
    if (isset($_FILES['imagesToUpload']) && !empty($_FILES['imagesToUpload']['name'][0])) {
        $count_files = count($_FILES['imagesToUpload']['name']);

        for ($i = 0; $i < $count_files; $i++) {
            $filename = basename($_FILES["imagesToUpload"]["name"][$i]);
            echo "<b>Name of file: </b>" . $filename . "<br>";
            $unique_image_name = pathinfo($filename, PATHINFO_FILENAME) .
                '_' . $user_id . '.' . pathinfo($filename, PATHINFO_EXTENSION);
            $target_file = $target_dir . $unique_image_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["imagesToUpload"]["tmp_name"][$i]);

            if ($check !== false) {
                echo "File " . ($i + 1) . " is an image - " . $check["mime"] . "." . "<br>";
                $uploadOk = 1;
            } else {
                echo "File " . ($i + 1) . " is not an image." . "<br>";
                $uploadOk = 0;
            }

            if (file_exists($target_file)) {
                echo "Sorry, file " . ($i + 1) . " already exists." . "<br>";
                $uploadOk = 0;
            }

            if ($_FILES["imagesToUpload"]["size"][$i] > 500000) {
                echo "Sorry, file " . ($i + 1) . " is too large." . "<br>";
                $uploadOk = 0;
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, file " . ($i + 1) . " has an invalid file type." . "<br>";
                $uploadOk = 0;
            }

            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["imagesToUpload"]["tmp_name"][$i], $target_file)) {
                    // Insert image into database
                    $insert_image = "INSERT INTO `image` (image_name, user_id ) VALUES (:image_name, :user_id)";
                    $stmt = $conn->prepare($insert_image);
                    $stmt->bindParam(':image_name', $unique_image_name);
                    $stmt->bindParam(':user_id', $friend_id);
                    if ($stmt->execute()) {
                        echo "File " . htmlspecialchars($filename) . " is successfully uploaded." . "<br>";
                    } else {
                        echo "Sorry, there was an error uploading file " . htmlspecialchars($filename) . "." . "<br>";
                    }
                }
            }
        }
    } else {
        echo "No files were uploaded. Try to upload files first.";
    }
} else {
    echo "Error in Form submission.";
}

