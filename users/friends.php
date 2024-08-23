<?php
global $conn;
$show_table = false;

if (isset($_GET['friends_list']) && isset($_SESSION['username'])) {
    $show_table = true;
    $user_id = $_GET['friends_list'];
    $_SESSION['user_id'] = $user_id;
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
}
?>
<div class="container-fluid w-50 m-auto">
    <?php if ($show_table && !empty($friend_ids)) { ?>
    <table class="table table-bordered mt-5">
        <thead class="table-secondary">
        <tr>
            <th>Friend ID.</th>
            <th>Email</th>
            <th>Send Text Message</th>
        </tr>
        </thead>
        <tbody class="table-light">
        <?php
        foreach ($friend_ids as $friend_id) {
            //Fetching user email for each friend
            $select_user = "SELECT * FROM `user` WHERE `ID` = :friend_id";
            $stmt_user = $conn->prepare($select_user);
            $stmt_user->bindParam(':friend_id', $friend_id);
            $stmt_user->execute();
            $friend_details = $stmt_user->fetch(PDO::FETCH_ASSOC);
            echo "<tr>";
            echo "<td>" . $friend_details['ID'] . "</td>";
            echo "<td>" . $friend_details['Email'] . "</td>";
            echo "<td><a href='./send_text.php?friend_id={$friend_details['ID']}&friend_email=" .urldecode
                ($friend_details['Email']) ."' 
                    type='button' class='btn btn-lg'>
                    <i class='fa-solid fa-paper-plane'></i>
                    </a></td>";
            echo "</tr>";
        }
        }
        ?>
        </tbody>
    </table>
</div>
