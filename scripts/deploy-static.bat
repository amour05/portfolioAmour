@echo off
REM ============================================================================
REM Deploy Static Site to GitHub Pages (Main Branch)
REM Script pour générer et déployer le site statique sur GitHub Pages
REM ============================================================================

setlocal enabledelayedexpansion

set OUTPUT_DIR=%1
if "!OUTPUT_DIR!"=="" set OUTPUT_DIR=dist

set GIT_BRANCH=%2
if "!GIT_BRANCH!"=="" set GIT_BRANCH=main

echo.
echo =====================================================================
echo 🚀 Static Site Deployment Script (Windows)
echo =====================================================================
echo.
echo 📝 Configuration:
echo   Output directory: !OUTPUT_DIR!
echo   Target branch: !GIT_BRANCH!
echo.

REM Check if git is available
where /q git
if errorlevel 1 (
    echo ❌ Error: Git not found in PATH!
    exit /b 1
)

REM Step 1: Install dependencies
echo 📦 Step 1: Installing dependencies...
call npm install
if errorlevel 1 (
    echo ❌ Failed to install dependencies
    exit /b 1
)
echo ✅ Dependencies installed
echo.

REM Step 2: Build assets
echo 📦 Step 2: Building assets with Vite...
call npm run build
if errorlevel 1 (
    echo ❌ Failed to build assets
    exit /b 1
)
echo ✅ Assets built successfully
echo.

REM Step 3: Generate static site
echo 📦 Step 3: Generating static site...
php artisan static:generate --output=!OUTPUT_DIR!
if errorlevel 1 (
    echo ❌ Failed to generate static site
    exit /b 1
)
echo ✅ Static site generated successfully
echo.

REM Step 4: Verify index.html
echo 📦 Step 4: Verifying build...
if not exist "!OUTPUT_DIR!\index.html" (
    echo ❌ Error: !OUTPUT_DIR!\index.html not found!
    exit /b 1
)
echo ✅ Verified: !OUTPUT_DIR!\index.html exists
dir /h "!OUTPUT_DIR!\index.html"
echo.

REM Step 5: Show summary
echo 📊 Build Summary:
echo   Output directory size: 
for /f "tokens=*" %%A in ('dir /s /b "!OUTPUT_DIR!" ^| find /c ":"') do echo   Total items: %%A
echo.

echo =====================================================================
echo ✅ Static site is ready for deployment!
echo =====================================================================
echo.
echo Next steps:
echo   1. Review the generated files in: !OUTPUT_DIR!\
echo   2. Test locally: npx http-server !OUTPUT_DIR!
echo   3. Push to GitHub: git add . ^&^& git commit -m "Deploy static site" ^&^& git push
echo.

pause
