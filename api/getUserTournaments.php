<?php
// Permetre solicituds creuades
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once"../includes/db_connection.php";

if($_SERVER['REQUEST_METHOD']=='POST'){
    $data = json_decode(file_get_contents('php://input'), true);

    $dni=$data['dni'];

    $q = " SELECT * FROM `match` JOIN team ON team1_id=id OR team2_id=id WHERE player1_dni = id ";

}
?>