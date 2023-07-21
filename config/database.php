<?php
include_once "constants.php";

// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

if (mysqli_connect_error()) {
    die("Connection Failed! " . mysqli_connect_error());
}

// echo "Connection Successful!";
