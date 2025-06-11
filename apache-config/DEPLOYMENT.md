# Minesweeper Map Generator - Apache HTTPS Deployment

## Características del Despliegue

### ✅ Requisitos Cumplidos

- **HTTPS obligatorio**: La aplicación solo acepta conexiones HTTPS
- **Sin indexado**: Los directorios no muestran su contenido automáticamente
- **Autenticación por grupos**: Las funcionalidades de carga de mapas requieren autenticación
- **Dominio configurado**: www.minesweepermapgenerator.com y www.minesweepermapgenerator.es
- **Raíz del sitio**: /var/www/https
- **Sin XAMPP**: Utiliza Apache puro con PHP
- **Dockerizado**: Puerto 8080 expuesto para Docker Desktop en Windows

## Configuración de Apache

### Virtual Host SSL (puerto 8443)
- ServerName: www.minesweepermapgenerator.com
- ServerAlias: www.minesweepermapgenerator.es
- DocumentRoot: /var/www/https
- Certificado SSL autofirmado (desarrollo)

### Seguridad
- Headers de seguridad HTTP configurados
- Redirección automática HTTP → HTTPS
- Directorio indexing deshabilitado
- Tokens del servidor ocultos

### Autenticación
- **Endpoints protegidos**: `/api/upload` y `/api/generate`
- **Método**: HTTP Basic Auth
- **Grupo requerido**: `mapgenerators`
- **Usuarios**: `mapuser1`, `mapuser2`
- **Contraseña**: `password`

## Docker Configuration

### Puertos Expuestos
- **8080**: HTTP (redirige a HTTPS)
- **8443**: HTTPS (puerto principal)

### Volúmenes
- `apache-logs`: Logs de Apache
- Certificados SSL generados automáticamente

## Comandos de Despliegue

### Construcción y Despliegue
```bash
# Opción 1: Script automatizado
./build.sh

# Opción 2: Docker Compose manual
docker-compose build
docker-compose up -d

# Opción 3: Docker manual
docker build -t minesweeper-web .
docker run -d -p 8080:8080 -p 8443:8443 minesweeper-web
```

### Verificación
```bash
# Verificar estado
docker-compose ps

# Ver logs
docker-compose logs -f

# Probar endpoints
curl http://localhost:8080/
curl -k https://localhost:8443/

# Probar autenticación
curl -k -u mapuser1:password https://localhost:8443/api/upload
```

## Estructura de Archivos

```
├── Dockerfile                     # Imagen Docker principal
├── docker-compose.yml             # Orquestación de servicios
├── build.sh                       # Script de construcción
├── .dockerignore                  # Archivos excluidos de Docker
├── apache-config/
│   ├── 000-default-ssl.conf       # Virtual Host Apache
│   └── DEPLOYMENT.md               # Esta documentación
└── src/                           # Código fuente de la aplicación
    ├── frontend/
    └── backend/
```

## URLs de Acceso

### Desarrollo Local
- **HTTP**: http://localhost:8080 (redirige a HTTPS)
- **HTTPS**: https://localhost:8443

### Producción (configuración DNS requerida)
- **HTTPS**: https://www.minesweepermapgenerator.com
- **HTTPS**: https://www.minesweepermapgenerator.es

## Credenciales de Acceso

### Usuarios del Grupo "mapgenerators"
| Usuario    | Contraseña | Grupo          |
|------------|------------|----------------|
| mapuser1   | password   | mapgenerators  |
| mapuser2   | password   | mapgenerators  |

## Endpoints Protegidos

### Requieren Autenticación
- `POST /api/upload` - Subida de mapas
- `POST /api/generate` - Generación de mapas

### Acceso Público
- `GET /` - Página principal
- `GET /api.php` - API general (sin funciones protegidas)

## Solución de Problemas

### Certificado SSL
Si hay problemas con el certificado:
```bash
# Regenerar certificado
docker exec -it minesweeper-apache-server openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout /etc/ssl/private/minesweeper.key \
  -out /etc/ssl/certs/minesweeper.crt \
  -subj "/CN=www.minesweepermapgenerator.com"
```

### Permisos
Si hay problemas de permisos:
```bash
docker exec -it minesweeper-apache-server chown -R www-data:www-data /var/www/https
docker exec -it minesweeper-apache-server chmod -R 755 /var/www/https
```

### Logs
Para depurar problemas:
```bash
# Logs de Apache
docker exec -it minesweeper-apache-server tail -f /var/log/apache2/minesweeper_error.log

# Logs de PHP
docker exec -it minesweeper-apache-server tail -f /var/log/apache2/php_errors.log
```

## Notas de Seguridad

⚠️ **Importante**: En producción debes:
1. Cambiar las contraseñas por defecto
2. Usar certificados SSL válidos (Let's Encrypt, CA)
3. Configurar un firewall apropiado
4. Actualizar regularmente la imagen base
5. Configurar copias de seguridad

## Next Steps

Para un despliegue en producción, considera:
- Reverse proxy (Nginx + Apache)
- Balanceador de carga
- Base de datos externa
- Monitoreo y logging centralizado
- CI/CD pipeline 