<?php
// Permitir solicitudes cruzadas desde cualquier origen
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json'); // Establecer el tipo de contenido a JSON desde el principio

// Incluir el archivo de conexión a la base de datos
require_once "../includes/db_connection.php";

// Verificar si el método de solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decodificar el cuerpo JSON de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
    $dni = $data['dni'] ?? ''; // Usar el operador de fusión null para manejar casos donde 'dni' no esté definido

    // Preparar la consulta SQL
    $sql = "SELECT t.id as team_id, m.match_time, m.field_id, m.id as match_id, me.dni_m 
            FROM `match` m 
            JOIN team t ON t.id = m.team1_id OR t.id = m.team2_id 
            JOIN member me ON (me.dni_m = t.player1_dni OR me.dni_m = t.player2_dni) AND me.dni_m = ?
            ORDER BY m.id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $arr_result = $result->fetch_assoc();
        $response = [
            'success' => true,
            'time' => $arr_result['match_time'],
            'id' => $arr_result['match_id'],
            'field' => $arr_result['field_id']
        ];

        // Consulta adicional para obtener el compañero de equipo
        $queryTeammate = "SELECT player2_dni FROM team WHERE player1_dni = ? UNION SELECT player1_dni FROM team WHERE player2_dni = ?";
        $stmtTeammate = $conn->prepare($queryTeammate);
        $stmtTeammate->bind_param("ss", $dni, $dni);
        $stmtTeammate->execute();
        $resultTeammate = $stmtTeammate->get_result();

        if ($resultTeammate->num_rows > 0) {
            $teammate = $resultTeammate->fetch_assoc();
            $teammateDni = $teammate['player2_dni'] ?? ''; // Asumiendo que la clave del resultado es player2_dni

            // Obtener el nombre del compañero de equipo
            $queryName = "SELECT CONCAT(name, ' ', surname1) AS player2_name FROM user WHERE dni = ?";
            $stmtName = $conn->prepare($queryName);
            $stmtName->bind_param("s", $teammateDni);
            $stmtName->execute();
            $resultName = $stmtName->get_result();
            if ($resultName->num_rows > 0) {
                $name = $resultName->fetch_assoc();
                $response['player2_name'] = $name['player2_name'];
            }
        }

        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron resultados']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
