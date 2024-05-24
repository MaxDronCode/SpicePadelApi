<?php
    // Permitir solicitudes cruzadas
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once "./includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        // Detener la ejecución del script después de enviar los headers para las solicitudes OPTIONS
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $data['team_id'];
        

        $q = "DELETE FROM team WHERE id = '$id'";
        $result = mysqli_query($conn, $q);

        if ($result) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Delete Team Ok']);
        } else {
            header('Content-Type: application/json');
            http_response_code(500); // Envía un código de estado HTTP cuando hay un error
            echo json_encode(['success' => false, 'message' => 'Delte Team Failed: ' . mysqli_error($conn)]);
        }
    }
?>
