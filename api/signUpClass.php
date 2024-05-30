<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once "../includes/db_connection.php";

$data = json_decode(file_get_contents("php://input"));

$member_id = $data->member_id;
$class_id = $data->class_id;
$field_id = $data->field_id;


    // antes del insert hay que comprobar 
    $sql = "INSERT INTO class_book (dni_m, class_id, field_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $member_id, $class_id, $field_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Te has apuntado a la classe con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al apuntarse a la classe: ' . $stmt->error]);
    }

    

$conn->close();
