@echo off
REM 🧪 Script de test local pour la génération statique (Windows)
REM Usage: scripts\test-static-build.bat

setlocal enabledelayedexpansion
cd /d "%~dp0\.."

echo 🧪 Testing Local Static Build...
echo ================================
echo.

REM 1. Check .env
echo 1️⃣ Checking .env file...
if exist .env (
    echo [32m✅ .env exists[0m
) else (
    echo [33m⚠️ .env not found, creating from .env.example[0m
    copy .env.example .env
    php artisan key:generate --no-interaction
)

REM 2. Check database
echo.
echo 2️⃣ Checking database...
if exist database\portfolio.sqlite (
    echo [32m✅ SQLite database exists[0m
) else (
    echo [33m⚠️ Creating SQLite database[0m
    type nul > database\portfolio.sqlite
)

REM 3. Run migrations
echo.
echo 3️⃣ Running migrations...
php artisan migrate --database=sqlite --force --no-interaction

REM 4. Seed database
echo.
echo 4️⃣ Seeding database ^(optional^)...
php artisan db:seed --force --no-interaction 2>nul || echo [33m⚠️ Seeding skipped[0m

REM 5. Check npm dependencies
echo.
echo 5️⃣ Checking npm dependencies...
if not exist node_modules (
    echo [33mInstalling npm dependencies...[0m
    call npm install
)

REM 6. Build Vite
echo.
echo 6️⃣ Building Vite assets...
call npm run build

REM 7. Kill existing Laravel servers
echo.
echo 7️⃣ Cleaning up old processes...
for /f "tokens=5" %%a in ('netstat -ano ^| find ":8000"') do (
    taskkill /pid %%a /f 2>nul
)
timeout /t 1 /nobreak > nul

REM 8. Start Laravel server
echo.
echo 8️⃣ Starting Laravel development server...
start "Laravel Server" php artisan serve --host=localhost --port=8000
timeout /t 3 /nobreak > nul

REM 9. Check if server is ready
echo.
echo 9️⃣ Waiting for server to be ready...
set "attempts=0"
:wait_loop
set /a attempts+=1
if %attempts% gtr 30 (
    echo [31m❌ Server failed to start[0m
    goto error
)
timeout /t 1 /nobreak > nul
curl -f http://localhost:8000/ >nul 2>&1
if errorlevel 1 (
    echo Attempt !attempts!/30...
    goto wait_loop
)
echo [32m✅ Server is ready![0m

REM 10. Generate static files
echo.
echo 1️⃣0️⃣ Generating static site...
call npm run build:static
if errorlevel 1 (
    echo [31m❌ Static generation failed![0m
    goto error
)
echo [32m✅ Static generation succeeded![0m

REM 11. Verify dist/index.html
echo.
echo 1️⃣1️⃣ Verifying dist/index.html...
if exist dist\index.html (
    echo [32m✅ dist/index.html found![0m
    echo.
    echo 📄 First 500 characters:
    for /f "delims=" %%a in ('type dist\index.html') do (
        set "line=%%a"
        setlocal enabledelayedexpansion
        if defined output (
            set "output=!output!!line!"
        ) else (
            set "output=!line!"
        )
        endlocal & set "output=%output%"
    )
    echo %output:~0,500%
) else (
    echo [31m❌ dist/index.html NOT FOUND![0m
    echo.
    echo 📁 dist/ contents:
    if exist dist (
        dir dist /s
    ) else (
        echo dist/ directory doesn't exist!
    )
    goto error
)

REM 12. List files
echo.
echo 1️⃣2️⃣ Checking static files...
echo.
echo 📁 dist/ directory structure:
dir dist /s /b | findstr /c:"dist\" | more +20

echo.
echo 📊 File count:
for /f "tokens=*" %%a in ('dir dist /s /b ^| find /v "" ^| find /c /v ""') do (
    echo Total files: %%a
)

echo.
echo ================================
echo [32m✅ Static build test complete![0m
echo ================================
echo.
echo Next steps:
echo   1. Commit your changes:
echo      git add -A ^&^& git commit -m "Static build test passed"
echo   2. Push to GitHub:
echo      git push origin main
echo   3. Monitor the build:
echo      GitHub ^-> Actions ^-> build-and-deploy
echo.
pause
exit /b 0

:error
echo.
echo [31m❌ Test failed![0m
taskkill /f /im php.exe 2>nul
exit /b 1
