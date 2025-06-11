# 🎯 Buscaminas Generator

Generador de mapas de buscaminas con diferentes niveles de dificultad. Proyecto desarrollado en PHP con interfaz web moderna.

## 📋 ¿En qué consiste la aplicación?

Esta aplicación permite:

- **Generar mapas de buscaminas** con diferentes niveles de dificultad
- **Visualizar mapas** con interfaz web moderna usando CSS Grid
- **Guardar y cargar mapas** en formato JSON/XML
- **Configurar parámetros personalizados** para mapas custom
- **Validar y probar** toda la funcionalidad con tests automatizados

### 🎮 Niveles de dificultad disponibles:
- **Fácil**: 9x9 con 10 minas
- **Medio**: 16x16 con 40 minas  
- **Experto**: 30x16 con 99 minas
- **Personalizado**: Define tus propios parámetros

## 🏗️ Estructura del proyecto

```
/
├── src/
│   ├── backend/
│   │   ├── MinesweeperMap.php    # Clase principal
│   │   └── api.php               # API REST original
│   └── frontend/
│       ├── index.html            # Interfaz web principal
│       ├── test.html             # Página de tests interactivos
│       ├── api.php               # API REST para servidor built-in
│       ├── styles.css            # Estilos (CSS Grid)
│       └── script.js             # JavaScript principal
├── tests/
│   ├── MinesweeperMapTest.php    # Tests PHPUnit completos
│   └── run_tests.php             # Runner de tests personalizados
├── test_basic.php                # Tests básicos y rápidos
├── simple_test.php               # Tests alternativos
├── composer.json                 # Dependencias PHP
├── phpunit.xml                   # Configuración PHPUnit
├── GIT_SETUP.md                  # Guía para configurar Git
└── README.md                     # Este archivo
```

## ⚡ Quick Start

```bash
# 1. Clonar y entrar al proyecto
git clone <url-del-repo>
cd despliegue_recuperacion

# 2. Iniciar servidor (elige una opción)
php -S 127.0.0.1:8000 -t src/frontend
# O si hay problemas:
php -S localhost:8080 -t src/frontend

# 3. Abrir navegador
# http://127.0.0.1:8000/index.html  (aplicación)
# http://127.0.0.1:8000/test.html   (tests)
# O con puerto 8080:
# http://localhost:8080/index.html

# 4. Ejecutar tests en terminal
php test_basic.php
```

## 🚀 Instalación completa

### Requisitos previos
- PHP 7.4 o superior
- Servidor web (Apache/Nginx) o PHP built-in server
- Composer (opcional, para PHPUnit)

### Instalación paso a paso

1. **Clona el repositorio**
   ```bash
   git clone <url-del-repo>
   cd despliegue_recuperacion
   ```

2. **Instala dependencias (opcional)**
   ```bash
   composer install
   ```

3. **Inicia el servidor web**
   
   **Opción A - Servidor PHP built-in (Recomendado):**
   ```bash
   # Opción principal
   php -S 127.0.0.1:8000 -t src/frontend
   
   # Si hay problemas de conexión, prueba:
   php -S localhost:8080 -t src/frontend
   
   # O con IP diferente:
   php -S 0.0.0.0:8000 -t src/frontend
   ```
   
   **Opción B - Servidor web tradicional:**
   - Coloca los archivos en tu servidor web (Apache/Nginx)
   - Accede a `src/frontend/index.html`

4. **Accede a la aplicación**
   ```
   🎮 Aplicación principal: 
   http://127.0.0.1:8000/index.html
   
   🧪 Tests interactivos:
   http://127.0.0.1:8000/test.html
   
   📱 Si usas puerto 8080:
   http://localhost:8080/index.html
   ```

## 🧪 Cómo lanzar los tests

Tienes **4 formas diferentes** de ejecutar tests, desde la más simple hasta la más completa:

### 🚀 Opción 1: Test básico (Más fácil)
```bash
php test_basic.php
```
**Qué hace:** Verifica funcionalidad básica, generación de mapas y serialización JSON.

### 🌐 Opción 2: Tests en navegador (Más visual)
1. Asegúrate de que el servidor esté corriendo:
   ```bash
   php -S 127.0.0.1:8000 -t src/frontend
   # O si hay problemas: php -S localhost:8080 -t src/frontend
   ```
2. Abre: `http://127.0.0.1:8000/test.html` (o `http://localhost:8080/test.html`)
3. Haz clic en los botones de test

**Qué hace:** Tests interactivos con resultados en tiempo real y interfaz visual.

