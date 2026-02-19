@echo off
REM ============================================================================
REM GitHub Pages Deployment Validation Script (Windows)
REM Valide que toute la configuration est correcte
REM ============================================================================

setlocal enabledelayedexpansion

set PASSED=0
set FAILED=0
set WARNINGS=0

echo.
echo =====================================================================
echo 🔍 GitHub Pages Deployment Validation (Windows)
echo =====================================================================
echo.

REM ============================================================================
REM 1. ENVIRONMENT CHECKS
REM ============================================================================
echo 1️⃣  ENVIRONMENT CHECKS
echo ───────────────────────────────────────────────────────────────

where /q git
if !errorlevel! equ 0 (
    echo ✅ Git installed
    set /a PASSED=PASSED+1
) else (
    echo ❌ Git not installed
    set /a FAILED=FAILED+1
)

where /q php
if !errorlevel! equ 0 (
    echo ✅ PHP installed
    set /a PASSED=PASSED+1
) else (
    echo ❌ PHP not installed
    set /a FAILED=FAILED+1
)

where /q node
if !errorlevel! equ 0 (
    echo ✅ Node.js installed
    set /a PASSED=PASSED+1
) else (
    echo ❌ Node.js not installed
    set /a FAILED=FAILED+1
)

where /q npm
if !errorlevel! equ 0 (
    echo ✅ npm installed
    set /a PASSED=PASSED+1
) else (
    echo ❌ npm not installed
    set /a FAILED=FAILED+1
)

where /q composer
if !errorlevel! equ 0 (
    echo ✅ Composer installed
    set /a PASSED=PASSED+1
) else (
    echo ❌ Composer not installed
    set /a FAILED=FAILED+1
)

echo.

REM ============================================================================
REM 2. PROJECT STRUCTURE
REM ============================================================================
echo 2️⃣  PROJECT STRUCTURE
echo ───────────────────────────────────────────────────────────────

if exist composer.json (
    echo ✅ composer.json exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ composer.json not found
    set /a FAILED=FAILED+1
)

if exist package.json (
    echo ✅ package.json exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ package.json not found
    set /a FAILED=FAILED+1
)

if exist app\ (
    echo ✅ app/ directory exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ app/ directory not found
    set /a FAILED=FAILED+1
)

if exist resources\ (
    echo ✅ resources/ directory exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ resources/ directory not found
    set /a FAILED=FAILED+1
)

echo.

REM ============================================================================
REM 3. GITHUB PAGES CONFIGURATION
REM ============================================================================
echo 3️⃣  GITHUB PAGES CONFIGURATION
echo ───────────────────────────────────────────────────────────────

if exist .github\workflows\deploy.yml (
    echo ✅ .github\workflows\deploy.yml exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ .github\workflows\deploy.yml not found
    set /a FAILED=FAILED+1
)

if exist .nojekyll (
    echo ✅ .nojekyll exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ .nojekyll not found
    set /a FAILED=FAILED+1
)

if exist _config.yml (
    echo ✅ _config.yml exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ _config.yml not found
    set /a FAILED=FAILED+1
)

if exist .gitignore (
    echo ✅ .gitignore exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ .gitignore not found
    set /a FAILED=FAILED+1
)

echo.

REM ============================================================================
REM 4. ARTISAN COMMANDS
REM ============================================================================
echo 4️⃣  ARTISAN COMMANDS
echo ───────────────────────────────────────────────────────────────

if exist app\Console\Commands\GenerateStaticSite.php (
    echo ✅ GenerateStaticSite.php exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ GenerateStaticSite.php not found
    set /a FAILED=FAILED+1
)

echo.

REM ============================================================================
REM 5. DEPENDENCIES
REM ============================================================================
echo 5️⃣  DEPENDENCIES
echo ───────────────────────────────────────────────────────────────

if exist vendor\ (
    echo ✅ vendor/ exists
    set /a PASSED=PASSED+1
) else (
    echo ⚠️  vendor/ not found (run: composer install)
    set /a WARNINGS=WARNINGS+1
)

if exist node_modules\ (
    echo ✅ node_modules/ exists
    set /a PASSED=PASSED+1
) else (
    echo ⚠️  node_modules/ not found (run: npm install)
    set /a WARNINGS=WARNINGS+1
)

echo.

REM ============================================================================
REM 6. BUILD ASSETS
REM ============================================================================
echo 6️⃣  BUILD ASSETS
echo ───────────────────────────────────────────────────────────────

if exist public\build\ (
    echo ✅ public/build exists
    set /a PASSED=PASSED+1
) else (
    echo ⚠️  public/build not found (run: npm run build)
    set /a WARNINGS=WARNINGS+1
)

if exist resources\css\ (
    echo ✅ resources/css/ exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ resources/css/ not found
    set /a FAILED=FAILED+1
)

echo.

REM ============================================================================
REM 7. NPM SCRIPTS
REM ============================================================================
echo 7️⃣  NPM SCRIPTS
echo ───────────────────────────────────────────────────────────────

findstr /M "\"build\"" package.json > nul
if !errorlevel! equ 0 (
    echo ✅ npm run build script exists
    set /a PASSED=PASSED+1
) else (
    echo ❌ build script not found
    set /a FAILED=FAILED+1
)

findstr /M "\"deploy:local\"" package.json > nul
if !errorlevel! equ 0 (
    echo ✅ npm run deploy:local script exists
    set /a PASSED=PASSED+1
) else (
    echo ⚠️  deploy:local script not found
    set /a WARNINGS=WARNINGS+1
)

echo.

REM ============================================================================
REM 8. GIT REPOSITORY
REM ============================================================================
echo 8️⃣  GIT REPOSITORY
echo ───────────────────────────────────────────────────────────────

if exist .git\ (
    echo ✅ Git repository initialized
    set /a PASSED=PASSED+1
) else (
    echo ❌ .git/ not found (run: git init)
    set /a FAILED=FAILED+1
)

echo.

REM ============================================================================
REM SUMMARY
REM ============================================================================
echo =====================================================================
echo 📊 VALIDATION SUMMARY
echo =====================================================================
echo.
echo ✅ Passed   : !PASSED!
if !FAILED! gtr 0 (
    echo ❌ Failed   : !FAILED!
)
if !WARNINGS! gtr 0 (
    echo ⚠️  Warnings : !WARNINGS!
)
echo.

if !FAILED! equ 0 (
    echo ✅ ALL CHECKS PASSED^^!
    echo.
    echo Your GitHub Pages deployment is ready! 🚀
    echo.
    echo Next steps:
    echo   1. Run: npm run deploy:local
    echo   2. Test: npm run serve:dist
    echo   3. Push: git push origin main
    echo.
    pause
    exit /b 0
) else (
    echo ❌ VALIDATION FAILED
    echo.
    echo Check the failed items above and fix them.
    echo.
    pause
    exit /b 1
)
