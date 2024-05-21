<?php
// Permitir solicitudes cruzadas
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "../includes/db_connection.php";

$response = ['success' => false, 'message' => 'Unknown error'];

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['dni'], $data['name'], $data['surename1'], $data['surename2'], $data['phone'], $data['email'], $data['birthday'], $data['bank_account'], $data['address'], $data['password'])) {
            $response['message'] = 'Todos los campos son requeridos';
            throw new Exception($response['message']);
        }

        $dni = $data['dni'];
        $name = $data['name'];
        $surename1 = $data['surename1'];
        $surename2 = $data['surename2'];
        $phone = $data['phone'];
        $email = $data['email'];
        $birthday = $data['birthday'];
        $bank_account = $data['bank_account'];
        $address = $data['address'];
        $password = $data['password'];

        // Comprobar que el DNI no exista ya en la BD usando consultas preparadas para prevenir inyecciones SQL
        $stmt = $conn->prepare("SELECT EXISTS (SELECT 1 FROM user WHERE dni = ?)");
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $stmt->bind_result($exists);
        $stmt->fetch();
        $stmt->close();

        if ($exists) {
            $response['message'] = 'El usuario ya existe';
        } else {
            // Iniciar una transacción
            $conn->begin_transaction();
            try {
                // Insertar en la tabla user
                $stmt = $conn->prepare("INSERT INTO user (dni, name, surname1, surname2, phone, email, address, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $dni, $name, $surename1, $surename2, $phone, $email, $address, $password);
                
                if (!$stmt->execute()) {
                    throw new Exception("Error en la ejecución de la consulta: " . $stmt->error);
                }
                $stmt->close();

                // Insertar en la tabla member
                $stmt = $conn->prepare("INSERT INTO member (dni, birthday, bank_account) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $dni, $birthday, $bank_account);
                
                if (!$stmt->execute()) {
                    throw new Exception("Error en la ejecución de la consulta: " . $stmt->error);
                }
                $stmt->close();

                // Si todo va bien, confirmar la transacción
                $conn->commit();

                // Generar el token
                $token = bin2hex(random_bytes(16));
                $response = ['success' => true, 'message' => 'Dado de alta correctamente', 'token' => $token, 'user_mail' => $email];
            } catch (Exception $e) {
                // Si ocurre un error, deshacer la transacción
                $conn->rollback();
                $response['message'] = $e->getMessage();
            }
        }
    } else {
        $response['message'] = 'Método no permitido';
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>
