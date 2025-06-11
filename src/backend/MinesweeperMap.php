<?php

namespace Minesweeper;

use Exception;
use SimpleXMLElement;

class MinesweeperMap {
    public $rows;
    public $cols;
    public $mines;
    private $map;
    
    public function __construct($rows, $cols, $mines) {
        $this->rows = $rows;
        $this->cols = $cols;
        $this->mines = $mines;
        $this->map = [];
    }
    
    public function generateMap() {
        // Inicializar el mapa con ceros
        $this->map = array_fill(0, $this->rows, array_fill(0, $this->cols, 0));
        
        // Colocar minas aleatoriamente
        $minesPlaced = 0;
        while ($minesPlaced < $this->mines) {
            $row = rand(0, $this->rows - 1);
            $col = rand(0, $this->cols - 1);
            
            if ($this->map[$row][$col] !== -1) {
                $this->map[$row][$col] = -1; // -1 representa una mina
                $minesPlaced++;
            }
        }
        
        // Calcular números para cada casilla
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                if ($this->map[$i][$j] !== -1) {
                    $this->map[$i][$j] = $this->countAdjacentMines($i, $j);
                }
            }
        }
    }
    
    private function countAdjacentMines($row, $col) {
        $count = 0;
        
        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {
                $newRow = $row + $i;
                $newCol = $col + $j;
                
                if ($newRow >= 0 && $newRow < $this->rows && 
                    $newCol >= 0 && $newCol < $this->cols && 
                    $this->map[$newRow][$newCol] === -1) {
                    $count++;
                }
            }
        }
        
        return $count;
    }
    
    public function getMap() {
        return $this->map;
    }
    
    public function toJson() {
        return json_encode([
            'rows' => $this->rows,
            'cols' => $this->cols,
            'mines' => $this->mines,
            'map' => $this->map
        ]);
    }
    
    public function fromJson($json) {
        $data = json_decode($json, true);
        $this->rows = $data['rows'];
        $this->cols = $data['cols'];
        $this->mines = $data['mines'];
        $this->map = $data['map'];
    }
    
    public function toXml() {
        $xml = new SimpleXMLElement('<minesweeper/>');
        $xml->addChild('rows', $this->rows);
        $xml->addChild('cols', $this->cols);
        $xml->addChild('mines', $this->mines);
        
        $mapElement = $xml->addChild('map');
        foreach ($this->map as $rowIndex => $row) {
            $rowElement = $mapElement->addChild('row');
            $rowElement->addAttribute('index', $rowIndex);
            foreach ($row as $colIndex => $value) {
                $cell = $rowElement->addChild('cell', $value);
                $cell->addAttribute('col', $colIndex);
            }
        }
        
        return $xml->asXML();
    }
    
    public function fromXml($xml) {
        $data = simplexml_load_string($xml);
        $this->rows = (int)$data->rows;
        $this->cols = (int)$data->cols;
        $this->mines = (int)$data->mines;
        
        $this->map = [];
        foreach ($data->map->row as $row) {
            $rowIndex = (int)$row['index'];
            $this->map[$rowIndex] = [];
            foreach ($row->cell as $cell) {
                $colIndex = (int)$cell['col'];
                $this->map[$rowIndex][$colIndex] = (int)$cell;
            }
        }
    }
    
    public static function fromDifficulty($level) {
        switch ($level) {
            case 'easy':
                return new self(9, 9, 10);
            case 'medium':
                return new self(16, 16, 40);
            case 'expert':
                return new self(30, 16, 99);
            default:
                throw new Exception("Nivel de dificultad no válido");
        }
    }
    
    public function saveToFile($filename) {
        $json = $this->toJson();
        file_put_contents($filename, $json);
    }
    
    public static function loadFromFile($filename) {
        if (!file_exists($filename)) {
            throw new Exception("El archivo no existe");
        }
        
        $json = file_get_contents($filename);
        $map = new self(1, 1, 1); // Valores temporales
        $map->fromJson($json);
        return $map;
    }
} 