<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = json_decode(file_get_contents('php://input'), true);

        $user_mail = $data['user_mail'];

        // transformar el mail a dni
        $q = "SELECT dni FROM user WHERE email = '$user_mail'";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_array($result, true);
        $dni = $arr_result['dni'];

        // consultar si el usuario ya esta en un team
        $q = "SELECT id FROM team WHERE player1_dni = '$dni' OR player2_dni = '$dni'";
        $result = mysqli_query($conn, $q);
        
        if (mysqli_num_rows($result) > 0) { // el usuario ya existe en un team
            echo json_encode(['alreadyInTeam' => true]);
        } else {
            echo json_encode(['alreadyInTeam' => false]);
        }


    }
?>