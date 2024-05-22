<?php
// Permitir solicitudes cruzadas
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "../includes/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $q = "
        SELECT
            t.id,
            CONCAT(u1.name, ' ', u1.surname1) AS player1_name,
            CONCAT(u2.name, ' ', u2.surname1) AS player2_name
        FROM
            team t
        JOIN
            user u1 ON t.player1_dni = u1.dni
        JOIN
            user u2 ON t.player2_dni = u2.dni;
    ";
    $result = mysqli_query($conn, $q);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
