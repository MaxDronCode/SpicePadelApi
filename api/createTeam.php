<?php
    // Permitir solicitudes cruzadas
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once "../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $user_mail1 = $data['user_mail1'];
        $user_mail2 = $data['user_mail2'];

        // Transformar el mail del primer participante a dni
        $q1 = "SELECT dni FROM user WHERE email = '$user_mail1'";
        $result1 = mysqli_query($conn, $q1);
        if ($result1) {
            $arr_result1 = mysqli_fetch_assoc($result1);
            $user_dni1 = $arr_result1['dni'];

            // Transformar el mail del segundo participante a dni
            $q2 = "SELECT dni FROM user WHERE email = '$user_mail2'";
            $result2 = mysqli_query($conn, $q2);
            if ($result2) {

                $arr_result2 = mysqli_fetch_assoc($result2);
                $user_dni2 = $arr_result2['dni'];

                // Comprobar que el user 2 no esté en otro equipo
                $q3 = "SELECT EXISTS (SELECT 1 FROM team WHERE player1_dni = '$user_dni2' OR player2_dni = '$user_dni2')";
                $result3 = mysqli_query($conn, $q3);
                if ($result3) {
                    
                    $arr_result3 = mysqli_fetch_array($result3);
                    
                    if ($arr_result3[0] == 1) { // Si ya existe

                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'El usuario 2 ya está en otro equipo']);
                    } else {
                        
                        $q4 = "INSERT INTO team VALUES ('DEFAULT', '$user_dni1', '$user_dni2')";
                        // mysqli_query($conn, $q4);
                        // header('Content-Type: application/json');
                        // echo json_encode(['success' => true, 'message' => 'Nuevo equipo creado']);
                        // echo json_encode(['success' => false, 'message' => "user_dni1:$user_dni1 i user_dni2:$user_dni2"]);
                        $result = mysqli_query($conn, $q4);
                        if ($result) {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => true, 'message' => 'Nuevo equipo creado']);
                        } else {
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'message' => 'Error al crear el equipo']);
                        }
                    }
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Error en la consulta de equipo']);
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Error al obtener DNI del usuario 2']);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error al obtener DNI del usuario 1']);
        }
    }
?>
