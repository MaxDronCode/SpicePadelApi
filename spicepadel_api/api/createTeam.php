<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = json_decode(file_get_contents('php://input'), true);

        $user_mail1 = $data['user_mail1'];
        $user_mail2 = $data['user_mail2'];
        // transformar el mail del primer participante a dni
        $q1 = "SELECT dni FROM user WHERE email = '$user_mail1'";
        $result = mysqli_query($conn, $q1);
        $arr_result = mysqli_fetch_array($result);
        $user_dni1 = $arr_result['dni'];
        // transformar el mail del segundo participante a dni
        $q1 = "SELECT dni FROM user WHERE email = '$user_mail2'";
        $result = mysqli_query($conn, $q1);
        $arr_result = mysqli_fetch_array($result);
        $user_dni2 = $arr_result['dni'];

        // comprobar que el user 2 no este cogido en otro team
        $q = "SELECT EXISTS (SELECT 1 FROM team WHERE player1_dni = '$user_dni2' OR player2_dni = '$user_dni2')";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_array($result);

        if ($arr_result[0] == 1) { // si ya existes
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'El usuario 2 ya esta en otro equipo']);
        } else {
            $q = "INSERT INTO team VALUES (DEFAULT, '$user_dni1', '$user_dni2', 0)";
            mysqli_query($conn, $q);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Nuevo equipo creado']);

        }



    }

?>