@echo off
REM Script rapide pour Windows PowerShell
REM QUICK START - Exécutez juste ceci!

title Portfolio - Static Build Helper

echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║                                                        ║
echo ║    🚀 Portfolio Laravel  GitHub Pages Generator       ║
echo ║       (Quick Start - Windows)                         ║
echo ║                                                        ║
echo ╚════════════════════════════════════════════════════════╝
echo.

echo 📋 Sélectionnez une action:
echo.
echo 1) SETUP INITIAL (first time only)
echo 2) BUILD LOCAL ONLY
echo 3) BUILD COMPLET AVEC SERVEUR
echo 4) NETTOYER DIST/
echo 5) AFFICHER AIDE
echo 0) QUITTER
echo.

set /p choice="Choisissez (0-5): "

if "!choice!"=="1" (
    cls
    echo Étape 1: Installing dependencies...
    call composer install
    call npm install
    echo.
    echo Étape 2: Generate app key...
    call php artisan key:generate
    echo.
    echo Étape 3: Setup database...
    if not exist database mkdir database
    type nul > database\portfolio.sqlite
    call php artisan migrate --database=sqlite --force
    echo.
    echo Étape 4: Seed database...
    call php artisan db:seed --force
    echo.
    echo ✓ SETUP COMPLETE!
    echo.
    pause
    goto menu
)

if "!choice!"=="2" (
    cls
    echo Building Vite assets only...
    call npm run build
    echo.
    echo ✓ BUILD COMPLETE!
    echo Output: public/build/
    echo.
    pause
    goto menu
)

if "!choice!"=="3" (
    cls
    echo Full static site generation...
    call npm run build:static
    echo.
    echo ✓ GENERATION COMPLETE!
    echo Output: dist/
    echo.
    pause
    goto menu
)

if "!choice!"=="4" (
    cls
    if exist dist (
        rmdir /s /q dist
        echo ✓ dist/ folder cleaned
    ) else (
        echo dist/ folder not found
    )
    echo.
    pause
    goto menu
)

if "!choice!"=="5" (
    cls
    echo.
    echo ══════════════════════════════════════════════════════
    echo                     AIDE / HELP
    echo ══════════════════════════════════════════════════════
    echo.
    echo 📚 COMMANDES DISPONIBLES:
    echo.
    echo npm run build
    echo   ^→ Compile les assets Vite (CSS/JS)
    echo.
    echo npm run build:static
    echo   ^→ Génère le site complet en statique
    echo.
    echo php artisan serve --host=localhost --port=8000
    echo   ^→ Démarre le serveur Laravel
    echo.
    echo php artisan static:generate --output=dist
    echo   ^→ Génère les pages HTML statiques
    echo.
    echo ══════════════════════════════════════════════════════
    echo.
    echo 🔗 DOCUMENTATION:
    echo   → DEPLOY_GITHUB_PAGES.md (Guide complet)
    echo   → GITHUB_PAGES_CONFIG.md (Configuration GitHub)
    echo.
    echo 🐛 TROUBLESHOOTING:
    echo   Question: Le serveur ne démarre pas?
    echo   Réponse: Changez le port: php artisan serve --port=9000
    echo.
    echo   Question: npm run build échoue?
    echo   Réponse: Supprimez node_modules et: npm install
    echo.
    echo   Question: Composer install échoue?
    echo   Réponse: Mettez à jour Composer: composer self-update
    echo.
    echo ══════════════════════════════════════════════════════
    echo.
    pause
    goto menu
)

if "!choice!"=="0" (
    exit /b 0
)

goto menu

:menu
cls
goto start
