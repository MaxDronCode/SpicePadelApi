<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");



    require_once"./includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        // Detener el script después de enviar los encabezados de CORS
        exit(0);
    }

     
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = mysqli_query($conn, "SELECT * FROM commentary");
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    }



?>