<?php
require_once '../src/backend/MinesweeperMap.php';

use Minesweeper\MinesweeperMap;

class TestRunner {
    private $passed = 0;
    private $failed = 0;
    
    public function test($name, $callable) {
        echo "🧪 Testing: $name... ";
        try {
            $callable();
            echo "✅ PASSED\n";
            $this->passed++;
        } catch (Exception $e) {
            echo "❌ FAILED: " . $e->getMessage() . "\n";
            $this->failed++;
        }
    }
    
    public function assertEquals($expected, $actual, $message = '') {
        if ($expected !== $actual) {
            throw new Exception($message ?: "Expected $expected but got $actual");
        }
    }
    
    public function assertCount($expected, $array, $message = '') {
        $actual = count($array);
        if ($expected !== $actual) {
            throw new Exception($message ?: "Expected count $expected but got $actual");
        }
    }
    
    public function assertTrue($condition, $message = '') {
        if (!$condition) {
            throw new Exception($message ?: "Expected true but got false");
        }
    }
    
    public function assertNotEmpty($value, $message = '') {
        if (empty($value)) {
            throw new Exception($message ?: "Expected non-empty value");
        }
    }
    
    public function assertFileExists($file, $message = '') {
        if (!file_exists($file)) {
            throw new Exception($message ?: "File $file does not exist");
        }
    }
    
    public function summary() {
        $total = $this->passed + $this->failed;
        echo "\n📊 Test Results:\n";
        echo "✅ Passed: {$this->passed}/$total\n";
        echo "❌ Failed: {$this->failed}/$total\n";
        
        if ($this->failed === 0) {
            echo "🎉 All tests passed!\n";
        }
        
        $coverage = $total > 0 ? round(($this->passed / $total) * 100, 1) : 0;
        echo "📈 Coverage: $coverage%\n";
        
        // Return appropriate exit code
        return $this->failed === 0 ? 0 : 1;
    }
    
    private function countMines($map) {
        $count = 0;
        foreach ($map as $row) {
            foreach ($row as $cell) {
                if ($cell === -1) $count++;
            }
        }
        return $count;
    }
    
    public function runAllTests() {
        echo "🚀 Starting Minesweeper Tests\n\n";
        
        // Test 1: Constructor
        $this->test('Constructor', function() {
            $map = new MinesweeperMap(10, 10, 15);
            $this->assertTrue($map instanceof MinesweeperMap);
        });
        
        // Test 2: Easy Difficulty
        $this->test('Easy Difficulty', function() {
            $map = MinesweeperMap::fromDifficulty('easy');
            $map->generateMap();
            $mapArray = $map->getMap();
            
            $this->assertCount(9, $mapArray, 'Should have 9 rows');
            $this->assertCount(9, $mapArray[0], 'Should have 9 columns');
            $this->assertEquals(10, $this->countMines($mapArray), 'Should have 10 mines');
        });
        
        // Test 3: Medium Difficulty
        $this->test('Medium Difficulty', function() {
            $map = MinesweeperMap::fromDifficulty('medium');
            $map->generateMap();
            $mapArray = $map->getMap();
            
            $this->assertCount(16, $mapArray);
            $this->assertCount(16, $mapArray[0]);
            $this->assertEquals(40, $this->countMines($mapArray));
        });
        
        // Test 4: Expert Difficulty
        $this->test('Expert Difficulty', function() {
            $map = MinesweeperMap::fromDifficulty('expert');
            $map->generateMap();
            $mapArray = $map->getMap();
            
            $this->assertCount(30, $mapArray);
            $this->assertCount(16, $mapArray[0]);
            $this->assertEquals(99, $this->countMines($mapArray));
        });
        
        // Test 5: Custom Map
        $this->test('Custom Map', function() {
            $map = new MinesweeperMap(5, 7, 8);
            $map->generateMap();
            $mapArray = $map->getMap();
            
            $this->assertCount(5, $mapArray);
            $this->assertCount(7, $mapArray[0]);
            $this->assertEquals(8, $this->countMines($mapArray));
        });
        
        // Test 6: Invalid Difficulty
        $this->test('Invalid Difficulty Exception', function() {
            try {
                MinesweeperMap::fromDifficulty('invalid');
                throw new Exception('Should have thrown exception');
            } catch (Exception $e) {
                $this->assertTrue(strpos($e->getMessage(), 'Nivel de dificultad no válido') !== false);
            }
        });
        
        // Test 7: JSON Serialization
        $this->test('JSON Serialization', function() {
            $map = new MinesweeperMap(4, 4, 3);
            $map->generateMap();
            
            $json = $map->toJson();
            $this->assertNotEmpty($json);
            
            $data = json_decode($json, true);
            $this->assertEquals(4, $data['rows']);
            $this->assertEquals(4, $data['cols']);
            $this->assertEquals(3, $data['mines']);
        });
        
        // Test 8: JSON Deserialization
        $this->test('JSON Deserialization', function() {
            $originalMap = new MinesweeperMap(3, 3, 2);
            $originalMap->generateMap();
            
            $json = $originalMap->toJson();
            
            $newMap = new MinesweeperMap(1, 1, 1);
            $newMap->fromJson($json);
            
            $this->assertEquals($originalMap->getMap(), $newMap->getMap());
        });
        
        // Test 9: XML Serialization
        $this->test('XML Serialization', function() {
            $map = new MinesweeperMap(3, 3, 2);
            $map->generateMap();
            
            $xml = $map->toXml();
            $this->assertNotEmpty($xml);
            $this->assertTrue(strpos($xml, '<minesweeper>') !== false);
            $this->assertTrue(strpos($xml, '<rows>3</rows>') !== false);
        });
        
        // Test 10: File Operations
        $this->test('Save and Load File', function() {
            $originalMap = new MinesweeperMap(3, 3, 1);
            $originalMap->generateMap();
            
            $filename = 'test_map.json';
            $originalMap->saveToFile($filename);
            
            $this->assertFileExists($filename);
            
            $loadedMap = MinesweeperMap::loadFromFile($filename);
            $this->assertEquals($originalMap->getMap(), $loadedMap->getMap());
            
            unlink($filename);
        });
        
        // Test 11: Map Validation
        $this->test('Map Cell Values Validation', function() {
            $map = new MinesweeperMap(5, 5, 5);
            $map->generateMap();
            
            $mapArray = $map->getMap();
            
            foreach ($mapArray as $row) {
                foreach ($row as $cell) {
                    $this->assertTrue($cell >= -1 && $cell <= 8, 'Cell value should be between -1 and 8');
                }
            }
        });
        
        // Test 12: Empty Map Before Generation
        $this->test('Empty Map Before Generation', function() {
            $map = new MinesweeperMap(3, 3, 2);
            $mapArray = $map->getMap();
            $this->assertEquals([], $mapArray);
        });
        
        $this->summary();
    }
}

$runner = new TestRunner();
$exitCode = $runner->runAllTests();
exit($exitCode); 