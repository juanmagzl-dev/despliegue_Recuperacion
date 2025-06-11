<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Minesweeper\MinesweeperMap;

class MinesweeperMapTest extends TestCase
{
    public function testConstructor()
    {
        $map = new MinesweeperMap(10, 10, 15);
        $this->assertInstanceOf(MinesweeperMap::class, $map);
    }

    public function testEasyDifficultyCreation()
    {
        $map = MinesweeperMap::fromDifficulty('easy');
        $map->generateMap();
        
        $mapArray = $map->getMap();
        
        $this->assertCount(9, $mapArray, 'Easy map should have 9 rows');
        $this->assertCount(9, $mapArray[0], 'Easy map should have 9 columns');
        
        $mineCount = $this->countMines($mapArray);
        $this->assertEquals(10, $mineCount, 'Easy map should have 10 mines');
    }

    public function testMediumDifficultyCreation()
    {
        $map = MinesweeperMap::fromDifficulty('medium');
        $map->generateMap();
        
        $mapArray = $map->getMap();
        
        $this->assertCount(16, $mapArray, 'Medium map should have 16 rows');
        $this->assertCount(16, $mapArray[0], 'Medium map should have 16 columns');
        
        $mineCount = $this->countMines($mapArray);
        $this->assertEquals(40, $mineCount, 'Medium map should have 40 mines');
    }

    public function testExpertDifficultyCreation()
    {
        $map = MinesweeperMap::fromDifficulty('expert');
        $map->generateMap();
        
        $mapArray = $map->getMap();
        
        $this->assertCount(30, $mapArray, 'Expert map should have 30 rows');
        $this->assertCount(16, $mapArray[0], 'Expert map should have 16 columns');
        
        $mineCount = $this->countMines($mapArray);
        $this->assertEquals(99, $mineCount, 'Expert map should have 99 mines');
    }

    public function testInvalidDifficultyThrowsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Nivel de dificultad no válido');
        
        MinesweeperMap::fromDifficulty('invalid');
    }

    public function testCustomMapCreation()
    {
        $rows = 5;
        $cols = 7;
        $mines = 8;
        
        $map = new MinesweeperMap($rows, $cols, $mines);
        $map->generateMap();
        
        $mapArray = $map->getMap();
        
        $this->assertCount($rows, $mapArray);
        $this->assertCount($cols, $mapArray[0]);
        
        $mineCount = $this->countMines($mapArray);
        $this->assertEquals($mines, $mineCount);
    }

    public function testMapGenerationValidation()
    {
        $map = new MinesweeperMap(5, 5, 5);
        $map->generateMap();
        
        $mapArray = $map->getMap();
        
        $mineCount = $this->countMines($mapArray);
        $this->assertEquals(5, $mineCount);
        
        foreach ($mapArray as $row) {
            foreach ($row as $cell) {
                $this->assertTrue($cell >= -1 && $cell <= 8, 'Cell value should be between -1 and 8');
            }
        }
    }

    public function testJsonSerialization()
    {
        $map = new MinesweeperMap(4, 4, 3);
        $map->generateMap();
        
        $json = $map->toJson();
        
        $this->assertJson($json, 'Should produce valid JSON');
        
        $data = json_decode($json, true);
        $this->assertArrayHasKey('rows', $data);
        $this->assertArrayHasKey('cols', $data);
        $this->assertArrayHasKey('mines', $data);
        $this->assertArrayHasKey('map', $data);
        
        $this->assertEquals(4, $data['rows']);
        $this->assertEquals(4, $data['cols']);
        $this->assertEquals(3, $data['mines']);
    }

    public function testJsonDeserialization()
    {
        $originalMap = new MinesweeperMap(3, 3, 2);
        $originalMap->generateMap();
        
        $json = $originalMap->toJson();
        
        $newMap = new MinesweeperMap(1, 1, 1);
        $newMap->fromJson($json);
        
        $this->assertEquals($originalMap->getMap(), $newMap->getMap());
    }

    public function testXmlSerialization()
    {
        $map = new MinesweeperMap(3, 3, 2);
        $map->generateMap();
        
        $xml = $map->toXml();
        
        $this->assertNotEmpty($xml, 'XML should not be empty');
        $this->assertStringContainsString('<minesweeper>', $xml);
        $this->assertStringContainsString('<rows>3</rows>', $xml);
        $this->assertStringContainsString('<cols>3</cols>', $xml);
        $this->assertStringContainsString('<mines>2</mines>', $xml);
    }

    public function testXmlDeserialization()
    {
        $originalMap = new MinesweeperMap(3, 3, 2);
        $originalMap->generateMap();
        
        $xml = $originalMap->toXml();
        
        $newMap = new MinesweeperMap(1, 1, 1);
        $newMap->fromXml($xml);
        
        $this->assertEquals($originalMap->getMap(), $newMap->getMap());
    }

    public function testSaveToFile()
    {
        $map = new MinesweeperMap(3, 3, 1);
        $map->generateMap();
        
        $filename = 'test_map.json';
        $map->saveToFile($filename);
        
        $this->assertFileExists($filename);
        
        $content = file_get_contents($filename);
        $this->assertJson($content);
        
        unlink($filename);
    }

    public function testLoadFromFile()
    {
        $originalMap = new MinesweeperMap(3, 3, 1);
        $originalMap->generateMap();
        
        $filename = 'test_map.json';
        $originalMap->saveToFile($filename);
        
        $loadedMap = MinesweeperMap::loadFromFile($filename);
        
        $this->assertEquals($originalMap->getMap(), $loadedMap->getMap());
        
        unlink($filename);
    }

    public function testLoadFromNonExistentFileThrowsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('El archivo no existe');
        
        MinesweeperMap::loadFromFile('non_existent_file.json');
    }

    public function testGetMapReturnsArray()
    {
        $map = new MinesweeperMap(2, 2, 1);
        $map->generateMap();
        
        $mapArray = $map->getMap();
        
        $this->assertIsArray($mapArray);
        $this->assertCount(2, $mapArray);
        $this->assertIsArray($mapArray[0]);
        $this->assertCount(2, $mapArray[0]);
    }

    public function testEmptyMapBeforeGeneration()
    {
        $map = new MinesweeperMap(3, 3, 2);
        
        $mapArray = $map->getMap();
        $this->assertEquals([], $mapArray, 'Map should be empty before generation');
    }

    private function countMines(array $map): int
    {
        $count = 0;
        foreach ($map as $row) {
            foreach ($row as $cell) {
                if ($cell === -1) {
                    $count++;
                }
            }
        }
        return $count;
    }
} 