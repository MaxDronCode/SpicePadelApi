<?php
    // Permitir solicitudes cruzadas
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once "../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $dni = $data['dni'];
        $password = $data['password'];

        $q = "SELECT EXISTS (SELECT 1 FROM user WHERE dni = '$dni' AND password = '$password')";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_array($result);

        if ($arr_result[0] == 1) {
            // Obtener el email del usuario
            $q = "SELECT email FROM user WHERE dni = '$dni' AND password = '$password'";
            $result = mysqli_query($conn, $q);
            $arr_result = mysqli_fetch_array($result);
            $user_mail = $arr_result['email']; // tenemos el mail para devolver al front

            // Comprobar si el usuario es un administrador
            $q = "SELECT EXISTS (SELECT 1 FROM admin WHERE dni_a = '$dni')";
            $result = mysqli_query($conn, $q);
            $isAdmin = mysqli_fetch_array($result)[0] == 1; // true si es admin

            // Generar el token y devolverlo
            $token = bin2hex(random_bytes(16));
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Login successful', 
                'token' => $token, 
                'user_mail' => $user_mail, 
                'admin' => $isAdmin
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }

        $conn->close();
    }
?>
