<?php
session_start();

if (isset($_SESSION['form_data'])) {
    $friend_id = $_SESSION['form_data']['friend_id'];
    $friend_email = $_SESSION['form_data']['friend_email'];
    $user_id = $_SESSION['form_data']['user_id'];
    $user_email = $_SESSION['form_data']['user_email'];
    echo "Retrieved fields after redirect due to empty message:<br>";
    echo "user id: " .$user_id . "<br>";
    echo "user email: " .$user_email . "<br>";
    echo "friend id: " . $friend_id ."<br>";
    echo "friend email: " . $friend_email ."<br>";
    unset($_SESSION['form_data']);
} else if (isset($_GET['friend_id']) && isset($_GET['friend_email']) && isset($_SESSION['username'])) {
        $friend_id = htmlspecialchars($_GET['friend_id']);
        $friend_email = htmlspecialchars($_GET['friend_email']);
        $user_email = htmlspecialchars($_SESSION['username']);
        $user_id = htmlspecialchars($_SESSION['user_id']);
        echo "user email: " . $user_email . "<br>";
        echo "user_id: " . $user_id . "<br>";
        echo "Friend ID: " . $friend_id . "<br>";
        echo "Friend email: " . $friend_email . "<br>";
    }
else {
        echo "some data is missing.";
        exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messaging</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body class="p-3">
<div class="container-fluid my-5" style="padding-left: 100px;">
    <h2 class="mb-5">Send Text Message</h2>
    <div class="row d-flex align-items-center justify-content-center">
        <form action="processMessage.php" method="post">
            <div class="form-outline w-25 mb-3">
                <h3>From:</h3>
                <input type="text" class="form-control" name="form_user_id"
                       value="<?php echo htmlspecialchars($user_id);?>" readonly>
                <input type="text" class="form-control" name="form_user_email"
                       value="<?php echo htmlspecialchars($user_email);?>" readonly>
                <hr>
            </div>
            <div class="form-outline w-25 mb-3">
                <h3>To:</h3>
                <input type="text" class="form-control" name="form_friend_id"
                       value="<?php echo htmlspecialchars($friend_id);?>"
                       readonly>
                <input type="text" class="form-control" name="form_friend_email"
                       value="<?php echo htmlspecialchars($friend_email);?>"
                       readonly>
                <hr>
            </div>
            <div class="form-outline w-25 mb-3">
                <label for="message" class="form-label"><h3>Message</h3></label>
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo "<span style='color: red;'>*" . $_SESSION['error_message'] . "</span> <br>";
                    unset($_SESSION['error_message']);
                }
                ?>
                <textarea name="message" id="message" class="form-control"
                          placeholder="Enter your message"></textarea>
            </div>
            <div class="mt-4 w-25 pt-2">
                <input type="submit" value="Send" class="btn bg-info py-2 px-3" name="send_message">
            </div>
        </form>
    </div>
</div>
</body>
</html>
