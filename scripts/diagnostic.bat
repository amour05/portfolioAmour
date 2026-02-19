@echo off
REM Diagnostic Tool - Vérifie la configuration pour GitHub Pages

title Portfolio - Diagnostic Tool

setlocal enabledelayedexpansion

cls
echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║                                                        ║
echo ║           🔍 Portfolio Diagnostic Tool                ║
echo ║                                                        ║
echo ╚════════════════════════════════════════════════════════╝
echo.

set :failed=0

echo Checking prerequisites...
echo.

REM 1. Vérifier PHP
echo [1/10] Checking PHP...
where php >nul 2>nul
if errorlevel 1 (
    echo ❌ PHP not found
    set failed=1
) else (
    for /f "tokens=*" %%i in ('php -v ^| find "PHP"') do (
        echo ✓ !
    )
)
echo.

REM 2. Vérifier Node.js
echo [2/10] Checking Node.js...
where node >nul 2>nul
if errorlevel 1 (
    echo ❌ Node.js not found
    set failed=1
) else (
    for /f "tokens=*" %%i in ('node -v') do (
        echo ✓ Node %%i
    )
)
echo.

REM 3. Vérifier npm
echo [3/10] Checking npm...
where npm >nul 2>nul
if errorlevel 1 (
    echo ❌ npm not found
    set failed=1
) else (
    for /f "tokens=*" %%i in ('npm -v') do (
        echo ✓ npm %%i
    )
)
echo.

REM 4. Vérifier Composer
echo [4/10] Checking Composer...
where composer >nul 2>nul
if errorlevel 1 (
    echo ❌ Composer not found
    set failed=1
) else (
    echo ✓ Composer found
)
echo.

REM 5. Vérifier files critiques
echo [5/10] Checking project files...
set "missing=0"

if exist "composer.json" (
    echo ✓ composer.json
) else (
    echo ❌ composer.json missing
    set missing=1
    set failed=1
)

if exist "package.json" (
    echo ✓ package.json
) else (
    echo ❌ package.json missing
    set missing=1
    set failed=1
)

if exist "routes\web.php" (
    echo ✓ routes/web.php
) else (
    echo ❌ routes/web.php missing
    set missing=1
    set failed=1
)

if exist ".github\workflows\deploy.yml" (
    echo ✓ .github/workflows/deploy.yml
) else (
    echo ❌ .github/workflows/deploy.yml missing
    set missing=1
    set failed=1
)

if exist "app\Console\Commands\GenerateStaticSite.php" (
    echo ✓ app/Console/Commands/GenerateStaticSite.php
) else (
    echo ❌ app/Console/Commands/GenerateStaticSite.php missing
    echo ^(This is the artisan command for static generation^)
    set missing=1
    set failed=1
)

echo.

REM 6. Vérifier dépendances PHP
echo [6/10] Checking PHP dependencies...
if exist "vendor\autoload.php" (
    echo ✓ vendor/ folder found
) else (
    echo ⚠ vendor/ folder not found - run: composer install
)
echo.

REM 7. Vérifier dépendances npm
echo [7/10] Checking Node dependencies...
if exist "node_modules" (
    echo ✓ node_modules folder found
) else (
    echo ⚠ node_modules folder not found - run: npm install
)
echo.

REM 8. Vérifier base de données
echo [8/10] Checking database...
if exist "database\*.sqlite" (
    echo ✓ SQLite database found
) else (
    echo ⚠ No SQLite database - run: touch database/portfolio.sqlite
)
echo.

REM 9. Vérifier .env
echo [9/10] Checking .env file...
if exist ".env" (
    echo ✓ .env found
) else (
    echo ⚠ .env not found - run: cp .env.example .env
)
echo.

REM 10. Vérifier Git
echo [10/10] Checking Git...
where git >nul 2>nul
if errorlevel 1 (
    echo ❌ Git not found
    set failed=1
) else (
    echo ✓ Git found
    REM Vérifier si c'est un repo git
    git rev-parse --git-dir >nul 2>nul
    if errorlevel 1 (
        echo ❌ Not a Git repository - run: git init
        set failed=1
    ) else (
        echo ✓ Git repository found
        for /f "tokens=*" %%i in ('git rev-parse --abbrev-ref HEAD') do (
            echo ✓ Current branch: %%i
        )
    )
)
echo.

echo ════════════════════════════════════════════════════════
echo Results Summary
echo ════════════════════════════════════════════════════════
echo.

if !failed!==0 (
    echo ✓ All checks passed!
    echo.
    echo Next steps:
    echo 1. Run: npm run build:static
    echo 2. Verify dist/ folder is created
    echo 3. Push to GitHub
    echo 4. Check Actions tab for workflow status
) else (
    echo ❌ Some checks failed!
    echo.
    echo FIXES:
    echo - Install missing software
    echo - Run: composer install
    echo - Run: npm install
    echo - Create: database/portfolio.sqlite
    echo.
)

echo ════════════════════════════════════════════════════════
echo.
pause
