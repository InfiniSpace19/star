<?php
$servername = "localhost";
$username = "kahi0015";
$password = "CST8257";
$dbname = "socialmedia";
$port = "3311";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;port=$port", $username, $password);
    //Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "<span style='color: darkgray;font-weight: 300;'><i>Connected successfully</i></span><br>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}