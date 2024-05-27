<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once "../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $team1_id = $data['team1_id'];
        $team2_id = $data['team2_id'];

        // Obtener pistas disponibles
        $queryFields = "SELECT id FROM field WHERE status = 'available'";
        $resultFields = mysqli_query($conn, $queryFields);
        $availableFields = [];
        while ($row = mysqli_fetch_assoc($resultFields)) {
            $availableFields[] = $row['id'];
        }

        // Seleccionar una pista aleatoria
        $randomFieldIndex = array_rand($availableFields);
        $selectedFieldId = $availableFields[$randomFieldIndex];

        // Calcular la hora del próximo partido
        $baseTime = new DateTime('10:00');
        $queryTime = "SELECT COUNT(id) as num_matches FROM `match` WHERE match_time >= '10:00' AND match_time < '18:00'";
        $resultTime = mysqli_query($conn, $queryTime);
        $dataTime = mysqli_fetch_assoc($resultTime);
        $num_matches = $dataTime['num_matches'];

        // Calculamos el intervalo de tiempo
        $minutesToAdd = $num_matches * 30;
        $baseTime->modify("+$minutesToAdd minutes");
        $match_time = $baseTime->format('H:i');

        // Resetear a las 10:00 si es mayor o igual a las 18:00
        if ($match_time >= '18:00') {
            $match_time = '10:00';
        }

        // Insertar el partido
        $q = "INSERT INTO `match` VALUES (DEFAULT, '$team1_id', '$team2_id', '0', '0', '$match_time', '$selectedFieldId')";
        mysqli_query($conn, $q);

        $q = "SELECT max(id) as last_match_id FROM `match`";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_assoc($result);
        $last_match_id = $arr_result['last_match_id'];

        $q = "INSERT INTO match_teams VALUES ('$team1_id', '$team2_id', '$last_match_id')";
        if (mysqli_query($conn, $q)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Insert match ok']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error al crear el equipo']);
        }
        
        $conn->close();
    }
?>
