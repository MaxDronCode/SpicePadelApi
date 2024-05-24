<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once "../includes/db_connection.php";

$field_id = $_GET['field_id'] ?? null; // Utiliza el operador de fusión null de PHP 7+

if (is_null($field_id) || !is_numeric($field_id)) {
    http_response_code(400); // Código de estado HTTP para "Solicitud Incorrecta"
    echo json_encode(['error' => 'field_id invalid or not provided']);
    exit;
}

$sql = "SELECT * FROM booking WHERE field_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param('i', $field_id); // 'i' indica que la variable es de tipo entero
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Error executing query']);
    exit;
}

$fields = [];
while ($row = $result->fetch_assoc()) {
    $fields[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($fields);
?>
