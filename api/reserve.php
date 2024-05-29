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
$start_hour_time = strtotime($start_hour);
$cutoff_time = strtotime('17:01');

if ($start_hour_time >= $cutoff_time) {
    echo json_encode(['success' => false, 'message' => 'No se puede reservar a partir de las 17:01']);
    exit; // Asegúrate de parar la ejecución si se cumple la condición
}

$cutoff_time = strtotime('07:59');
if ($start_hour_time <= $cutoff_time) {
    echo json_encode(['success' => false, 'message' => 'No se puede reservar antes de las 07:59']);
    exit; // Asegúrate de parar la ejecución si se cumple la condición
}


$q = "SELECT * FROM booking WHERE field_id = '$field_id' AND date = '$date' AND NOT ('$end_hour' <= start_hour OR '$start_hour' >= end_hour)";
$result = mysqli_query($conn, $q);
if (mysqli_num_rows($result) > 0) {
    echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva. No se pueden solapar las reservas']);
} else {

    // antes del insert hay que comprobar 
    $sql = "INSERT INTO booking (date, start_hour, end_hour, member_id, field_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $date, $start_hour, $end_hour, $member_id, $field_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Reserva realizada con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva: ' . $stmt->error]);
    }
    
}
$stmt->close();
$conn->close();
