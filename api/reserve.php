<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "../includes/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    $field_id = $data['field_id'];
    $date = $data['date'];
    $hour = $data['hour'];
    $member_id = $data['member_id'];

    if (empty($field_id) || empty($date) || empty($hour) || empty($member_id)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
        exit();
    }

    //validamos que la fecha de reserva sea mínimamente con un dia de antelación por si aca
    $currentDate = new DateTime();
    $reservationDate = new DateTime($date);

    //diff es una funcion que saca un intervalo entre dos fechas
    $interval = $currentDate->diff($reservationDate);

    if ($interval->days < 1 || $reservationDate < $currentDate) {
        echo json_encode(['success' => false, 'message' => 'La fecha de la reserva debe ser al menos con un día de antelación']);
        exit();
    }

    $query = "INSERT INTO booking (field_id, date, hour, member_id) VALUES ('$field_id', '$date', '$hour', '$member_id')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Reserva realizada con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al realizar la reserva: ' . mysqli_error($conn)]);
    }
} mysqli_close($conn);
?>
