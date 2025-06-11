<?php
header('Content-Type: application/json');

echo json_encode([
    'status' => 'working',
    'message' => 'PHP is working correctly',
    'timestamp' => date('Y-m-d H:i:s')
]);
?> 