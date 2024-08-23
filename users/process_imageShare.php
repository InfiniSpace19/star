<?php
session_start();
include('../config/connect.php');
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST["friend_id"])) {
    $friend_id = $_POST["friend_id"];
//    echo $friend_id;
    $shared_image_id = $_SESSION["shared_image_id"];
//    echo $shared_image_id;
    //Fetch image name
    $select_image ="SELECT Image_name FROM `image` WHERE ID='$shared_image_id'";
    $result = $conn->query($select_image);
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $row = $result->fetch();
    $image_name = $row["Image_name"];
//    echo $image_name;
    //Copy image to friend
    $share_image_sql = "INSERT INTO `image` (`Image_name`, `User_id`) VALUES (:image_name, :user_id)";
    $stmt = $conn->prepare($share_image_sql);
    $stmt->bindParam(':image_name', $image_name);
    $stmt->bindParam(':user_id', $friend_id);
    if($stmt->execute()) {
        echo "Image " . $image_name . " shared successfully with " .$friend_id."<br>";
    } else {
        echo "Error: " . $share_image_sql . "<br>" . $conn->error;
    }
}