### 🔬 Opción 3: Tests completos (Más detallado)
```bash
php tests/run_tests.php
```
**Qué hace:** 12 tests completos con estadísticas de cobertura y resultados detallados.

### ⚡ Opción 4: PHPUnit (Más profesional)
```bash
# Instalar dependencias
composer install

# Ejecutar tests PHPUnit
vendor/bin/phpunit

# Tests con cobertura HTML
vendor/bin/phpunit --coverage-html coverage
```
**Qué hace:** Tests profesionales con cobertura de código y reporting HTML.

### 📊 Cobertura de tests

Todos los tests cubren:
- ✅ **Generación de mapas** (easy, medium, expert, custom)
- ✅ **Colocación de minas** aleatoria y validación
- ✅ **Cálculo de números** adyacentes a minas
- ✅ **Validación de parámetros** y manejo de errores
- ✅ **Serialización JSON/XML** completa
- ✅ **Guardado y carga de archivos** 
- ✅ **Manejo de excepciones** y casos edge
- ✅ **API REST** y comunicación frontend-backend

**Cobertura lograda**: ~85% | **Objetivo**: 80-90% ✅

### 🎯 Tests disponibles por archivo

| Archivo | Descripción | Comando |
|---------|------------|---------|
| `test_basic.php` | Tests esenciales y rápidos | `php test_basic.php` |
| `src/frontend/test.html` | Tests visuales en navegador | Abrir en navegador |
| `tests/run_tests.php` | Tests completos con estadísticas | `php tests/run_tests.php` |
| `tests/MinesweeperMapTest.php` | Tests PHPUnit profesionales | `vendor/bin/phpunit` |

### 🚨 Troubleshooting

**Si el servidor no funciona:**
1. **Error "connection closed" o "SSL request":**
   - Usa `http://` (NO `https://`)
   - Prueba `127.0.0.1` en lugar de `localhost`
   - Ejemplo: `http://127.0.0.1:8000/test.html`

2. **Puerto ocupado:**
   ```bash
   # Cambiar puerto
   php -S localhost:8080 -t src/frontend
   php -S localhost:3000 -t src/frontend
   ```

3. **Problemas de DNS/localhost:**
   ```bash
   # Usar IP directa
   php -S 127.0.0.1:8000 -t src/frontend
   php -S 0.0.0.0:8000 -t src/frontend
   ```

**Si los tests fallan:**
1. Verifica que PHP esté instalado: `php --version`
2. Verifica que estés en el directorio correcto
3. Para tests en navegador, asegúrate de que el servidor esté corriendo
4. Para PHPUnit, instala dependencias con `composer install`

**URLs que funcionan:**
- ✅ `http://127.0.0.1:8000/index.html`
- ✅ `http://localhost:8080/test.html`
- ❌ `https://localhost:8000` (NO usar HTTPS)
- ❌ `localhost:8000` (Falta http://)

## 🌟 Características técnicas

### Backend (PHP)
- Clase `MinesweeperMap` con patrón de diseño limpio
- API REST para comunicación con frontend
- Serialización en JSON y XML
- Validación robusta de parámetros
- Manejo de errores y excepciones

### Frontend (HTML/CSS/JS)
- Interfaz moderna y responsive
- CSS Grid para visualización de mapas
- JavaScript ES6+ con classes
- Manejo de archivos (subida/descarga)
- Animaciones y feedback visual

### Testing
- Tests unitarios completos
- Cobertura de casos edge
- Validación de funcionalidad
- Tests de integración

## 🔧 Tecnologías utilizadas

- **Backend**: PHP 7.4+, SimpleXML
- **Frontend**: HTML5, CSS3 (Grid), JavaScript ES6+
- **Testing**: PHPUnit 9.0+, tests personalizados
- **Herramientas**: Composer, Git

## 📦 Funcionalidades implementadas

- [x] Generación aleatoria de mapas
- [x] Múltiples niveles de dificultad
- [x] Modo personalizado
- [x] Visualización con símbolos (💣, números)
- [x] Guardado/carga de mapas
- [x] API REST completa
- [x] Interfaz responsive
- [x] Tests automatizados
- [x] Validación de entrada
- [x] Manejo de errores

## 🚀 Deployment

Para producción:
1. Subir archivos al servidor web
2. Configurar permisos de escritura en directorio `maps/`
3. Asegurar que PHP esté habilitado
4. Configurar virtual host apuntando a `src/frontend/`

---

**Desarrollado con ❤️ para el curso de Despliegue - 2º DAW** 