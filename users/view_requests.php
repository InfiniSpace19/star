<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../index.php', '_self')</script>";
    exit();
} else {
    $username = $_SESSION['username'];
}

global $conn, $requests, $requests_count;
$req_no = 1;
$show_table = false;

if ($requests_count > 0) {
    $show_table = true;
?>

<div class="container-fluid w-50 m-auto">
    <?php if ($show_table && !empty($requests)) { ?>
    <table class="table table-bordered mt-5">
        <thead class="table-secondary">
        <tr>
            <th>Req. No.</th>
            <th>Email</th>
            <th>Accept</th>
            <th>Reject</th>
        </tr>
        </thead>
        <tbody class="table-light">
        <?php
        foreach ($requests as $req) {
            $sender_id = $req['Sender_id'];
            $receiver_id = $req['Receiver_id'];
            $modal_accept_id = 'acceptModal' . $sender_id;
            $modal_reject_id = 'rejectModal' . $sender_id;

            $select_sender_email = "SELECT Email FROM `user` WHERE ID = :SenderID";
            $stmt_sender = $conn->prepare($select_sender_email);
            $stmt_sender->bindParam(':SenderID', $sender_id);
            $stmt_sender->execute();
            $sender = $stmt_sender->fetch(PDO::FETCH_ASSOC);
            if ($sender) {
                $sender_email = $sender['Email'];
                echo "
                    <tr>
                        <td>$req_no</td>
                        <td>$sender_email</td>            
                        <td><a href='#' type='button' class='btn btn-lg' data-bs-toggle='modal' 
                data-bs-target='#$modal_accept_id'><i class='fa-solid fa-check' style='color: green;'></i></a></td>
                        <td><a href='#' type='button' class='btn btn-lg' data-bs-toggle='modal' 
                data-bs-target='#$modal_reject_id'><i class='fa-solid fa-ban' style='color: crimson;'></i></a></td>
                     </tr>";
                // Modal for Accept
                echo "<div class='modal fade' id='$modal_accept_id' tabindex='-1' 
                            aria-labelledby='acceptModalLabel$sender_id' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h4 class='modal-title fs-5' id='acceptModalLabel$sender_id'></h4>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'>
                                </button>
                            </div>
                            <div class='modal-body'>
                               Please confirm acceptance of request from:<h5>$sender_email</h5>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel
                                </button>
                                <button type='button' class='btn btn-danger'>
                                    <a href='./profile.php?accept_request=$sender_id&receiver_id=$receiver_id' 
                                        class='text-light text-decoration-none'>Accept</a></button>
                            </div>
                        </div>
                    </div>
                </div>";
                // Modal for Reject
                echo "<div class='modal fade' id='$modal_reject_id' tabindex='-1' 
                            aria-labelledby='rejectModalLabel$sender_id' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h4 class='modal-title fs-5' id='rejectModalLabel$sender_id'></h4>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'>
                                </button>
                            </div>
                            <div class='modal-body'>
                               Please confirm reject of request from:<h5>$sender_email</h5>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                <button type='button' class='btn btn-danger'>
                                    <a href='./profile.php?reject_request=$sender_id&receiver_id=$receiver_id' 
                                        class='text-light text-decoration-none'>Reject</a></button>
                            </div>
                        </div>
                    </div>
                </div>";
            }
            $req_no++;
        }
        } else {
            echo "<p><b>0</b> friend requests found.</p>";
        }
        }
        ?>
        </tbody>
    </table>
</div>




