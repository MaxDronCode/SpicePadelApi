<?php
    // Permitir solicitudes cruzadas
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once "./includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $teacher_name = $data['teacher_name'];
        $teacher_surname1 = $data['teacher_surname1'];
        $date = $data['date'];
        $start_hour = $data['start_hour'];
        $field_id = $data['field_id'];
        $students_num = $data['students_num'];
        $end_hour = $data['end_hour'];

        
            $q4 = "INSERT INTO classes VALUES (DEFAULT, '$date', '$start_hour', '$field_id', '$end_hour', '$teacher_name', '$teacher_surname1', '$students_num')";
            if (mysqli_query($conn, $q4)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Nuevo clase creada']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Error al crear la clase']);
            }
        
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error en la consulta de la clase']);
    }

?>
