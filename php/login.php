<?php
session_start();

// Obtener los datos enviados en el cuerpo de la solicitud
$input = json_decode(file_get_contents("php://input"), true);

// Validar y guardar los datos del usuario en la sesión
if ($input) {
    $_SESSION['user'] = [
        'username' => $input['username'],
        'nombre' => $input['nombre'],
        'edad' => $input['edad'],
        'genero' => $input['genero'],
        'profesion' => $input['profesion']
    ];
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
?>
