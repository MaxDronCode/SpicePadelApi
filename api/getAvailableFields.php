<?php
    // Permetre solicituds creuades
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require_once"../includes/db_connection.php";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $q = "SELECT * FROM field WHERE LOWER(status) = 'available'";
        $result = mysqli_query($conn, $q);

        $data = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        echo json_encode($data);
    }
?>