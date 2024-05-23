<?php
    // Permitir solicitudes cruzadas
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once "../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $q = "DELETE FROM `match`";
        if (mysqli_query($conn, $q)){
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No se ha podido hacer el delete']);
        }
    }
    $conn -> close();
?>