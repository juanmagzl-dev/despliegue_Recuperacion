<?php
require_once '../../vendor/autoload.php';
use Minesweeper\MinesweeperMap;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'generate':
            $difficulty = $_POST['difficulty'] ?? '';
            $rows = intval($_POST['rows'] ?? 0);
            $cols = intval($_POST['cols'] ?? 0);
            $mines = intval($_POST['mines'] ?? 0);
            
            if ($difficulty === 'custom') {
                if ($rows <= 0 || $cols <= 0 || $mines <= 0) {
                    throw new Exception('Parámetros inválidos para modo custom');
                }
                $map = new MinesweeperMap($rows, $cols, $mines);
            } else {
                $map = MinesweeperMap::fromDifficulty($difficulty);
            }
            
            $map->generateMap();
            
            echo json_encode([
                'success' => true,
                'map' => $map->getMap(),
                'rows' => $map->rows ?? ($difficulty === 'easy' ? 9 : ($difficulty === 'medium' ? 16 : 30)),
                'cols' => $map->cols ?? ($difficulty === 'easy' ? 9 : ($difficulty === 'medium' ? 16 : 16)),
                'json' => $map->toJson()
            ]);
            break;
            
        case 'save':
            $mapData = $_POST['mapData'] ?? '';
            if (empty($mapData)) {
                throw new Exception('No hay datos del mapa para guardar');
            }
            
            $filename = 'maps/map_' . date('Y-m-d_H-i-s') . '.json';
            
            // Crear directorio si no existe
            if (!is_dir('maps')) {
                mkdir('maps', 0777, true);
            }
            
            file_put_contents($filename, $mapData);
            
            echo json_encode([
                'success' => true,
                'filename' => $filename,
                'download' => $mapData
            ]);
            break;
            
        case 'load':
            if (!isset($_FILES['file'])) {
                throw new Exception('No se ha subido ningún archivo');
            }
            
            $uploadedFile = $_FILES['file'];
            $content = file_get_contents($uploadedFile['tmp_name']);
            
            // Validar que sea un JSON válido
            $data = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('El archivo no contiene un JSON válido');
            }
            
            echo json_encode([
                'success' => true,
                'mapData' => $data
            ]);
            break;
            
        default:
            throw new Exception('Acción no válida');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} 