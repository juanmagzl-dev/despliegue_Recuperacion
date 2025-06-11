#!/bin/bash

# Minesweeper Map Generator - Docker Build Script
# This script builds and deploys the Apache HTTPS server

echo "🚀 Building Minesweeper Map Generator Docker Image..."

# Stop and remove existing containers
echo "📦 Stopping existing containers..."
docker-compose down 2>/dev/null || true

# Build the Docker image
echo "🔨 Building Docker image..."
docker-compose build --no-cache

# Start the services
echo "▶️ Starting services..."
docker-compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 10

# Check if services are running
echo "🔍 Checking service status..."
docker-compose ps

# Display connection information
echo ""
echo "✅ Deployment complete!"
echo ""
echo "🌐 Your Minesweeper Map Generator is now available at:"
echo "   HTTP:  http://localhost:8080"
echo "   HTTPS: https://localhost:8443"
echo ""
echo "🔐 For protected map generation endpoints, use these credentials:"
echo "   Username: mapuser1 or mapuser2"
echo "   Password: password"
echo ""
echo "📁 Application root: /var/www/https"
echo "🏠 Domain: www.minesweepermapgenerator.com or www.minesweepermapgenerator.es"
echo ""
echo "📝 To view logs: docker-compose logs -f"
echo "🛑 To stop: docker-compose down"
echo ""

# Test the endpoints
echo "🧪 Testing endpoints..."
echo "Testing HTTP endpoint..."
curl -s -o /dev/null -w "HTTP Status: %{http_code}\n" http://localhost:8080/ || echo "HTTP endpoint not ready yet"

echo "Testing HTTPS endpoint (self-signed certificate)..."
curl -s -k -o /dev/null -w "HTTPS Status: %{http_code}\n" https://localhost:8443/ || echo "HTTPS endpoint not ready yet"

echo ""
echo "🎮 Happy mining!" 