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

        $dni = $data['user_dni'];
        

        $q = "DELETE FROM user WHERE dni = '$dni'";
        $result = mysqli_query($conn, $q);

        if ($result) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Delete User Ok']);
        } else {
            header('Content-Type: application/json');
            http_response_code(500); // Envía un código de estado HTTP cuando hay un error
            echo json_encode(['success' => false, 'message' => 'Delte User Failed: ' . mysqli_error($conn)]);
        }
    }
?>
