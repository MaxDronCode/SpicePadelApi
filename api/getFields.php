<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once "../includes/db_connection.php";

$sql = "SELECT id, name, description FROM field WHERE status = 'Available'";
$result = $conn->query($sql);

$fields = [];
while($row = $result->fetch_assoc()) {
    $fields[] = $row;
}

$conn->close();

echo json_encode($fields);
?>
