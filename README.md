# 🎮 Minesweeper Map Generator

**Generador de Mapas de Buscaminas con Docker y Apache HTTPS**

## 📋 Descripción del Proyecto

Aplicación web desarrollada en PHP que permite generar, visualizar y gestionar mapas del juego Buscaminas. La aplicación está completamente dockerizada y desplegada usando Apache con HTTPS y autenticación de usuarios.

## 🏗️ Arquitectura Técnica

### **Stack Tecnológico:**
- **Backend**: PHP 8.2 con Apache
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Servidor Web**: Apache 2.4 con SSL/TLS
- **Contenedorización**: Docker
- **Autenticación**: Apache Basic Auth

### **Estructura del Proyecto:**
```
├── Dockerfile                 # Imagen Docker principal
├── docker-compose.yml        # Orquestación (opcional)
├── apache-config/            # Configuración Apache
│   └── 000-default-ssl.conf  # VirtualHost HTTPS
├── src/                      # Código fuente
│   ├── frontend/            # HTML, CSS, JS
│   └── backend/             # PHP, API
└── README.md                # Este archivo
```

## 🐳 Configuración Docker

### **Base de la Imagen:**
```dockerfile
FROM php:8.2-apache
```

### **Componentes Instalados:**
- **SSL Certificate**: Certificado auto-firmado para desarrollo
- **Apache Modules**: ssl, rewrite, headers, expires, deflate, auth_basic
- **PHP Extensions**: Configuración personalizada
- **Usuarios**: Sistema de autenticación con grupos

### **Puertos Expuestos:**
- **8080**: HTTP (redirige a HTTPS)
- **8443**: HTTPS (principal)

### **Características del Dockerfile:**
- **Multi-stage build**: Optimizado para producción
- **SSL auto-firmado**: Generado automáticamente para desarrollo
- **Usuarios pre-configurados**: Sistema de autenticación listo
- **Módulos Apache**: Habilitados automáticamente (ssl, rewrite, headers)
- **Permisos**: Configurados correctamente para www-data
- **Health check**: Monitoreo automático del estado del contenedor

## 🔧 Configuración Apache

### **Virtual Hosts:**
```apache
# HTTPS (Principal)
<VirtualHost *:8443>
    ServerName www.minesweepermapgenerator.com
    ServerAlias www.minesweepermapgenerator.es
    DocumentRoot /var/www/https
    SSLEngine on
</VirtualHost>

# HTTP (Redirección)
<VirtualHost *:8080>
    # Redirige automáticamente a HTTPS
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>
```

### **Características de Seguridad:**
- ✅ **HTTPS Obligatorio**: Redirección automática HTTP → HTTPS
- ✅ **Headers de Seguridad**: HSTS, X-Frame-Options, X-XSS-Protection
- ✅ **Indexado Deshabilitado**: `Options -Indexes`
- ✅ **Tokens de Servidor Ocultos**: `ServerTokens Prod`

### **Autenticación:**
- **Tipo**: Basic Authentication
- **Usuarios**: `mapuser1`, `mapuser2`
- **Contraseña**: `password`
- **Grupo**: `mapgenerators`

## 🚀 Instalación y Uso

### **1. Clonar el Repositorio:**
```bash
git clone <repository-url>
cd despliegue_recuperacion
```

### **2. Construir la Imagen Docker:**
```bash
docker build -t minesweeper-app .
```

### **3. Ejecutar el Contenedor:**
```bash
docker run -d -p 8081:8080 -p 8444:8443 --name minesweeper-app minesweeper-app
```

### **4. Acceder a la Aplicación:**
- **URL Principal**: https://localhost:8444
- **Alternativa HTTP**: http://localhost:8081 (redirige a HTTPS)

⚠️ **Nota**: Aceptar el certificado SSL auto-firmado en el navegador.

## 🎯 Funcionalidades

### **🌐 Funcionalidades Públicas (Sin Autenticación):**
- ✅ **Generar Mapas**: Crear mapas con diferentes dificultades
  - Fácil: 9x9, 10 minas
  - Medio: 16x16, 40 minas
  - Experto: 30x16, 99 minas
  - Personalizado: Dimensiones y minas configurables
- ✅ **Visualizar Mapas**: Interfaz gráfica con números y minas
- ✅ **Descargar Localmente**: Exportar mapas como archivos JSON
- ✅ **Cargar Archivos Locales**: Importar mapas desde el ordenador

### **🔐 Funcionalidades Protegidas (Requieren Autenticación):**
- 🔐 **Guardar en Servidor**: Almacenar mapas en el servidor
- 🔐 **Cargar desde Servidor**: Recuperar mapas guardados
- 🔐 **Subir al Servidor**: Upload de archivos de mapas

