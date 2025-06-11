<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    echo json_encode([
        'step' => 1,
        'message' => 'Starting API debug',
        'files_exist' => [
            'MinesweeperMap.php' => file_exists('backend/MinesweeperMap.php')
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?> 