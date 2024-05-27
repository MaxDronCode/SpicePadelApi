<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"./includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $data['match_id'];
        $sets_t1 = $data['pointsTeam1'];
        $sets_t2 = $data['pointsTeam2'];

        $q = "UPDATE `match` SET sets_t1 = '$sets_t1', sets_t2 = '$sets_t2' WHERE id = '$id'";
        if (mysqli_query($conn, $q)){
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
        }
    }
?>