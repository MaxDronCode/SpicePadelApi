<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once "../includes/db_connection.php";


$sql = "SELECT * FROM classes";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}

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
