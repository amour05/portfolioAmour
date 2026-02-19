@echo off
REM Script de génération statique pour Windows
REM Usage: static-build.bat [MODE]
REM MODE: local (default) ou prod

setlocal enabledelayedexpansion
setlocal enableextensions

set MODE=%1
if "!MODE!"=="" set MODE=local

set OUTPUT_DIR=dist
set LARAVEL_PORT=8000
set LARAVEL_HOST=localhost

echo.
echo ========================================
echo   Static Site Generator (GitHub Pages)
echo ========================================
echo.
echo Mode: !MODE!
echo Output: !OUTPUT_DIR!
echo.

REM Vérifier que les prérequis sont installés
echo Verifying prerequisites...
where php >nul 2>nul
if errorlevel 1 (
    echo ERROR: PHP not found. Please install PHP 8.2+
    exit /b 1
)

where npm >nul 2>nul
if errorlevel 1 (
    echo ERROR: Node.js/npm not found. Please install Node.js
    exit /b 1
)

where composer >nul 2>nul
if errorlevel 1 (
    echo ERROR: Composer not found. Please install Composer
    exit /b 1
)

echo OK - All prerequisites found
echo.

REM Étape 1: Nettoyer le répertoire de sortie
echo [1/8] Cleaning output directory...
if exist !OUTPUT_DIR! (
    rmdir /s /q !OUTPUT_DIR! >nul 2>nul
)
mkdir !OUTPUT_DIR! >nul 2>nul
echo ^OK
echo.

REM Étape 2: Installer les dépendances
if "!MODE!"=="prod" (
    echo [2/8] Installing PHP dependencies (production)...
    call composer install --no-dev --optimize-autoloader
    if errorlevel 1 (
        echo ERROR: Composer install failed
        exit /b 1
    )
) else (
    echo [2/8] Installing PHP dependencies...
    call composer install
    if errorlevel 1 (
        echo ERROR: Composer install failed
        exit /b 1
    )
)
echo OK
echo.

echo [3/8] Installing Node dependencies...
call npm install
if errorlevel 1 (
    echo ERROR: npm install failed
    exit /b 1
)
echo OK
echo.

REM Étape 3: Builder les assets
echo [4/8] Building Vite assets...
call npm run build
if errorlevel 1 (
    echo ERROR: Vite build failed
    exit /b 1
)
echo OK
echo.

REM Étape 4: Préparer Laravel
echo [5/8] Preparing Laravel environment...
if not exist .env (
    copy .env.example .env >nul 2>nul
    echo Created .env file - configure manually if needed
)
call php artisan key:generate --force >nul 2>nul
echo OK
echo.

REM Étape 5: Setup database
echo [6/8] Setting up SQLite database...
if not exist database (
    mkdir database
)
type nul > database\portfolio.sqlite
call php artisan migrate --database=sqlite --force --no-interaction
if errorlevel 1 (
    echo ERROR: Database migration failed
    exit /b 1
)
echo OK
echo.

REM Étape 6: Seed database
echo [7/8] Seeding database...
call php artisan db:seed --force --no-interaction >nul 2>nul
REM Ignorer les erreurs de seeders
echo OK
echo.

REM Étape 7: Générer les pages statiques
echo [8/8] Generating static pages...
REM Démarrer le serveur en arrière-plan
echo Starting Laravel server...
start cmd /min php artisan serve --host=!LARAVEL_HOST! --port=!LARAVEL_PORT!
timeout /t 3 /nobreak

REM Générer le site
call php artisan static:generate --output=!OUTPUT_DIR!
if errorlevel 1 (
    echo ERROR: Static generation failed
    echo Killing Laravel server...
    taskkill /f /im php.exe >nul 2>nul
    exit /b 1
)

echo OK
echo.

REM Tuer le serveur Laravel
echo Stopping Laravel server...
taskkill /f /im php.exe >nul 2>nul
timeout /t 1 /nobreak

echo.
echo ========================================
echo. 
echo ^✓ Build complete!
echo.
echo Output directory: !OUTPUT_DIR!\
echo Files generated: 
for /f %%a in ('dir /b /s !OUTPUT_DIR! 2^>nul ^| find /c /v ""') do set count=%%a
echo !count! files
echo.
echo ^✓ Ready to deploy to GitHub Pages!
echo.
echo Next steps:
echo 1. Commit and push your changes to GitHub
echo 2. Watch the workflow in Actions tab
echo 3. Visit: https://^<username^>.github.io/^<repo-name^>/
echo.
echo ========================================

exit /b 0
