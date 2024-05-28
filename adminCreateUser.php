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

        $dni_m = $data['dni'];
        $birthday = $data['birthday'];
        $bank_account = $data['bank_account'];
        

        // test de existencia
        $q = "SELECT EXISTS (SELECT 1 FROM user WHERE dni = '$dni')";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_assoc($result);

        if ($arr_result[0] == 1) {
            // ya existe en la bd, no se puede insertar
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Usuario ya existe']);
        } else {
            // usuario se puede insertar en la bd
            $q = "INSERT INTO user VALUES ('$dni','$name','$surname1','$surname2','$phone','$email','$address','$password')";
            $q2 = "INSERT INTO member VALUES ('$dni_m','$birthday','$bank_account')";

            $result1 = mysqli_query($conn, $q);
            
            if ($result1){
                $result2 = mysqli_query($conn, $q2);
                if ($result2){
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'User y member insertados']);
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'User insertado, member no!!!!']);
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'User NO insertado!!!']);
            }
        }
    }
?>
