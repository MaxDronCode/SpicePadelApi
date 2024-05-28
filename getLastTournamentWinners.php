<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"./includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $q = "SELECT max(id) as max_id FROM tournament";
        $result = mysqli_query($conn, $q);
        $arr_result = mysqli_fetch_assoc($result);
        $last_id = $arr_result['max_id'];

        $q = "SELECT * FROM tournament WHERE id = '$last_id'";
        $result = mysqli_query($conn, $q);
        
        if (mysqli_num_rows($result) > 0){

            $arr_result = mysqli_fetch_assoc($result);
            $arr_result['success'] = true;

            header('Content-Type: application/json');
            echo json_encode($arr_result);


        } else {

            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No se ha podido acceder a los torneos']);
        }
    }
?>