<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include the MinesweeperMap class
$classFile = __DIR__ . '/backend/MinesweeperMap.php';
if (!file_exists($classFile)) {
    echo json_encode(['success' => false, 'error' => 'MinesweeperMap class file not found']);
    exit();
}

require_once $classFile;

// Check if class exists
if (!class_exists('Minesweeper\MinesweeperMap')) {
    echo json_encode(['success' => false, 'error' => 'MinesweeperMap class not found']);
    exit();
}

use Minesweeper\MinesweeperMap;

// Get action from POST or GET
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'generate':
            $difficulty = $_POST['difficulty'] ?? 'easy';
            $rows = intval($_POST['rows'] ?? 0);
            $cols = intval($_POST['cols'] ?? 0);
            $mines = intval($_POST['mines'] ?? 0);
            
            // Create map based on difficulty
            if ($difficulty === 'custom') {
                if ($rows <= 0 || $cols <= 0 || $mines <= 0) {
                    throw new Exception('Invalid parameters for custom mode');
                }
                if ($mines >= $rows * $cols) {
                    throw new Exception('Too many mines for map size');
                }
                $map = new MinesweeperMap($rows, $cols, $mines);
            } else {
                $map = MinesweeperMap::fromDifficulty($difficulty);
            }
            
            // Generate the map
            $map->generateMap();
            
            // Get dimensions based on difficulty
            switch ($difficulty) {
                case 'easy':
                    $mapRows = 9;
                    $mapCols = 9;
                    break;
                case 'medium':
                    $mapRows = 16;
                    $mapCols = 16;
                    break;
                case 'expert':
                    $mapRows = 30;
                    $mapCols = 16;
                    break;
                case 'custom':
                    $mapRows = $rows;
                    $mapCols = $cols;
                    break;
                default:
                    $mapRows = 9;
                    $mapCols = 9;
            }
            
            // Return response
            echo json_encode([
                'success' => true,
                'map' => $map->getMap(),
                'rows' => $mapRows,
                'cols' => $mapCols,
                'json' => $map->toJson()
            ]);
            break;
            
        case 'save':
            $mapData = $_POST['mapData'] ?? '';
            if (empty($mapData)) {
                throw new Exception('No map data to save');
            }
            
            $filename = 'maps/map_' . date('Y-m-d_H-i-s') . '.json';
            
            if (!is_dir('maps')) {
                mkdir('maps', 0755, true);
            }
            
            if (file_put_contents($filename, $mapData) === false) {
                throw new Exception('Failed to save map file');
            }
            
            echo json_encode([
                'success' => true,
                'filename' => $filename,
                'download' => $mapData
            ]);
            break;
            
        case 'load':
            if (!isset($_FILES['file'])) {
                throw new Exception('No file uploaded');
            }
            
            $uploadedFile = $_FILES['file'];
            if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload error');
            }
            
            $content = file_get_contents($uploadedFile['tmp_name']);
            if ($content === false) {
                throw new Exception('Failed to read uploaded file');
            }
            
            $data = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON file: ' . json_last_error_msg());
            }
            
            echo json_encode([
                'success' => true,
                'mapData' => $data
            ]);
            break;
            
        case '':
            echo json_encode([
                'success' => false,
                'error' => 'No action specified'
            ]);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'error' => 'Invalid action: ' . $action
            ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
} catch (Error $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Fatal error: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?> 