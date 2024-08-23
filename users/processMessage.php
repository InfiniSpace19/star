<?php
session_start();
include('../config/connect.php');
global $conn;
$message = $messageErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    $form_user_id = $_POST['form_user_id'];
    $form_friend_id = $_POST['form_friend_id'];

    if (empty($_POST['message'])) {
        $_SESSION['error_message'] = "Message cannot be empty";
        $_SESSION['form_data'] = [
            'friend_id' => $_POST['form_friend_id'],
            'friend_email' => $_POST['form_friend_email'],
            'user_id' => $_POST['form_user_id'],
            'user_email' => $_POST['form_user_email'],
        ];
        header("Location: send_text.php");
        exit();
    } else {
        $message = $_POST['message'];
        $message_status = 1;

        $insert_message = "INSERT INTO `message` (Sender_id, Receiver_id, Message, Message_status) 
                            VALUES (:Sender_id, :Receiver_id, :message, :message_status)";
        $stmt = $conn->prepare($insert_message);
        $stmt->bindParam(':Sender_id', $form_user_id, PDO::PARAM_INT);
        $stmt->bindParam(':Receiver_id', $form_friend_id, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':message_status', $message_status, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Message sent!');
            window.location.href = './profile.php';
            </script>";
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    }
} else {
    echo "Error: Post request failed.";
}
$conn = null;
