<?php
// Permetre solicituds creuades
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once"../includes/db_connection.php";

if($_SERVER['REQUEST_METHOD']=='POST'){
    $data = json_decode(file_get_contents('php://input'), true);

    $user_mail= $data['user_mail'];

    $q="SELECT dni from user where email = '$user_mail'";
    $result = mysqli_query($conn, $q);
    
    if(mysqli_num_rows($result)>0){
        
        $arr_result=mysqli_fetch_array($result, true);
    
        $user_dni=$arr_result['dni'];
    
        $q="SELECT id, player2_dni from team where player1_dni = '$user_dni'";
        $result = mysqli_query($conn, $q);
        $arr_result=mysqli_fetch_array($result, true);

        $response = [$arr_result['id'], $arr_result['player2_dni'], $arr_result['points']];
        header('Content-Type: application/json');
        echo json_encode($response);

    }else{

    }

}

?>
