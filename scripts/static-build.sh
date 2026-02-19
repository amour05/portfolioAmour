#!/bin/bash

# Script local de développement et build pour la génération statique
# Usage: ./scripts/static-build.sh [local|prod]

set -e

MODE=${1:-local}
OUTPUT_DIR="dist"

echo "🚀 Starting static site build..."
echo "📋 Mode: $MODE"
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Fonction pour tracer l'exécution
log_section() {
    echo -e "${GREEN}✓${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

log_error() {
    echo -e "${RED}✗${NC} $1"
}

# Étape 1: Vérifier les conditions préalables
log_section "Vérifying prerequisites..."
command -v php > /dev/null || { log_error "PHP is not installed"; exit 1; }
command -v npm > /dev/null || { log_error "Node.js/npm is not installed"; exit 1; }
command -v composer > /dev/null || { log_error "Composer is not installed"; exit 1; }

# Étape 2: Installer les dépendances si nécessaire
if [ "$MODE" = "prod" ]; then
    log_section "Installing PHP dependencies..."
    composer install --no-dev --optimize-autoloader
    
    log_section "Installing Node dependencies..."
    npm ci
else
    log_section "Installing dependencies (dev)..."
    composer install
    npm install
fi

# Étape 3: Builder les assets
log_section "Building Vite assets..."
npm run build

# Étape 4: Préparer Laravel
log_section "Preparing Laravel environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    log_warning "Created .env file (configure it manually if needed)"
fi

php artisan key:generate --force 2>/dev/null || true

# Étape 5: Setup base de données SQLite
log_section "Setting up SQLite database..."
mkdir -p database
touch database/portfolio.sqlite
php artisan migrate --database=sqlite --force --no-interaction

# Étape 6: Seed la base si nécessaire
if [ -f database/seeders/DatabaseSeeder.php ]; then
    log_section "Seeding database..."
    php artisan db:seed --force --no-interaction 2>/dev/null || log_warning "Seeding failed (continuing anyway)"
fi

# Étape 7: Lancer le serveur Laravel en arrière-plan
log_section "Starting Laravel development server..."
php artisan serve --host=localhost --port=8000 > /dev/null 2>&1 &
LARAVEL_PID=$!
sleep 2

# Trap pour nettoyer le processus à la sortie
cleanup() {
    log_section "Cleaning up..."
    kill $LARAVEL_PID 2>/dev/null || true
}
trap cleanup EXIT

# Étape 8: Générer les fichiers statiques
log_section "Generating static site..."
php artisan static:generate --output=$OUTPUT_DIR

# Étape 9: Finaliser
log_section "Build complete!"
echo ""
echo "📁 Output directory: $OUTPUT_DIR"
echo "📊 Files generated:"
find $OUTPUT_DIR -type f | wc -l
echo ""
echo "🎉 Ready to deploy to GitHub Pages!"
echo "💡 Tip: Push your changes and enable GitHub Pages in repository settings"
echo ""
