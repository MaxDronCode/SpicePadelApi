<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once "../includes/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_mail = $data['user_mail'] ?? '';

    // Consulta que selecciona directamente la última reserva del usuario
    $query = "SELECT start_hour, end_hour, field_id, `date` FROM booking WHERE member_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("s", $user_mail);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $response = $result->fetch_assoc();
        } else {
            // Devolver N/A si no se encuentra ninguna reserva
            $response = [
                'field_id' => 'N/A',
                'start_hour' => 'N/A',
                'end_hour' => 'N/A',
                'date' => 'N/A'
            ];
        }
        
        echo json_encode($response);
    } else {
        // Manejar el error en la preparación de la consulta
        echo json_encode(['error' => 'Error en la preparación de la consulta']);
    }
}
?>
