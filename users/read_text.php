<?php
include('../config/connect.php');
global $conn;
$show_table = false;
$messages = [];

if (isset($_GET['read_texts']) && isset($_SESSION['username'])) {
    $show_table = true;
    $select_texts = "SELECT * FROM `message` WHERE Receiver_id = :ReceiverID AND message_status=1";
    $stmt = $conn->prepare($select_texts);
    $stmt->bindParam(':ReceiverID', $user_id);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rows_count = $stmt->rowCount();
    if ($rows_count == 0) {
        echo "<h3>No messages found.</h3>";
    }

}
?>
<div class="container-fluid w-50 m-auto">
    <?php if ($show_table && !empty($messages)) { ?>
    <table class="table table-bordered mt-5">
        <thead class="table-secondary text-center">
        <tr>
            <th>Message ID.</th>
            <th>Sender ID</th>
            <th>Message</th>
        </tr>
        </thead>
        <tbody class="table-light">
        <?php foreach ($messages as $message) { ?>
        <tr>
            <td><?php echo htmlspecialchars($message['ID']); ?></td>
            <td><?php echo htmlspecialchars($message['Sender_id']); ?></td>
            <td><?php echo htmlspecialchars($message['Message']); ?></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>
