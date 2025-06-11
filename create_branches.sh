#!/bin/bash

echo "🌿 Creating Git branches for Minesweeper project..."

# Crear rama develop
git checkout -b develop 2>/dev/null || git checkout develop
echo "✅ Branch 'develop' ready"

# Crear ramas de features
git checkout -b feature/map-generator 2>/dev/null || git checkout feature/map-generator
echo "✅ Branch 'feature/map-generator' ready"

git checkout develop
git checkout -b feature/save-load 2>/dev/null || git checkout feature/save-load
echo "✅ Branch 'feature/save-load' ready"

git checkout develop
git checkout -b feature/frontend-ui 2>/dev/null || git checkout feature/frontend-ui
echo "✅ Branch 'feature/frontend-ui' ready"

git checkout develop
git checkout -b feature/testing 2>/dev/null || git checkout feature/testing
echo "✅ Branch 'feature/testing' ready"

# Volver a main
git checkout main 2>/dev/null || git checkout master

echo ""
echo "📋 Available branches:"
git branch

echo ""
echo "🎯 Branch strategy:"
echo "  - main/master: Production-ready code"
echo "  - develop: Integration branch"
echo "  - feature/*: Feature development"
echo ""
echo "💡 Workflow:"
echo "  1. Work on feature branches"
echo "  2. Merge to develop via PR"
echo "  3. Merge develop to main when ready" 