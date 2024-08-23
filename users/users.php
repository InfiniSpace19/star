<?php
session_start();
include('../config/connect.php');
global $conn;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .welcome {
            color: darkgreen;
            font-weight: 500;
        }
        .fa-envelope-grey {
            color: lightgrey;
            font-size: larger;
        }
        .icon-cell {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
<h3 class="text-center mb-5">List of Users</h3>
<div class="alert alert-success mb-5 text-center fw-bold mb-5">
    <?php
    include('./send_request.php');
    ?>
</div>
<div class="container-fluid w-50 m-auto">
    <table class="table table-bordered mt-5">
        <thead class="table-secondary text-center">
        <tr>
            <th>User ID.</th>
            <th>Email</th>
            <th>Favorite Color</th>
            <th>Status</th>
            <th>Send Friend Request</th>
        </tr>
        </thead>
        <tbody class="table-light">
        <?php
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            echo "<span class='welcome'>Welcome, $username</span>";
        } elseif (isset($_COOKIE['username'])) {
            $username = $_COOKIE['username'];
            echo "<span class='welcome'>Welcome, $username</span>";
        } else {
            echo "<span class='welcome'>Welcome, Admin</span><br>";
        }

        $select_users = "SELECT * FROM `user` WHERE Email <> '$username'";
        $stmt = $conn->prepare($select_users);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rows_count = $stmt->rowCount();

        if ($rows_count == 0) {
            echo "<h3>No users found.</h3>";
        }

        foreach ($users as $user) {
            $user_id = $user['ID'];
            $email = $user['Email'];
            $favcolor = $user['Favcolor'];
            $status = $user['Status'];
            $modal_id = 'sendRequestModal' . $user_id;

            echo "  <tr>
                    <td>$user_id</td>
                    <td>$email</td>            
                    <td>$favcolor</td>
                    <td>$status</td>";

            if ($status == 'Active') {
                echo "<td class='icon-cell'>
                        <a href='#' type='button' class='btn btn-lg' data-bs-toggle='modal' 
                            data-bs-target='#$modal_id'>
                            <i class='fa-solid fa-envelope'></i>
                        </a>
                     </td>";
            } else {
                echo "<td class='icon-cell'>
                        <i class='fa-solid fa-envelope fa-envelope-grey'></i>
                      </td>";
            }
            echo "</tr>";
            // Modal
            echo "<div class='modal fade' id='$modal_id' tabindex='-1' 
                    aria-labelledby='sendRequestModalLabel$user_id' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h4 class='modal-title fs-5' id='sendRequestModalLabel$user_id'>Friend Request to: 
                                    <span style='color: brown;'> $email</span></h4>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'>
                                </button>
                            </div>
                            <div class='modal-body'>
                                Are you sure?
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                <button type='button' class='btn btn-danger'><a href='./users.php?send_request=$user_id' 
                                    class='text-light text-decoration-none'>Send</a></button>
                            </div>
                        </div>
                    </div>
                </div>";
        }
        $conn = null;
        ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>




