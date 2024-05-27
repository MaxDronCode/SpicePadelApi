<?php
    // Permitir solicitudes cruzadas
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");

    require_once "./includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        // Detener el script después de enviar los encabezados de CORS
        exit(0);
    }
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = json_decode(file_get_contents('php://input'), true);

        $user_name = $data['user_name'];
        $email = $data['email'];
        $phone = $data['phone'];
        $theme = $data['theme'];
        $text = $data['text'];

        $q = "INSERT INTO commentary VALUES (DEFAULT, '$user_name', '$email', '$phone', '$theme', '$text')";

        if (mysqli_query($conn, $q)){

            
            $response = ['success' => true, 'message' => 'Gracias por contactar con nosotros, nos pondremos en contacto lo antes posible!'];
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } else {

            $response = ['success' => false, 'message' => 'Algo ha fallado al insertar comentario'];
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
?>