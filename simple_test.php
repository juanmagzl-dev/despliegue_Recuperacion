<?php
echo "🧪 TESTING MINESWEEPER PROJECT\n";
echo "================================\n\n";

// Test 1: Verificar que el archivo existe
echo "Test 1: Verificando archivos...\n";
if (file_exists('src/backend/MinesweeperMap.php')) {
    echo "✅ MinesweeperMap.php existe\n";
} else {
    echo "❌ MinesweeperMap.php NO encontrado\n";
    exit(1);
}

// Test 2: Cargar la clase
echo "\nTest 2: Cargando clase...\n";
try {
    require_once 'src/backend/MinesweeperMap.php';
    echo "✅ Clase MinesweeperMap cargada\n";
} catch (Exception $e) {
    echo "❌ Error cargando clase: " . $e->getMessage() . "\n";
    exit(1);
}

use Minesweeper\MinesweeperMap;

// Test 3: Crear mapa fácil
echo "\nTest 3: Mapa fácil...\n";
try {
    $easyMap = MinesweeperMap::fromDifficulty('easy');
    $easyMap->generateMap();
    $map = $easyMap->getMap();
    
    $rows = count($map);
    $cols = count($map[0]);
    $mines = 0;
    
    foreach ($map as $row) {
        foreach ($row as $cell) {
            if ($cell === -1) $mines++;
        }
    }
    
    echo "✅ Mapa fácil: {$rows}x{$cols} con {$mines} minas\n";
    
    if ($rows === 9 && $cols === 9 && $mines === 10) {
        echo "✅ Dimensiones correctas\n";
    } else {
        echo "❌ Dimensiones incorrectas\n";
    }
} catch (Exception $e) {
    echo "❌ Error en mapa fácil: " . $e->getMessage() . "\n";
}

// Test 4: Crear mapa personalizado
echo "\nTest 4: Mapa personalizado...\n";
try {
    $customMap = new MinesweeperMap(5, 5, 3);
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
    echo "❌ Error en mapa custom: " . $e->getMessage() . "\n";
}

// Test 5: JSON serialización
echo "\nTest 5: Serialización JSON...\n";
try {
    $map = new MinesweeperMap(3, 3, 2);
    $map->generateMap();
    
    $json = $map->toJson();
    $data = json_decode($json, true);
    
    if ($data && isset($data['rows']) && isset($data['cols']) && isset($data['mines'])) {
        echo "✅ JSON generado correctamente\n";
        echo "   Datos: {$data['rows']}x{$data['cols']} con {$data['mines']} minas\n";
    } else {
        echo "❌ JSON inválido\n";
    }
} catch (Exception $e) {
    echo "❌ Error en JSON: " . $e->getMessage() . "\n";
}

// Test 6: API Test
echo "\nTest 6: Verificando API...\n";
if (file_exists('src/frontend/api.php')) {
    echo "✅ API disponible en src/frontend/api.php\n";
} else {
    echo "❌ API no encontrada\n";
}

// Resumen
echo "\n🎉 TESTS COMPLETADOS\n";
echo "================\n";
echo "📊 Para tests más detallados:\n";
echo "   php tests/run_tests.php\n";
echo "📋 Para tests PHPUnit:\n";
echo "   composer install && vendor/bin/phpunit\n";
echo "🌐 Para probar en navegador:\n";
echo "   http://localhost:8000/test.html\n";
?> 