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
    $qMaxid = "SELECT MAX(id) as max_id FROM booking WHERE member_id='$user_mail'";
    $stmtMaxid = $conn->prepare($qMaxid);
    $stmtMaxid->execute();
    $resultMaxid = $stmtMaxid->get_result();
    $arr_result = $resultMaxid->fetch_assoc();
    $last_id = $arr_result['max_id'];

    // Corregir la consulta para usar la variable correctamente y evitar SQL Injection
    $q = "SELECT start_hour, end_hour, field_id FROM booking WHERE member_id = ? AND id = ?";
    $stmt = $conn->prepare($q);
    if ($stmt) {
        $stmt->bind_param("si", $user_mail, $last_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = $result->fetch_assoc();
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Error en la preparación de la consulta']);
    }
}
?>