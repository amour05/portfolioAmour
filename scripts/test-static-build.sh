#!/bin/bash
# 🧪 Script de test local pour la génération statique
# Usage: bash scripts/test-static-build.sh

set -e  # Exit on error

echo "🧪 Testing Local Static Build..."
echo "================================"
echo ""

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Check .env
echo "1️⃣ Checking .env file..."
if [ -f .env ]; then
    echo -e "${GREEN}✅ .env exists${NC}"
else
    echo -e "${YELLOW}⚠️ .env not found, creating from .env.example${NC}"
    cp .env.example .env
    php artisan key:generate --no-interaction
fi

# 2. Check database
echo ""
echo "2️⃣ Checking database..."
if [ -f database/portfolio.sqlite ]; then
    echo -e "${GREEN}✅ SQLite database exists${NC}"
else
    echo -e "${YELLOW}⚠️ Creating SQLite database${NC}"
    touch database/portfolio.sqlite
fi

# 3. Run migrations
echo ""
echo "3️⃣ Running migrations..."
php artisan migrate --database=sqlite --force --no-interaction

# 4. Seed database (optional)
echo ""
echo "4️⃣ Seeding database (optional)..."
php artisan db:seed --force --no-interaction || echo -e "${YELLOW}⚠️ Seeding skipped${NC}"

# 5. Check if npm dependencies are installed
echo ""
echo "5️⃣ Checking npm dependencies..."
if [ ! -d node_modules ]; then
    echo -e "${YELLOW}Installing npm dependencies...${NC}"
    npm install
fi

# 6. Build Vite assets
echo ""
echo "6️⃣ Building Vite assets..."
npm run build

# 7. Kill any existing Laravel servers on port 8000
echo ""
echo "7️⃣ Cleaning up old processes..."
lsof -ti :8000 | xargs kill -9 2>/dev/null || true
sleep 1

# 8. Start Laravel server
echo ""
echo "8️⃣ Starting Laravel development server..."
php artisan serve --host=localhost --port=8000 > /tmp/laravel-test.log 2>&1 &
SERVER_PID=$!
echo "Server PID: $SERVER_PID"

# 9. Wait for server with health check
echo ""
echo "9️⃣ Waiting for server to be ready..."
for i in {1..30}; do
    if curl -f http://localhost:8000/ > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Server is ready!${NC}"
        break
    fi
    if [ $i -eq 30 ]; then
        echo -e "${RED}❌ Server failed to start${NC}"
        cat /tmp/laravel-test.log
        kill $SERVER_PID 2>/dev/null || true
        exit 1
    fi
    echo "Attempt $i/30..."
    sleep 1
done

# 10. Clean old dist directory
echo ""
echo "🔟 Generating static site..."
rm -rf dist-test || true
mkdir -p dist-test

# 11. Generate static files
echo ""
echo "1️⃣1️⃣ Running static generator..."
if npm run build:static; then
    echo -e "${GREEN}✅ Static generation succeeded!${NC}"
else
    echo -e "${RED}❌ Static generation failed!${NC}"
    kill $SERVER_PID 2>/dev/null || true
    exit 1
fi

# 12. Verify dist/index.html
echo ""
echo "1️⃣2️⃣ Verifying dist/index.html..."
if [ -f dist/index.html ]; then
    echo -e "${GREEN}✅ dist/index.html found!${NC}"
    echo ""
    echo "📄 First 500 characters:"
    head -c 500 dist/index.html
else
    echo -e "${RED}❌ dist/index.html NOT FOUND!${NC}"
    echo ""
    echo "📁 dist/ contents:"
    ls -la dist/ || echo "dist/ directory doesn't exist!"
    kill $SERVER_PID 2>/dev/null || true
    exit 1
fi

# 13. Check for other static files
echo ""
echo ""
echo "1️⃣3️⃣ Checking static files..."
echo ""
echo "📁 dist/ directory structure:"
find dist/ -type f | head -20
echo ""
echo "📊 File count:"
echo "Total files: $(find dist/ -type f | wc -l)"

# 14. Cleanup
echo ""
echo "1️⃣4️⃣ Cleaning up..."
kill $SERVER_PID 2>/dev/null || true

echo ""
echo "================================"
echo -e "${GREEN}✅ Static build test complete!${NC}"
echo "================================"
echo ""
echo "Next steps:"
echo "  1. Commit your changes:"
echo "     git add -A && git commit -m 'Static build test passed'"
echo "  2. Push to GitHub:"
echo "     git push origin main"
echo "  3. Monitor the build:"
echo "     GitHub → Actions → build-and-deploy"
echo ""
