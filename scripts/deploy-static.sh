#!/bin/bash

###############################################################################
# Deploy Static Site to GitHub Pages (Main Branch)
# Script pour générer et déployer le site statique sur GitHub Pages
###############################################################################

set -e

echo "═════════════════════════════════════════════════════════════"
echo "🚀 Static Site Deployment Script"
echo "═════════════════════════════════════════════════════════════"
echo ""

# Configuration
OUTPUT_DIR=${1:-"dist"}
GIT_BRANCH=${2:-"main"}

# Check if we're in a git repository
if [ ! -d ".git" ]; then
    echo "❌ Error: Not in a git repository!"
    exit 1
fi

echo "📝 Configuration:"
echo "  Output directory: $OUTPUT_DIR"
echo "  Target branch: $GIT_BRANCH"
echo ""

# Step 1: Build assets
echo "📦 Step 1: Building assets with Vite..."
npm run build || {
    echo "❌ Failed to build assets"
    exit 1
}
echo "✅ Assets built successfully"
echo ""

# Step 2: Generate static site
echo "📦 Step 2: Generating static site..."
php artisan static:generate --output="$OUTPUT_DIR" || {
    echo "❌ Failed to generate static site"
    exit 1
}
echo "✅ Static site generated successfully"
echo ""

# Step 3: Verify index.html
echo "📦 Step 3: Verifying build..."
if [ ! -f "$OUTPUT_DIR/index.html" ]; then
    echo "❌ Error: $OUTPUT_DIR/index.html not found!"
    exit 1
fi
echo "✅ Verified: $OUTPUT_DIR/index.html exists"
ls -lh "$OUTPUT_DIR/index.html"
echo ""

# Step 4: Show summary
echo "📊 Build Summary:"
echo "  Total files: $(find "$OUTPUT_DIR" -type f | wc -l)"
echo "  Total directories: $(find "$OUTPUT_DIR" -type d | wc -l)"
echo "  Size: $(du -sh "$OUTPUT_DIR" | cut -f1)"
echo ""

echo "═════════════════════════════════════════════════════════════"
echo "✅ Static site is ready for deployment!"
echo "═════════════════════════════════════════════════════════════"
echo ""
echo "Next steps:"
echo "  1. Review the generated files in: $OUTPUT_DIR/"
echo "  2. Test locally: npx http-server $OUTPUT_DIR"
echo "  3. Push to GitHub: git add . && git commit -m 'Deploy static site' && git push"
echo ""
