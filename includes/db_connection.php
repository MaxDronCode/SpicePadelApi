<?php
    $servername = "localhost";
    $database = "test";
    $username = "root";
    $password = "";
    $port = 3306;
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database, $port);
    mysqli_set_charset($conn, "utf8");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>