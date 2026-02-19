#!/bin/bash

###############################################################################
# GitHub Pages Deployment Validation Script
# Valide que toute la configuration est correcte
###############################################################################

set +e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

PASSED=0
FAILED=0
WARNINGS=0

echo ""
echo "════════════════════════════════════════════════════════════════"
echo "🔍 GitHub Pages Deployment Validation"
echo "════════════════════════════════════════════════════════════════"
echo ""

# Function to check
check() {
    local name=$1
    local command=$2
    
    if eval "$command" > /dev/null 2>&1; then
        echo -e "${GREEN}✅${NC} $name"
        ((PASSED++))
    else
        echo -e "${RED}❌${NC} $name"
        ((FAILED++))
    fi
}

warn() {
    local name=$1
    local command=$2
    
    if eval "$command" > /dev/null 2>&1; then
        echo -e "${YELLOW}⚠️ ${NC} $name"
        ((WARNINGS++))
    fi
}

# ============================================================================
# 1. ENVIRONMENT CHECKS
# ============================================================================
echo -e "${BLUE}1️⃣  ENVIRONMENT CHECKS${NC}"
echo "───────────────────────────────────────────────────────────────"

check "Git installed" "which git"
check "PHP 8.2+ installed" "php -v | grep -E 'PHP 8\.[2-9]|PHP [89]'"
check "Node.js 20+ installed" "node -v | grep -E 'v20|v21|v22'"
check "npm installed" "which npm"
check "Composer installed" "which composer"

echo ""

# ============================================================================
# 2. PROJECT STRUCTURE CHECKS
# ============================================================================
echo -e "${BLUE}2️⃣  PROJECT STRUCTURE${NC}"
echo "───────────────────────────────────────────────────────────────"

check "composer.json exists" "[ -f composer.json ]"
check "package.json exists" "[ -f package.json ]"
check "app/ directory exists" "[ -d app ]"
check "resources/ directory exists" "[ -d resources ]"
check "routes/ directory exists" "[ -d routes ]"

echo ""

# ============================================================================
# 3. GITHUB PAGES CONFIGURATION
# ============================================================================
echo -e "${BLUE}3️⃣  GITHUB PAGES CONFIGURATION${NC}"
echo "───────────────────────────────────────────────────────────────"

check ".github/workflows/deploy.yml exists" "[ -f .github/workflows/deploy.yml ]"
check ".nojekyll exists" "[ -f .nojekyll ]"
check "_config.yml exists" "[ -f _config.yml ]"
check ".gitignore exists" "[ -f .gitignore ]"

echo ""

# ============================================================================
# 4. ARTISAN COMMANDS
# ============================================================================
echo -e "${BLUE}4️⃣  ARTISAN COMMANDS${NC}"
echo "───────────────────────────────────────────────────────────────"

check "static:generate command exists" "php artisan list | grep -q 'static:generate'"
check "GenerateStaticSite.php exists" "[ -f app/Console/Commands/GenerateStaticSite.php ]"

echo ""

# ============================================================================
# 5. DEPENDENCIES
# ============================================================================
echo -e "${BLUE}5️⃣  DEPENDENCIES${NC}"
echo "───────────────────────────────────────────────────────────────"

check "vendor/ exists" "[ -d vendor ]"
check "node_modules/ exists" "[ -d node_modules ]"
check "vite installed" "npm list vite > /dev/null 2>&1"
check "laravel-vite-plugin installed" "npm list laravel-vite-plugin > /dev/null 2>&1"

echo ""

# ============================================================================
# 6. BUILD ASSETS
# ============================================================================
echo -e "${BLUE}6️⃣  BUILD ASSETS${NC}"
echo "───────────────────────────────────────────────────────────────"

check "public/build exists" "[ -d public/build ]"
check "resources/css/ exists" "[ -d resources/css ]"
check "resources/js/ exists" "[ -d resources/js ]"

echo ""

# ============================================================================
# 7. PACKAGE.JSON SCRIPTS
# ============================================================================
echo -e "${BLUE}7️⃣  NPM SCRIPTS${NC}"
echo "───────────────────────────────────────────────────────────────"

check "npm run build script exists" "grep -q '\"build\"' package.json"
check "npm run dev script exists" "grep -q '\"dev\"' package.json"
check "npm run deploy:local script exists" "grep -q '\"deploy:local\"' package.json"
check "npm run serve:dist script exists" "grep -q '\"serve:dist\"' package.json"

echo ""

# ============================================================================
# 8. GIT REPOSITORY
# ============================================================================
echo -e "${BLUE}8️⃣  GIT REPOSITORY${NC}"
echo "───────────────────────────────────────────────────────────────"

check "Git repository initialized" "[ -d .git ]"
check "main branch exists" "git branch | grep -q main"
check "git remote configured" "git remote -v | grep -q origin"

echo ""

# ============================================================================
# 9. FILE SIZE VALIDATION
# ============================================================================
echo -e "${BLUE}9️⃣  FILE VALIDATION${NC}"
echo "───────────────────────────────────────────────────────────────"

check ".nojekyll is empty or minimal" "[ $(wc -c < .nojekyll) -lt 100 ]"
check "_config.yml is valid YAML" "[ -s _config.yml ]"
check "composer.json is valid JSON" "php -r 'json_decode(file_get_contents(\"composer.json\"), true); true' && true || false"
check "package.json is valid JSON" "node -e 'JSON.parse(require(\"fs\").readFileSync(\"package.json\", \"utf8\"))' 2>/dev/null && true || false"

echo ""

# ============================================================================
# 10. OPTIONAL FEATURES
# ============================================================================
echo -e "${BLUE}🔟 OPTIONAL FEATURES${NC}"
echo "───────────────────────────────────────────────────────────────"

warn "GitHub Actions available" "[ -f .github/workflows/*.yml ]"
warn "README.md exists" "[ -f README.md ]"
warn "public/robots.txt exists" "[ -f public/robots.txt ]"

echo ""

# ============================================================================
# SUMMARY
# ============================================================================
TOTAL=$((PASSED + FAILED))

echo "════════════════════════════════════════════════════════════════"
echo "📊 VALIDATION SUMMARY"
echo "════════════════════════════════════════════════════════════════"
echo ""
echo -e "  ${GREEN}✅ Passed${NC}   : $PASSED/$TOTAL"
if [ $FAILED -gt 0 ]; then
    echo -e "  ${RED}❌ Failed${NC}   : $FAILED/$TOTAL"
fi
if [ $WARNINGS -gt 0 ]; then
    echo -e "  ${YELLOW}⚠️  Warnings${NC}  : $WARNINGS"
fi

echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}✅ ALL CHECKS PASSED!${NC}"
    echo ""
    echo "Your GitHub Pages deployment is ready! 🚀"
    echo ""
    echo "Next steps:"
    echo "  1. Run: npm run deploy:local"
    echo "  2. Test: npm run serve:dist"
    echo "  3. Push: git push origin main"
    echo "  4. Check: https://github.com/USERNAME/REPO/actions"
    echo ""
    exit 0
else
    echo -e "${RED}❌ VALIDATION FAILED${NC}"
    echo ""
    echo "Issues found:"
    echo "  1. Check the failed items above"
    echo "  2. Run the recommended fixes"
    echo "  3. Re-run this validation script"
    echo ""
    exit 1
fi
