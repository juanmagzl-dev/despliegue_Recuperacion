<?php
// Test simple para verificar que la API funciona
header('Content-Type: application/json');

// Test básico
echo json_encode([
    'success' => true,
    'message' => 'API funcionando correctamente',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => phpversion()
]);
?> 