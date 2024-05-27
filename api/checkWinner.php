<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $data = json_decode(file_get_contents('php://input'), true);

        $team1_id = $data['team1_id'];
        $team2_id = $data['team2_id'];

        
        $q = "SELECT sets_t1, sets_t2 FROM `match` WHERE team1_id = '$team1_id' AND team2_id = '$team2_id'";
        
        $result = mysqli_query($conn, $q);

        if (mysqli_num_rows($result) > 0){

            $arr_result = mysqli_fetch_assoc($result);

            if ($arr_result['sets_t1'] > $arr_result['sets_t2']){

                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'winner_id' => $team1_id]);

            } elseif($arr_result['sets_t1'] < $arr_result['sets_t2']) {

                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'winner_id' => $team2_id]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Este match aun no esta puntuado!', 'tie' => true]);

            }

        } else {

            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error en la consulta sacando al ganador']);

        }

        $conn->close();


        
    }
?>