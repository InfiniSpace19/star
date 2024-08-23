<?php
//include_once ('../config/connect.php');
global $conn;

if (isset($_GET['send_request']) && isset($_SESSION['username'])) {
    $b_user_id = $_GET['send_request'];
    $username = $_SESSION['username'];
//    echo "B-user ID: " . $b_user_id;
//    echo "<br>";
//    echo "A-username: " . $username;
//    echo "<br>";

    $select_a_user_id = "SELECT * FROM `user` WHERE Email = '$username'";
    $stmt_req = $conn->prepare($select_a_user_id);
    $stmt_req->execute();
    $user = $stmt_req->fetch(PDO::FETCH_ASSOC);
    $a_user_id = $user['ID'];
//    echo "A-user ID: " . $a_user_id;

    // Check if the request already exists
    $check_request = "SELECT * FROM `request`
                            WHERE (Sender_id = :sender_id AND Receiver_id = :receiver_id)
                               OR (Sender_id = :receiver_id AND Receiver_id = :sender_id)";
    $stmt = $conn->prepare($check_request);
    $stmt->bindParam(':sender_id', $a_user_id);
    $stmt->bindParam(':receiver_id', $b_user_id);
    $stmt->execute();
    // Fetch the result
    $existing_request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_request) {
             $status = $existing_request['Req_status'];
            echo "<br>Request status: " . $status . "<br>";
            switch ($status) {
                case "Accepted":
                    echo "<span class='error'>A friend request already accepted. </span>";
                    break;
                case "Rejected":
                    echo "<span class='error'>A friend request was rejected. </span>";
                    break;
                case "Pending":
                    echo "<span class='error'>A friend request was already submitted. </span>";
                    break;
                default:
                    echo "<span class='error'>A friend request was not submitted. </span>";
                    break;
            }
    } else {
        // If no request exists
        echo "No existing request found. You can proceed. ";
        //Insert the friend request in request table
        $insert_request = "INSERT INTO `request` (Sender_id, Receiver_id) VALUES (:sender_id, :receiver_id)";
        $stmt_ins = $conn->prepare($insert_request);
        $stmt_ins->bindParam(':sender_id', $a_user_id);
        $stmt_ins->bindParam(':receiver_id', $b_user_id);

        if ($stmt_ins->execute()) {
            echo "Friend Request sent.";
        } else {
            echo "<span class='error'>Something went wrong!</span><br>";
        }
    }
}

