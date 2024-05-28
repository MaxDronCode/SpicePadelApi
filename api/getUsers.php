<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once "../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $player1_mail = $data['player1_mail'] ?? ''; // Asegúrate de que la variable existe

        // Usar consultas preparadas para evitar inyección SQL
        $sql = "SELECT u.email FROM user u 
                JOIN member m ON u.dni = m.dni_m
                WHERE NOT EXISTS (
                    SELECT 1 FROM team t WHERE t.player1_dni = m.dni_m OR t.player2_dni = m.dni_m
                )
                AND u.email <> ?"; // Asumiendo que el campo correcto es email

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo json_encode(['error' => 'Error preparando la consulta']);
            exit;
        }

        $stmt->bind_param("s", $player1_mail);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        echo json_encode($users);
    }
?>
