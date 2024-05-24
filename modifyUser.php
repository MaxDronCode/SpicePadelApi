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

        $dni = $data['dni'];
        $name = $data['name'];
        $surname1 = $data['surname1'];
        $surname2 = $data['surname2'];
        $phone = $data['phone'];
        $email = $data['email'];
        $address = $data['address'];
        $password = $data['password'];

        $q = "UPDATE user SET dni = '$dni', name = '$name', surname1 = '$surname1', surname2 = '$surname2', phone = '$phone', email = '$email', address = '$address', password = '$password' WHERE dni = '$dni'";
        $result = mysqli_query($conn, $q);

        if ($result) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Update User Ok']);
        } else {
            header('Content-Type: application/json');
            http_response_code(500); // Envía un código de estado HTTP cuando hay un error
            echo json_encode(['success' => false, 'message' => 'Update User Failed: ' . mysqli_error($conn)]);
        }
    }
?>
