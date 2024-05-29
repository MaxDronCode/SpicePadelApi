<?php 
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once "../includes/db_connection.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    $user_mail = $data['user_mail'] ?? '';

    // Usar consultas preparadas para prevenir inyección SQL
    $qMaxid = "SELECT MAX(id) as max_id FROM booking WHERE member_id=?";
    $stmtMaxid = $conn->prepare($qMaxid);
    if ($stmtMaxid) {
        $stmtMaxid->bind_param("s", $user_mail);
        $stmtMaxid->execute();
        $resultMaxid = $stmtMaxid->get_result();
        if ($resultMaxid->num_rows > 0) {
            $arr_result = $resultMaxid->fetch_assoc();
            $last_id = $arr_result['max_id'];

            $q = "SELECT start_hour, end_hour, field_id, date FROM booking WHERE member_id = ? AND id = ?";
            $stmt = $conn->prepare($q);
            if ($stmt) {
                $stmt->bind_param("si", $user_mail, $last_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $response = $result->fetch_assoc();
                } else {
                    $response = ['field_id' => 'N/A', 'start_hour' => 'N/A', 'end_hour' => 'N/A'];
                }
                echo json_encode($response);
            } else {
                echo json_encode(['error' => 'Error en la preparación de la consulta']);
            }
        } else {
            echo json_encode(['field_id' => 'N/A', 'start_hour' => 'N/A', 'end_hour' => 'N/A']);
        }
    } else {
        echo json_encode(['error' => 'Error al preparar la consulta de ID máximo']);
    }
}
?>
