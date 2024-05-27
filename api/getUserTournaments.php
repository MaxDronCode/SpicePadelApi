<?php
// Permetre solicituds creuades
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once"../includes/db_connection.php";

if($_SERVER['REQUEST_METHOD']=='POST'){
    $data = json_decode(file_get_contents('php://input'), true);

    $dni=$data['dni'];

    // consulta que devuelve los partidos que ha tenido el usuario.
    $q = "SELECT t.id as team_id, m.match_time, m.field_id, m.id as match_id FROM `match` m JOIN team t ON t.id = m.team1_id OR t.id = m.team2_id JOIN member me ON (me.dni_m = t.player1_dni OR me.dni_m = t.player2_dni) AND dni_m = '$dni'";
    $result = mysqli_query($conn, $q);
    
    // este if saca la informacion de los partidos
    if(mysqli_num_rows($result)>0){
        
        $arr_result = mysqli_fetch_array($result, true);
        $data = array(
            'success' => true,
            'time' => $arr_result['match_time'],
            'id' => $arr_result['id'],
            'field' => $arr_result['field_id'],
        );


        $q1 = "SELECT player2_dni FROM team WHERE player1_dni = '$dni'";
        $resul1= mysqli_query($conn, $q1);

        if(mysqli_num_rows($resul1)>0){
            $arr_result = mysqli_fetch_assoc($result);
            $dni2 = $arr_result['player2_dni'];

            $q="SELECT concat(name, ' ', surname1) as player2_name FROM user WHERE dni='$dni2'";
            $result = mysqli_query($conn, $q);
            $resul= mysqli_fetch_assoc($result);
            $player2name=$resul['player2_name'];
            $data['player2_name'] = $player2name;
        } else {
            $q1 = "SELECT player1_dni FROM team WHERE player2_dni = '$dni'";
            $resul1= mysqli_query($conn, $q1);

            $arr_result = mysqli_fetch_assoc($result);
            $dni2 = $arr_result['player2_dni'];

            $q="SELECT concat(name, ' ', surname1) as player2_name FROM user WHERE dni='$dni2'";
            $result = mysqli_query($conn, $q);
            $resul= mysqli_fetch_assoc($result);
            $player2name=$resul['player2_name'];
            $data['player2_name'] = $player2name;

        }

        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
    }
}
?>