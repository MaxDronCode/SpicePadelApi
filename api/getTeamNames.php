<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $user_email = $data['user_email'];
        
        $q = "SELECT dni FROM user WHERE email = '$user_email'";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_array($result, true);
        $dni1 = $arr_result['dni'];

        $q = "SELECT * FROM team WHERE player1_dni = '$dni1' OR player2_dni = '$dni1'";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_array($result, true);

        $dni1 = $arr_result['player1_dni'];
        $dni2 = $arr_result['player2_dni'];
        $team_id = $arr_result['id'];

        $q = "SELECT name, surname1 FROM user WHERE dni = '$dni1'";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_array($result, true);
        $name1 = $arr_result['name'] . " " . $arr_result['surname1'];
        
        $q = "SELECT name, surname1 FROM user WHERE dni = '$dni2'";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_array($result, true);
        $name2 = $arr_result['name'] . " " . $arr_result['surname1'];

        header('Content-Type: application/json');
        echo json_encode(['name_player1' => $name1, 'name_player2' => $name2, 'team_id' => $team_id]);
    }
?>