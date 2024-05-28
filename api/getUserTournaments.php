<?php
// Permitir solicitudes cruzadas desde cualquier origen
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Incluir el archivo de conexión a la base de datos
require_once "../includes/db_connection.php";

// Verificar si el método de solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decodificar el cuerpo JSON de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    // Obtener el DNI del usuario desde los datos de la solicitud
    $dni = $data['dni'];

    // Consulta SQL para obtener los partidos en los que ha participado el usuario
    $q = "SELECT t.id as team_id, m.match_time, m.field_id, m.id as match_id FROM `match` m JOIN team t ON t.id = m.team1_id OR t.id = m.team2_id JOIN member me ON (me.dni_m = t.player1_dni OR me.dni_m = t.player2_dni) AND dni_m = '$dni'";
    $result = mysqli_query($conn, $q);
    
    // Verificar si la consulta devuelve resultados
    if (mysqli_num_rows($result) > 0) {
        
        // Obtener los resultados de la consulta
        $arr_result = mysqli_fetch_array($result, true);
        // $data = array(
        //     'success' => true,
        //     'time' => $arr_result['match_time'],
        //     'id' => $arr_result['id'],
        //     'field' => $arr_result['field_id'],
        // );
        $data = ['success' => true, 'time' => $arr_result['match_time'], 'id' => $arr_result['id'], 'field' => $arr_result['field_id']];

        // Consulta para obtener el DNI del segundo jugador del equipo
        $q1 = "SELECT player2_dni FROM team WHERE player1_dni = '$dni'";
        $resul1 = mysqli_query($conn, $q1);

        // Verificar si la consulta devuelve resultados
        if (mysqli_num_rows($resul1) > 0) {
            $arr_result = mysqli_fetch_assoc($resul1);
            $dni2 = $arr_result['player2_dni'];

            // Consulta para obtener el nombre del segundo jugador
            $q = "SELECT concat(name, ' ', surname1) as player2_name FROM user WHERE dni='$dni2'";
            $result = mysqli_query($conn, $q);
            $resul = mysqli_fetch_assoc($result);
            $player2name = $resul['player2_name'];
            $data['player2_name'] = $player2name;
        } else {
            // Consulta para obtener el DNI del primer jugador si el DNI proporcionado es del segundo jugador del equipo
            $q1 = "SELECT player1_dni FROM team WHERE player2_dni = '$dni'";
            $resul1 = mysqli_query($conn, $q1);

            $arr_result = mysqli_fetch_assoc($resul1);
            $dni2 = $arr_result['player2_dni'];

            // Consulta para obtener el nombre del primer jugador
            $q = "SELECT concat(name, ' ', surname1) as player2_name FROM user WHERE dni='$dni2'";
            $result = mysqli_query($conn, $q);
            $resul = mysqli_fetch_assoc($result);
            $player2name = $resul['player2_name'];
            $data['player2_name'] = $player2name;
        }

        // Establecer el tipo de contenido a JSON y devolver los datos codificados en JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        // Devolver una respuesta JSON indicando que no se encontraron resultados
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
    }
}
?>
