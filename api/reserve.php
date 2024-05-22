<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once "../includes/db_connection.php";

$data = json_decode(file_get_contents("php://input"));

$date = $data->date;
$start_hour = $data->start_hour;
$end_hour = $data->end_hour;
$member_id = $data->member_id;
$field_id = $data->field_id;

if (!$date || !$start_hour || !$end_hour || !$member_id || !$field_id) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
    exit;
}

$sql = "INSERT INTO booking (date, start_hour, end_hour, member_id, field_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $date, $start_hour, $end_hour, $member_id, $field_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Reserva realizada con éxito']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva']);
}

$stmt->close();
$conn->close();
?>

