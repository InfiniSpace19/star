<?php
global $conn;
if (isset($_GET['accept_request']) && isset($_GET['receiver_id']) && isset($_SESSION['username'])) {
    $sender_id = $_GET['accept_request'];
    $receiver_id = $_GET['receiver_id'];

    $update_request = "UPDATE `request` SET Req_status='Accepted' WHERE  Sender_id='$sender_id' AND Receiver_id='$receiver_id'";
    if ($conn->query($update_request)) {
        echo "Request accepted successfully.";
        exit();
    } else {
        echo "Something went wrong: " . $conn->error;
    }
}