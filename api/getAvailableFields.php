<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once "../includes/db_connection.php";

$field_id = $_GET['id'];
$date = $_GET['date'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT start_hour, end_hour FROM booking WHERE field_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $field_id, $date);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($bookings);
?>
