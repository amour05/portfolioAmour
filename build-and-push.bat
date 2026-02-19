@echo off
REM Script simple pour générer le site statique
REM Étape 1: Lancer le serveur
REM Étape 2: Générer les pages
REM Étape 3: Arrêter le serveur

cd /d %CD%

echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║                                                        ║
echo ║  GENERATING STATIC SITE FOR GITHUB PAGES             ║
echo ║                                                        ║
echo ╚════════════════════════════════════════════════════════╝
echo.

REM Step 1: Clean old build
echo [Step 1/4] Cleaning dist folder...
if exist dist (
    rmdir /s /q dist >nul 2>nul
)
mkdir dist

REM Step 2: Build Vite
echo [Step 2/4] Building Vite assets...
call npm run build
if errorlevel 1 goto error

REM Step 3: Start Laravel server in background
echo [Step 3/4] Starting Laravel server...
start cmd /min /B /K "php artisan serve --host=localhost --port=8000"
timeout /t 3 /nobreak

REM Step 4: Generate static pages
echo [Step 4/4] Generating static pages...
call php artisan static:generate --output=dist
if errorlevel 1 goto error

REM Kill the server
echo.
echo Stopping Laravel server...
for /f "tokens=5" %%a in ('netstat -ano ^| find "8000"') do taskkill /pid %%a /f 2>nul

echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║                                                        ║
echo ║            ✅ BUILD COMPLETE!                         ║
echo ║                                                        ║
echo ║  Files generated in: dist/                            ║
echo ║                                                        ║
echo ║  Next step:                                           ║
echo ║  1. Push to GitHub: git push origin main             ║
echo ║  2. Check Actions tab                                ║
echo ║  3. Visit: https://username.github.io/repo-name/    ║
echo ║                                                        ║
echo ╚════════════════════════════════════════════════════════╝
echo.
pause
exit /b 0

:error
echo.
echo ❌ ERROR during build!
echo Check the error messages above
echo.
taskkill /f /im php.exe >nul 2>nul
pause
exit /b 1
