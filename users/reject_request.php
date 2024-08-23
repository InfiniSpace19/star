<?php
global $conn;
if (isset($_GET['reject_request']) && isset($_GET['receiver_id']) && isset($_SESSION['username'])) {
    $sender_id = $_GET['reject_request'];
    $receiver_id = $_GET['receiver_id'];

    $update_request = "UPDATE `request` SET Req_status='Rejected' WHERE  Sender_id='$sender_id' AND Receiver_id='$receiver_id'";
    if ($conn->query($update_request)) {
        echo "Request rejected successfully.";
        exit();
    } else {
        echo "Something went wrong: " . $conn->error;
    }
}