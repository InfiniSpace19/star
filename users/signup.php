<?php
session_start();
require_once('../config/connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up</title>
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
global $conn;
function cleanInput($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$email = "";
$emailErr = "";

if (isset($_POST['register_user'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        echo "<span class='error'>Please fill all the fields.</span>";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $email = cleanInput($_POST['email']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $favcolor = 'white';
        $status = 'Active';

        $select_query = "SELECT * FROM `user` WHERE email='$email'";
        $result_query = $conn->prepare($select_query);
        $result_query->execute();
        $rows_count = $result_query->rowCount();

        if ($rows_count > 0) {
            echo "<script>alert('Email already exists!')</script>";
        } elseif ($password != $confirm_password) {
            echo "<script>alert('Passwords do not match!')</script>";
        } else {
            $insert_query = "INSERT INTO `user`(Email, Password, Favcolor, Status) 
       VALUES (:Email, :Password, :Favcolor, :Status)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bindParam(':Email', $email);
            $stmt->bindParam(':Password', $hashed_password);
            $stmt->bindParam(':Favcolor', $favcolor);
            $stmt->bindParam(':Status', $status);

            if ($stmt->execute()) {
                echo "<script>alert('User registered successfully.');</script>";
                $conn = null;
                $_SESSION["username"] = $email;
                setcookie("username", $email, time() + (86400 * 30), "/");
                echo "<script>window.open('./signin.php', '_self')</script>";
            } else {
                echo "<script>alert('User registration failed!')</script>";
            }
        }
    }
}
?>
<div class="container-fluid my-5">
    <h3 class="text-center mb-5">New User Sign-up</h3>
    <div class="row d-flex align-items-center justify-content-center">
        <div class="col-lg-12 col-xl-6">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-outline mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class='error'><?php echo $emailErr; ?></span>
                </div>
                <div class="form-outline mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Enter your password">
                </div>
                <div class="form-outline mb-3">
                    <label for="password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                           placeholder="Confirm your password">
                </div>
                <div class="mt-4 pt-2">
                    <input type="submit" value="Register" class="btn bg-info py-2 px-3" name="register_user">
                    <p class="small mt-3 text-secondary"><span class="fw-bold">Already have an account?</span>
                        Sign in
                        <a href="signin.php" class="text-info">here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

