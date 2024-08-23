<?php
session_start();
include('../config/connect.php');
global $conn;

if (isset($_GET['shared_image']) && isset($_SESSION['user_id'])) {
    $shared_image_id = $_GET['shared_image'];
    $user_id = $_SESSION['user_id'];
    $user_email = $_SESSION['username'];
    $_SESSION['shared_image_id'] = $shared_image_id;
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
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
<?php
$select_friends = "SELECT * FROM `request` WHERE 
                   (Sender_id = :user_id AND Req_status = 'Accepted') 
                   OR (Receiver_id = :user_id AND Req_status = 'Accepted')";
$stmt_friends = $conn->prepare($select_friends);
$stmt_friends->bindParam(':user_id', $user_id);
$stmt_friends->execute();
$friends = $stmt_friends->fetchAll(PDO::FETCH_ASSOC);
// Initializing an array to hold friend IDs
$friend_ids = [];
foreach ($friends as $friend) {
    if ($friend['Sender_id'] == $user_id) {
        $friend_ids[] = $friend['Receiver_id'];
    } else {
        $friend_ids[] = $friend['Sender_id'];
    }
}
?>
<div class="container-fluid my-5">
    <h3 class="text-center mb-5">Share Image</h3>
    <div class="row d-flex align-items-center justify-content-center">
        <div class="col-lg-12 col-xl-6">
            <h5>From: <?php echo htmlspecialchars($user_email); ?></h5>
            <hr>
            <h5>Share with</h5>
            <form action="process_imageShare.php" method="post">
                <div class="form-outline mb-3">
                    <select name="friend_id" id="friendSelect" class="form-select form-select-sm w-50">
                        <option selected disabled>Select friend</option>
                        <?php
                        foreach ($friend_ids as $friend_id) {
                        //Fetching user email for each friend
                        $select_user = "SELECT * FROM `user` WHERE `ID` = :friend_id";
                        $stmt_user = $conn->prepare($select_user);
                        $stmt_user->bindParam(':friend_id', $friend_id);
                        $stmt_user->execute();
                        $friend_details = $stmt_user->fetch(PDO::FETCH_ASSOC);
                        echo "<option value='{$friend_details['ID']}'>{$friend_details['Email']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mt-4 pt-2">
                    <input type="submit" value="Share" class="btn bg-info py-2 px-3" name="share_image">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>