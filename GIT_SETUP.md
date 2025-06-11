# 🌿 Configuración de Git - Minesweeper Project

## Comandos para configurar las ramas

### 1. Configuración inicial de Git
```bash
git config --global user.name "Tu Nombre"
git config --global user.email "tu.email@ejemplo.com"
```

### 2. Inicialización del repositorio
```bash
# Inicializar Git
git init

# Agregar archivos
git add .

# Commit inicial
git commit -m "✨ Initial commit - Complete Minesweeper Project"
```

### 3. Crear estructura de ramas
```bash
# Crear rama develop
git checkout -b develop

# Crear ramas de features  
git checkout -b feature/map-generator
git checkout develop
git checkout -b feature/save-load
git checkout develop
git checkout -b feature/frontend-ui
git checkout develop
git checkout -b feature/testing

# Volver a rama principal
git checkout main
```

### 4. Verificar ramas creadas
```bash
git branch -a
```

## 📋 Estructura de ramas

- **main**: Código de producción (siempre estable)
- **develop**: Rama de integración 
- **feature/map-generator**: Lógica de generación de mapas
- **feature/save-load**: Funcionalidad de guardado/carga
- **feature/frontend-ui**: Interfaz web y CSS
- **feature/testing**: Tests y CI/CD

## 🔄 Flujo de trabajo

1. **Desarrollo**: Trabajar en ramas `feature/*`
2. **Integración**: Merge a `develop` via Pull Request
3. **Producción**: Merge `develop` → `main` cuando esté listo

## 🚀 Comandos útiles

```bash
# Cambiar de rama
git checkout nombre-rama

# Ver todas las ramas
git branch -a

# Ver estado actual
git status

# Hacer commit
git add .
git commit -m "Descripción del cambio"

# Hacer merge (desde rama destino)
git merge nombre-rama-origen
```

## 📝 Convenciones de commits

- `✨ feat:` Nueva funcionalidad
- `🐛 fix:` Corrección de bug
- `📝 docs:` Documentación
- `🧪 test:` Tests
- `🎨 style:` Cambios de estilo/formato
- `♻️ refactor:` Refactorización de código 