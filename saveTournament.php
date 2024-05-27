<?php
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    require_once "./includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $data = json_decode(file_get_contents("php://input"), true);

        $stmt = $conn->prepare("INSERT INTO tournament (winner_team, win_player1_name, win_player2_name) VALUES (?, ?, ?)");
        $winner_team = $data['winnerTeamId'];
        $win_player1_name = $data['winnerPlayer1'];
        $win_player2_name = $data['winnerPlayer2'];
        $stmt->bind_param("iss", $winner_team, $win_player1_name, $win_player2_name);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        
    }
?>