# Use official PHP with Apache image
FROM php:8.2-apache

# Set maintainer label
LABEL maintainer="juanm"
LABEL description="Minesweeper Map Generator - Apache HTTPS Server"

# Install system dependencies
RUN apt-get update && apt-get install -y \
    ssl-cert \
    nano \
    curl \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod ssl \
    && a2enmod rewrite \
    && a2enmod headers \
    && a2enmod expires \
    && a2enmod deflate \
    && a2enmod auth_basic \
    && a2enmod authn_file \
    && a2enmod authz_user \
    && a2enmod authz_groupfile

# Create necessary directories
RUN mkdir -p /var/www/https \
    && mkdir -p /etc/ssl/certs \
    && mkdir -p /etc/ssl/private

# Copy application files
COPY src/ /var/www/https/
COPY apache-config/000-default-ssl.conf /etc/apache2/sites-available/

# Organize application structure
RUN cp /var/www/https/frontend/* /var/www/https/ \
    && mkdir -p /var/www/https/api \
    && cp /var/www/https/backend/* /var/www/https/api/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/https \
    && chmod -R 755 /var/www/https

# Generate self-signed SSL certificate for development
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/minesweeper.key \
    -out /etc/ssl/certs/minesweeper.crt \
    -subj "/C=ES/ST=Madrid/L=Madrid/O=MinesweeperMapGenerator/OU=IT Department/CN=www.minesweepermapgenerator.com/emailAddress=admin@minesweepermapgenerator.com"

# Create htpasswd and htgroups files for authentication
RUN echo "mapuser1:\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi" > /etc/apache2/.htpasswd \
    && echo "mapuser2:\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi" >> /etc/apache2/.htpasswd \
    && echo "mapgenerators: mapuser1 mapuser2" > /etc/apache2/.htgroups

# Configure Apache ports for Docker
RUN echo "Listen 8080" >> /etc/apache2/ports.conf \
    && echo "Listen 8443" >> /etc/apache2/ports.conf

# Update virtual host to use port 8080 for HTTP and 8443 for HTTPS
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default-ssl.conf \
    && sed -i 's/<VirtualHost \*:443>/<VirtualHost *:8443>/' /etc/apache2/sites-available/000-default-ssl.conf

# Enable the SSL site
RUN a2ensite 000-default-ssl

# Disable default Apache site
RUN a2dissite 000-default

# Create Apache security configuration
RUN echo "ServerTokens Prod" >> /etc/apache2/conf-available/security.conf \
    && echo "ServerSignature Off" >> /etc/apache2/conf-available/security.conf

# Copy custom PHP configuration if needed
RUN echo "display_errors = Off" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "error_log = /var/log/apache2/php_errors.log" >> /usr/local/etc/php/conf.d/custom.ini

# Expose ports
EXPOSE 8080 8443

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8080/ || exit 1

# Start Apache in foreground
CMD ["apache2-foreground"] 