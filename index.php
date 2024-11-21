<?php
// Configurar cabeçalhos básicos da API
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Verificar o método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Endpoint básico
if ($method === 'GET') {
    echo json_encode([
        'status' => 'success',
        'message' => 'API funcionando!'
    ]);
} else {
    http_response_code(405); // Método não permitido
    echo json_encode([
        'status' => 'error',
        'message' => 'Método não permitido'
    ]);
}
