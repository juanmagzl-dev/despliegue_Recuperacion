<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    $result = ['steps' => []];
    
    $result['steps'][] = 'Step 1: Starting debug';
    
    // Verificar si el archivo existe
    if (!file_exists('backend/MinesweeperMap.php')) {
        throw new Exception('File backend/MinesweeperMap.php not found');
    }
    $result['steps'][] = 'Step 2: File found';
    
    // Intentar cargar el archivo
    require_once 'backend/MinesweeperMap.php';
    $result['steps'][] = 'Step 3: File loaded';
    
    // Intentar usar la clase
    use Minesweeper\MinesweeperMap;
    $result['steps'][] = 'Step 4: Namespace imported';
    
    // Crear instancia
    $map = MinesweeperMap::fromDifficulty('easy');
    $result['steps'][] = 'Step 5: Instance created';
    
    // Generar mapa
    $map->generateMap();
    $result['steps'][] = 'Step 6: Map generated';
    
    // Obtener datos
    $mapData = $map->getMap();
    $result['steps'][] = 'Step 7: Map data obtained';
    
    $result['success'] = true;
    $result['map_sample'] = array_slice($mapData, 0, 3); // Primeras 3 filas como muestra
    
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'steps' => $result['steps'] ?? []
    ]);
}
?> 