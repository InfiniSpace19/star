<?php
session_start();
require_once('../config/connect.php');
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    echo "Welcome, " . $username . "<br>";
} elseif (isset($_COOKIE["username"])) {
    $username = $_COOKIE["username"];
} else {
    echo "Welcome, Guest." . "<br>";
}
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign In</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
              crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
              integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
              crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <style>
        </style>
    </head>

    <body>
    <div class="container-fluid my-5">
        <h3 class="text-center mb-5">Sign In</h3>
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-12 col-xl-6">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-outline mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" id="email" name="email" class="form-control" value="<?php echo $username; ?>" required>
                    </div>

                    <div class="form-outline mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Enter your password" required>
                    </div>

                    <div class="mt-4 pt-2">
                        <input type="submit" value="Sign In" class="btn bg-info py-2 px-3" name="signin">
                        <p class="small mt-3 text-secondary"><span class="fw-bold">Don't have an account?</span> Sign
                            up <a href="signup.php" class="text-info">here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </body>

    </html>

<?php
global $conn;
if (isset($_POST['signin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // fetch user by email
    $select_query = "SELECT * FROM `user` WHERE Email=:Email";
    $stmt = $conn->prepare($select_query);
    $stmt->bindParam(':Email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $status = $user['Status'];
    $user_id = $user['ID'];
    if ($user) {
        if (isset($user['Password']) && password_verify($password, $user['Password']) && $status == 'Active' ) {
            $_SESSION['username'] = $email;
            $_SESSION['user_id'] = $user_id;

            echo "<script>alert('You are now logged in.')</script>";
            echo "<script>window.open('profile.php', '_self')</script>";
            exit;
        } else {
            echo "<script>alert('Password is not valid, or Account is deactivated')</script>";
        }
    } else {
        echo "<script>alert('Email is not valid.')</script>";
    }
}
?>