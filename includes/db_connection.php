<?php
    $servername = "sql7.freemysqlhosting.net";
    $database = "sql7710575";
    $username = "sql7710575";
    $password = "zZjfDV2CNV";
    $port = 3306;
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database, $port);
    mysqli_set_charset($conn, "utf8");
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // server : sql8.freemysqlhosting.net
    // db_name : sql8708583
    // user: sql8708583
    // pass : S5D7QZwMxv
    // port : 3306
?>