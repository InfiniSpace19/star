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
                        <a class='nav-link' href='./users/signin.php'>Sign In</a>
                    </li>";
            } else {
                echo "
                     <li class='nav-item'>
                        <a class='nav-link' href='#'>Welcome, <span class='user'>" . $_SESSION['username'] . "</span></a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='./users/signout.php'>Sign-Out</a>
                    </li>";
            }
            ?>
        </ul>
    </nav>