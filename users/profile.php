<?php
session_start();
include('../config/connect.php');
global $conn;

if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../index.php', '_self')</script>";
    exit();
} else {
    $username = $_SESSION['username'];
}

// Fetch the user's favorite color from the database
$select_color = "SELECT * FROM `user` WHERE Email = :Username";
$stmt = $conn->prepare($select_color);
$stmt->bindParam(':Username', $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $user['ID'];
$user_email = $user['Email'];
$favcolor = $user['Favcolor'] ?? 'white';

// Set session or cookie variable as per user's favorite color
$_SESSION['favcolor'] = $favcolor;
setcookie('background', $favcolor, time() + (86400 * 30), "/");

//Check for Pending friend requests & their emails
$select_pending_requests = "SELECT * FROM `request` WHERE Receiver_id = :ReceiverID AND Req_status='Pending'";
$stmt_reqs = $conn->prepare($select_pending_requests);
$stmt_reqs->bindParam(':ReceiverID', $user_id);
$stmt_reqs->execute();
$requests = $stmt_reqs->fetchAll(PDO::FETCH_ASSOC);
$requests_count = $stmt_reqs->rowCount();

if (isset($_POST['update_user'])) {
    $user_to_update = $_SESSION['username'];
    $email = $_POST['email'];
    $favcolor = $_POST['color'];

    // Update query to update database
    $update_query = "UPDATE `user` SET Email=:Email, Favcolor=:Favcolor WHERE Email=:Username";
    $stmt = $conn->prepare($update_query);
    $stmt->bindParam(':Email', $email);
    $stmt->bindParam(':Favcolor', $favcolor);
    $stmt->bindParam(':Username', $user_to_update);
    if ($stmt->execute()) {
        // Update the session variable with the new email
        $_SESSION['username'] = $email;
        $_SESSION['favcolor'] = $favcolor;
        setcookie('background', $favcolor, time() + (86400 * 30), "/");
        setcookie('username', $email, time() + (86400 * 30), "/");
        echo "<script>alert('Your profile is updated successfully.');
            window.location.href='profile.php?updated=true';</script>";
    } else {
        echo "<script>alert('Failed to updated your profile.')</script>";
    }
    $conn = null;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        body {
            background-color: <?php
              echo isset($_COOKIE['background']) ? $_COOKIE['background'] : (isset($_SESSION['favcolor']) ?
              $_SESSION['favcolor'] : 'white'); ?>;
                color: <?php
              $color = isset($_COOKIE['background']) ? $_COOKIE['background'] : (isset($_SESSION['favcolor']) ?
               $_SESSION['favcolor'] : 'white');
                echo $color == 'grey' ? 'white' : 'grey';
            ?>;
        }
        .friend {
            color: yellow;
        }
        .user {
            color: greenyellow;
        }
    </style>
</head>
<body>
<!-- Top navbar -->
<div class="container-fluid p-0">
    <nav class="navbar navbar-expand-lg border-bottom border-body bg-dark" data-bs-theme="dark" id="navbar-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./profile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./profile.php?friends_list=<?php echo $user_id; ?>">Friends</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./gallery.php">Photo Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link friend" href="?view_requests=1"><i class="fa-regular
                        fa-bell"></i><sup>
                                <?php
                                echo $requests_count; ?> Friend Requests</sup></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link friend" href="?read_texts=1"><i class="fa-regular
                        fa-envelope"></i><sup>
                                Text Messages</sup></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Second navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <ul class="navbar-nav me-auto">
            <?php
            if (!isset($_SESSION['username'])) {
                echo "
                    <li class='nav-item'>
                        <a class='nav-link' href='#'>Welcome, Guest</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='signin.php'>Login</a>
                    </li>";
            } else {
                echo "
                     <li class='nav-item'>
                        <a class='nav-link' href='#'>Welcome, <span class='user'>" . $_SESSION['username'] . "</span></a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='signout.php'>Sign-Out</a>
                    </li>";
            }
            ?>
        </ul>
    </nav>

    <h4 class="text-center text-secondary mb-4 mt-4">Edit Your Profile</h4>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-outline mb-4 w-25 m-auto">

            <label for="user_id" class="form-label mt-4">User ID</label>
            <input type="text" name="user_id" id="user_id" class="form-control no-focus" value="<?php echo $user_id; ?>"
                   readonly disabled>
            <label for="email" class="form-label mt-4">Email</label>
            <input type="text" name="email" id="email" class="form-control" value="<?php echo $user_email; ?>">

            <label for="color" class="form-label mt-4">Background Color</label>
            <select name="color" class="form-select">
                <option value="" disabled>Select a Color</option>
                <option value="lightgrey" <?php echo isset($_SESSION['favcolor']) && $_SESSION['favcolor'] == 'lightgrey' ?
                    'selected' : ''; ?>>
                    Light Gray
                </option>
                <option value="white" <?php echo isset($_SESSION['favcolor']) && $_SESSION['favcolor'] == 'white' ? 'selected' : ''; ?>>
                    White
                </option>
                <option value="lightyellow" <?php echo isset($_SESSION['favcolor']) && $_SESSION['favcolor'] == 'lightyellow' ? 'selected' : ''; ?>>
                    Light Yellow
                </option>
                <option value="lightgreen" <?php echo isset($_SESSION['favcolor']) && $_SESSION['favcolor'] == 'lightgreen' ? 'selected' : ''; ?>>
                    Light Green
                </option>
            </select>
            <div class="mt-4 pt-2">
                <input type="submit" name="update_user" class="btn btn-success mb-3 px-3 mt-4" value="Update Profile">
            </div>
        </div>
    </form>
</div>
<div class="text-center">
    <?php
    if (isset($_GET['view_requests']) && $_GET['view_requests'] == '1') {
        include('./view_requests.php');
    }
    if (isset($_GET['read_texts']) && $_GET['read_texts'] == '1') {
        include('./read_text.php');
    }

    include('./accept_request.php');
    include('./reject_request.php');
    include('./friends.php');

    ?>
</div>
<!-- Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>