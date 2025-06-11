// Generador de Mapas Buscaminas - JavaScript

class MinesweeperUI {
    constructor() {
        this.currentMap = null;
        this.currentMapData = null;
        this.selectedDifficulty = 'easy';
        this.initEventListeners();
    }
    
    initEventListeners() {
        document.querySelectorAll('[data-difficulty]').forEach(btn => {
            btn.addEventListener('click', (e) => this.selectDifficulty(e.target.dataset.difficulty));
        });
        
        document.getElementById('generateBtn').addEventListener('click', () => this.generateMap());
        document.getElementById('saveBtn').addEventListener('click', () => this.saveMap());
        document.getElementById('loadBtn').addEventListener('click', () => this.triggerFileLoad());
        document.getElementById('loadInput').addEventListener('change', (e) => this.loadMap(e));
        
        this.selectDifficulty('easy');
    }
    
    selectDifficulty(difficulty) {
        document.querySelectorAll('[data-difficulty]').forEach(btn => {
            btn.classList.remove('selected');
        });
        document.querySelector(`[data-difficulty="${difficulty}"]`).classList.add('selected');
        
        this.selectedDifficulty = difficulty;
        
        const customParams = document.getElementById('customParams');
        customParams.style.display = difficulty === 'custom' ? 'block' : 'none';
    }
    
    async generateMap() {
        this.showLoading(true);
        
        try {
            const formData = new FormData();
            formData.append('action', 'generate');
            formData.append('difficulty', this.selectedDifficulty);
            
            if (this.selectedDifficulty === 'custom') {
                const rows = parseInt(document.getElementById('rows').value);
                const cols = parseInt(document.getElementById('cols').value);
                const mines = parseInt(document.getElementById('mines').value);
                
                if (rows <= 0 || cols <= 0 || mines <= 0) {
                    throw new Error('Por favor, introduce valores válidos');
                }
                
                if (mines >= rows * cols) {
                    throw new Error('Demasiadas minas para el tamaño del mapa');
                }
                
                formData.append('rows', rows);
                formData.append('cols', cols);
                formData.append('mines', mines);
            }
            
            const response = await fetch('api.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.currentMap = data.map;
                this.currentMapData = data.json;
                this.renderMap(data.map, data.rows, data.cols);
                this.showMapInfo(data.rows, data.cols, this.countMines(data.map));
                document.getElementById('saveBtn').disabled = false;
            } else {
                this.showError(data.error || 'Error al generar el mapa');
            }
            
        } catch (error) {
            this.showError(error.message);
        } finally {
            this.showLoading(false);
        }
    }
    
    renderMap(map, rows, cols) {
        const mapGrid = document.getElementById('mapGrid');
        mapGrid.innerHTML = '';
        
        mapGrid.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
        
        for (let i = 0; i < rows; i++) {
            for (let j = 0; j < cols; j++) {
                const cell = document.createElement('div');
                cell.className = 'cell';
                
                const value = map[i][j];
                
                if (value === -1) {
                    cell.classList.add('mine');
                    cell.textContent = '💣';
                } else if (value > 0) {
                    cell.classList.add('number', `number-${value}`);
                    cell.textContent = value;
                }
                
                mapGrid.appendChild(cell);
            }
        }
    }
    
    showMapInfo(rows, cols, mines) {
        const mapInfo = document.getElementById('mapInfo');
        const mapStats = document.getElementById('mapStats');
        
        mapStats.textContent = `Mapa: ${rows}x${cols} | Minas: ${mines}`;
        mapInfo.style.display = 'block';
    }
    
    countMines(map) {
        let count = 0;
        for (let row of map) {
            for (let cell of row) {
                if (cell === -1) count++;
            }
        }
        return count;
    }
    
    saveMap() {
        if (!this.currentMapData) {
            this.showError('No hay mapa para guardar');
            return;
        }
        
        const blob = new Blob([this.currentMapData], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `minesweeper_map.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showSuccess('Mapa guardado');
    }
    
    triggerFileLoad() {
        document.getElementById('loadInput').click();
    }
    
    async loadMap(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        try {
            const content = await this.readFile(file);
            const mapData = JSON.parse(content);
            
            this.currentMap = mapData.map;
            this.currentMapData = content;
            this.renderMap(mapData.map, mapData.rows, mapData.cols);
            this.showMapInfo(mapData.rows, mapData.cols, mapData.mines);
            document.getElementById('saveBtn').disabled = false;
            
            this.showSuccess('Mapa cargado');
            
        } catch (error) {
            this.showError('Error al cargar archivo');
        }
        
        event.target.value = '';
    }
    
    readFile(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = e => resolve(e.target.result);
            reader.onerror = reject;
            reader.readAsText(file);
        });
    }
    
    showLoading(show) {
        document.getElementById('loading').style.display = show ? 'block' : 'none';
        document.getElementById('generateBtn').disabled = show;
    }
    
    showError(message) {
        alert('Error: ' + message);
    }
    
    showSuccess(message) {
        alert('Éxito: ' + message);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new MinesweeperUI();
}); 