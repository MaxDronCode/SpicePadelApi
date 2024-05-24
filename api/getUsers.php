<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once "../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $q = "SELECT u.email FROM user u 
              JOIN member m ON u.dni = m.dni_m
              WHERE NOT EXISTS (SELECT 1 FROM team t WHERE t.player1_dni = m.dni_m OR t.player2_dni = m.dni_m)";

        $result = mysqli_query($conn, $q);
        $users = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        echo json_encode($users);
    }
?>
