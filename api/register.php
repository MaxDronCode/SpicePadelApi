<?php
   

    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"../includes/db_connection.php";



    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
     
        // echo json_encode(['success' => false, 'message' => json_encode($data)]);

        $dni = $data['dni'];
        $name = $data['name'];
        $surename1 = $data['surename1'];
        $surename2 = $data['surename2'];
        $phone = $data['phone'];
        $email = $data['email'];
        $birthday = $data['birthday'];
        $bank_account = $data['bank_account'];
        $address = $data['address'];
        $password = $data['password'];

        // comprovar que el dni no exista ya en la bd
        $q = "SELECT EXISTS (SELECT 1 from user WHERE dni = '$dni')";
        $result = mysqli_query($conn, $q);


        $arr_result = mysqli_fetch_array($result);

        if ($arr_result[0] == 1){ // Si ya existe un dni asi en la bd
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'El usuario ya existe']);
        } else { // si no , se hace el insert
            // en la tabla user
            $q = "INSERT INTO user VALUES ('$dni', '$name', '$surename1', '$surename2', '$phone', '$email', '$address', '$password')";
            mysqli_query($conn, $q);
            // // en la tabla member
            // $q = "INSERT INTO member VALUES ('$dni', '$birthday','$bank_account')";
            // mysqli_query($conn, $q);
            // generar el token
            $token = bin2hex(random_bytes(16)); // para logear al usuario
            header('Content-Type: application/json');
            $response = ['success' => true, 'message' => 'Dado de alta correctamente', 'token' => $token, 'user_mail' => $email];
            echo json_encode($response);

        }
    }
    $conn->close();
?>