### **Credenciales de Acceso:**
```
Usuario: mapuser1
Contraseña: password

Usuario: mapuser2
Contraseña: password
```

## 🏃‍♂️ Scripts de Automatización

### **build.sh** - Construcción Automática:
```bash
#!/bin/bash
docker build -t minesweeper-app .
docker run -d -p 8081:8080 -p 8444:8443 --name minesweeper-app minesweeper-app
```

### **docker-compose.yml** - Orquestación:
```yaml
version: '3.8'
services:
  minesweeper:
    build: .
    ports:
      - "8081:8080"
      - "8444:8443"
    container_name: minesweeper-app
```

## 🔍 Verificación y Testing

### **Verificar Estado del Contenedor:**
```bash
# Ver contenedores en ejecución
docker ps

# Ver logs del contenedor
docker logs minesweeper-app

# Ver logs en tiempo real
docker logs -f minesweeper-app

# Inspeccionar el contenedor
docker inspect minesweeper-app

# Entrar al contenedor
docker exec -it minesweeper-app bash
```

### **Verificar Configuración Apache:**
```bash
docker exec minesweeper-app apache2ctl -S
docker exec minesweeper-app apache2ctl -t
```

### **Probar Funcionalidades:**
```bash
# Generar mapa (público)
curl -k -X POST -d "action=generate&difficulty=easy" https://localhost:8444/api/api.php

# Guardar mapa (requiere auth)
curl -k -u mapuser1:password -X POST -d "action=save&mapData={}" https://localhost:8444/api/api.php
```

## 📊 Estructura de la API

### **Endpoint Principal:**
```
POST /api/api.php
```

### **Acciones Disponibles:**

| Acción | Autenticación | Descripción |
|--------|---------------|-------------|
| `generate` | ❌ No | Generar nuevo mapa |
| `save` | ✅ Sí | Guardar mapa en servidor |
| `load` | ✅ Sí | Cargar mapa desde servidor |
| `upload` | ✅ Sí | Subir archivo al servidor |

### **Ejemplo de Respuesta:**
```json
{
  "success": true,
  "map": [[0,1,-1], [1,2,1], [0,0,0]],
  "rows": 3,
  "cols": 3,
  "json": "{\"rows\":3,\"cols\":3,\"mines\":1,\"map\":[[0,1,-1],[1,2,1],[0,0,0]]}"
}
```

## 🛠️ Troubleshooting

### **Problema: Error "Unexpected end of JSON input"**
- **Causa**: Problema de conectividad con la API
- **Solución**: Verificar que el contenedor esté ejecutándose

### **Problema: Certificado SSL no confiable**
- **Causa**: Certificado auto-firmado
- **Solución**: Aceptar el certificado en el navegador

### **Problema: Autenticación falla**
- **Causa**: Credenciales incorrectas
- **Solución**: Usar `mapuser1` o `mapuser2` con contraseña `password`

### **Problema: Puerto ocupado**
- **Causa**: Otro servicio usando el puerto
- **Solución**: 
```bash
# Cambiar puertos
docker run -d -p 8082:8080 -p 8445:8443 --name minesweeper-app minesweeper-app
```

## ⚡ Optimización y Rendimiento

### **Configuraciones de Rendimiento:**
- **Compresión**: Módulo deflate habilitado para archivos estáticos
- **Caché**: Headers de expiración configurados
- **Seguridad**: Headers de seguridad (HSTS, XSS Protection)
- **PHP**: Configuración optimizada para producción
- **Apache**: Configuración de memoria y procesos optimizada

### **Monitoreo:**
- **Health Check**: Verificación automática cada 30 segundos
- **Logs**: Archivos de log separados por VirtualHost
- **Métricas**: Acceso a estadísticas de Apache via mod_status

## 📋 Requisitos Cumplidos

✅ **HTTPS Obligatorio**: Redirección automática HTTP → HTTPS  
✅ **Indexado Deshabilitado**: `Options -Indexes`  
✅ **Autenticación por Grupos**: Sistema de usuarios y grupos  
✅ **Dominio Configurado**: `www.minesweepermapgenerator.com`  
✅ **Raíz Correcta**: `/var/www/https`  
✅ **Sin XAMPP**: Apache oficial Docker  
✅ **Dockerizado**: Imagen funcional en puerto 8080  

## 👥 Contribución

Para contribuir al proyecto:
1. Fork del repositorio
2. Crear rama feature: `git checkout -b feature/nueva-funcionalidad`
3. Commit cambios: `git commit -m 'Añadir nueva funcionalidad'`
4. Push a la rama: `git push origin feature/nueva-funcionalidad`
5. Crear Pull Request

## 🎯 Autor

**Juan Manuel** - Proyecto de Despliegue de Aplicaciones Web

---