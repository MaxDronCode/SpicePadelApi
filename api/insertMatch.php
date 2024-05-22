<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $team1_id = $data['team1_id'];
        $team2_id = $data['team2_id'];

        $q = "INSERT INTO `match` VALUES (DEFAULT, '$team1_id', '$team2_id', '0', '0', '10:00', '1')";
        mysqli_query($conn, $q);

        $q = "SELECT max(id) as last_match_id FROM `match`";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_assoc($result);
        $last_match_id = $arr_result['last_match_id'];

        $q = "INSERT INTO match_teams VALUES ('$team1_id', '$team2_id', '$last_match_id')";

        if (mysqli_query($conn, $q)){
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Insert match ok']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error al crear el equipo']);
        }
        
        $conn->close();
        
    }
?>