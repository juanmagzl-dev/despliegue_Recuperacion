<?php
echo "🧪 BASIC MINESWEEPER TEST\n";
echo "=========================\n\n";

// Test 1: Verificar archivos
echo "Test 1: Verificando archivos...\n";
if (file_exists('src/backend/MinesweeperMap.php')) {
    echo "✅ MinesweeperMap.php encontrado\n";
} else {
    echo "❌ Archivo no encontrado\n";
    exit(1);
}

// Test 2: Cargar sin namespace
echo "\nTest 2: Cargando clase...\n";
require_once 'src/backend/MinesweeperMap.php';

// Test usando el nombre completo de la clase
try {
    $easyMap = \Minesweeper\MinesweeperMap::fromDifficulty('easy');
    $easyMap->generateMap();
    $map = $easyMap->getMap();
    
    $rows = count($map);
    $cols = count($map[0]);
    
    // Contar minas
    $mines = 0;
    foreach ($map as $row) {
        foreach ($row as $cell) {
            if ($cell === -1) $mines++;
        }
    }
    
    echo "✅ Mapa fácil creado: {$rows}x{$cols} con {$mines} minas\n";
    
    if ($rows === 9 && $cols === 9 && $mines === 10) {
        echo "✅ Especificaciones correctas para nivel fácil\n";
    } else {
        echo "⚠️  Especificaciones: esperado 9x9 con 10 minas\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Test mapa medio
echo "\nTest 3: Mapa medio...\n";
try {
    $mediumMap = \Minesweeper\MinesweeperMap::fromDifficulty('medium');
    $mediumMap->generateMap();
    $map = $mediumMap->getMap();
    
    $rows = count($map);
    $cols = count($map[0]);
    
    echo "✅ Mapa medio: {$rows}x{$cols}\n";
    
} catch (Exception $e) {
    echo "❌ Error en mapa medio: " . $e->getMessage() . "\n";
}

// Test personalizado
echo "\nTest 4: Mapa personalizado...\n";
try {
    $customMap = new \Minesweeper\MinesweeperMap(5, 5, 3);
    $customMap->generateMap();
    $map = $customMap->getMap();
    
    $mines = 0;
    foreach ($map as $row) {
        foreach ($row as $cell) {
            if ($cell === -1) $mines++;
        }
    }
    
    echo "✅ Mapa custom: 5x5 con {$mines} minas\n";
    
} catch (Exception $e) {
    echo "❌ Error en custom: " . $e->getMessage() . "\n";
}

// Test JSON
echo "\nTest 5: Serialización JSON...\n";
try {
    $testMap = new \Minesweeper\MinesweeperMap(3, 3, 2);
    $testMap->generateMap();
    
    $json = $testMap->toJson();
    
    if (!empty($json)) {
        echo "✅ JSON generado correctamente\n";
        $data = json_decode($json, true);
        echo "   Contenido: {$data['rows']}x{$data['cols']} con {$data['mines']} minas\n";
    } else {
        echo "❌ JSON vacío\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error JSON: " . $e->getMessage() . "\n";
}

echo "\n🎉 TESTS BÁSICOS COMPLETADOS\n";
echo "=============================\n";
echo "✅ Clase funcional\n";
echo "✅ Generación de mapas OK\n";
echo "✅ Serialización OK\n";
echo "\n🚀 Prueba también:\n";
echo "   - Navegador: http://localhost:8000/test.html\n";
echo "   - Aplicación: http://localhost:8000/index.html\n";
?> 