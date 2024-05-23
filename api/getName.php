<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = json_decode(file_get_contents('php://input'), true);

        $user_email = $data['user_email'];

        $q= "SELECT CONCAT(name, ' ', surname1) AS player1_name FROM user WHERE email='$user_email'";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_assoc($result);
        if ($arr_result) {
            $user_name = $arr_result['player1_name'];
            $response = ['user_name' => $user_name];
        }
       header('Content-Type: application/json');
        echo json_encode($response);
        
    }
?>