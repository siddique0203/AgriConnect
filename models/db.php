<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "demowork";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// optional but recommended
//mysqli_set_charset($conn, "utf8mb4");
