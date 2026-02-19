# PowerShell Script Helper for GitHub Pages Deployment
# Usage: ./scripts/helper.ps1

Write-Host ""
Write-Host "╔════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║                                                        ║" -ForegroundColor Cyan
Write-Host "║    GitHub Pages - Portfolio Deployment Helper         ║" -ForegroundColor Cyan
Write-Host "║                                                        ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

function Show-Menu {
    Write-Host ""
    Write-Host "📋 SELECT AN ACTION:" -ForegroundColor Green
    Write-Host ""
    Write-Host "📦 SETUP:"
    Write-Host "  1) Setup Everything (PHP + Node dependencies)"
    Write-Host "  2) Create SQLite Database"
    Write-Host "  3) Seed Database with Test Data"
    Write-Host ""
    Write-Host "🏗️  BUILD:"
    Write-Host "  4) Build Vite Assets Only"
    Write-Host "  5) Build Complete Static Site (MAIN)"
    Write-Host "  6) Clean dist/ Folder"
    Write-Host ""
    Write-Host "🚀 DEPLOYMENT:"
    Write-Host "  7) Show Git Status"
    Write-Host "  8) Prepare for GitHub (add all + message)"
    Write-Host "  9) Push to GitHub"
    Write-Host ""
    Write-Host "🔍 DIAGNOSTICS:"
    Write-Host " 10) Run Diagnostic Check"
    Write-Host " 11) Show README"
    Write-Host " 12) Open Documentation"
    Write-Host ""
    Write-Host " 0) EXIT"
    Write-Host ""
}

function Execute-Command {
    param(
        [string]$Command,
        [string]$Description
    )
    
    Write-Host ""
    Write-Host "▶️  Executing: $Description" -ForegroundColor Yellow
    Write-Host "   Command: $Command" -ForegroundColor Gray
    Write-Host ""
    
    Invoke-Expression $Command
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "✅ SUCCESS!" -ForegroundColor Green
    } else {
        Write-Host ""
        Write-Host "❌ ERROR! (Exit code: $LASTEXITCODE)" -ForegroundColor Red
    }
}

$running = $true

while ($running) {
    Show-Menu
    
    $choice = Read-Host "Enter your choice (0-12)"
    
    switch ($choice) {
        "1" {
            Execute-Command "composer install; npm install" "Installing dependencies"
            Execute-Command "php artisan key:generate --force" "Generating APP_KEY"
        }
        
        "2" {
            Write-Host ""
            if (-not (Test-Path "database")) {
                New-Item -ItemType Directory -Path "database" -Force | Out-Null
            }
            $dbFile = "database\portfolio.sqlite"
            if (-not (Test-Path $dbFile)) {
                New-Item -ItemType File -Path $dbFile -Force | Out-Null
                Write-Host "✅ Created: $dbFile" -ForegroundColor Green
            }
            Execute-Command "php artisan migrate --database=sqlite --force --no-interaction" "Running migrations"
        }
        
        "3" {
            Execute-Command "php artisan db:seed --force --no-interaction" "Seeding database"
        }
        
        "4" {
            Execute-Command "npm run build" "Building Vite assets"
        }
        
        "5" {
            Execute-Command "npm run build:static" "Building complete static site"
            Write-Host ""
            Write-Host "✨ Check dist/ folder for generated files!" -ForegroundColor Green
            Write-Host ""
            $confirm = Read-Host "Open dist/ folder? (y/n)"
            if ($confirm -eq "y") {
                Invoke-Item ".\dist"
            }
        }
        
        "6" {
            Write-Host ""
            if (Test-Path "dist") {
                Remove-Item "dist" -Recurse -Force
                Write-Host "✅ dist/ folder cleaned" -ForegroundColor Green
            } else {
                Write-Host "⚠️  dist/ folder not found" -ForegroundColor Yellow
            }
        }
        
        "7" {
            Execute-Command "git status" "Checking Git status"
        }
        
        "8" {
            Write-Host ""
            Write-Host "📝 Current changes:" -ForegroundColor Yellow
            git status --short
            Write-Host ""
            $files = Read-Host "Add all changes? (y/n)"
            if ($files -eq "y") {
                git add .
                Write-Host ""
                $message = Read-Host "Enter commit message (or press Enter for default)"
                if ($message -eq "") {
                    $message = "Update portfolio - Deploy to GitHub Pages"
                }
                Execute-Command "git commit -m `"$message`"" "Committing changes"
            }
        }
        
        "9" {
            Write-Host ""
            Write-Host "🚀 Pushing to GitHub..." -ForegroundColor Cyan
            $branch = Read-Host "Push to branch (default: main)"
            if ($branch -eq "") {
                $branch = "main"
            }
            Execute-Command "git push origin $branch" "Pushing to GitHub"
            Write-Host ""
            Write-Host "✨ Check GitHub Actions in 1-2 minutes!" -ForegroundColor Green
            Write-Host "   Repository → Actions → Deploy to GitHub Pages" -ForegroundColor Gray
        }
        
        "10" {
            Write-Host ""
            Write-Host "🔍 Running diagnostic..." -ForegroundColor Yellow
            Write-Host ""
            
            # Check PHP
            if (Get-Command php -ErrorAction SilentlyContinue) {
                $phpVersion = php -v | Select-Object -First 1
                Write-Host "✅ PHP: $phpVersion" -ForegroundColor Green
            } else {
                Write-Host "❌ PHP not found" -ForegroundColor Red
            }
            
            # Check Node
            if (Get-Command node -ErrorAction SilentlyContinue) {
                $nodeVersion = node -v
                Write-Host "✅ Node.js: $nodeVersion" -ForegroundColor Green
            } else {
                Write-Host "❌ Node.js not found" -ForegroundColor Red
            }
            
            # Check npm
            if (Get-Command npm -ErrorAction SilentlyContinue) {
                $npmVersion = npm -v
                Write-Host "✅ npm: $npmVersion" -ForegroundColor Green
            } else {
                Write-Host "❌ npm not found" -ForegroundColor Red
            }
            
            # Check Composer
            if (Get-Command composer -ErrorAction SilentlyContinue) {
                Write-Host "✅ Composer found" -ForegroundColor Green
            } else {
                Write-Host "❌ Composer not found" -ForegroundColor Red
            }
            
            # Check Git
            if (Get-Command git -ErrorAction SilentlyContinue) {
                Write-Host "✅ Git found" -ForegroundColor Green
            } else {
                Write-Host "❌ Git not found" -ForegroundColor Red
            }
            
            # Check required files
            Write-Host ""
            Write-Host "📁 Required files:" -ForegroundColor Yellow
            
            @(
                ".github\workflows\deploy.yml",
                "app\Console\Commands\GenerateStaticSite.php",
                "composer.json",
                "package.json"
            ) | ForEach-Object {
                if (Test-Path $_) {
                    Write-Host "✅ $_" -ForegroundColor Green
                } else {
                    Write-Host "❌ $_" -ForegroundColor Red
                }
            }
        }
        
        "11" {
            if (Test-Path "GITHUB_PAGES_README.md") {
                Get-Content "GITHUB_PAGES_README.md" | Head -50
                Write-Host ""
                Write-Host "... (see full file in GITHUB_PAGES_README.md)" -ForegroundColor Gray
            } else {
                Write-Host "❌ README not found" -ForegroundColor Red
            }
        }
        
        "12" {
            Write-Host ""
            Write-Host "📖 Documentation files:" -ForegroundColor Green
            Write-Host ""
            Write-Host "1. WELCOME.txt              - Start here! (ASCII welcome)"
            Write-Host "2. SETUP_SUMMARY.md         - Quick summary"
            Write-Host "3. DEPLOY_GITHUB_PAGES.md  - COMPLETE GUIDE (in French)"
            Write-Host "4. EXAMPLES.md              - 7 scenarios with code"
            Write-Host "5. FILE_MANIFEST.js         - What was created"
            Write-Host ""
            $choice = Read-Host "Open which file? (1-5 or q to skip)"
            
            $files = @{
                "1" = "WELCOME.txt"
                "2" = "SETUP_SUMMARY.md"
                "3" = "DEPLOY_GITHUB_PAGES.md"
                "4" = "EXAMPLES.md"
                "5" = "FILE_MANIFEST.js"
            }
            
            if ($files.$choice) {
                Invoke-Item $files.$choice
            }
        }
        
        "0" {
            Write-Host ""
            Write-Host "👋 Goodbye! Good luck with your deployment! 🚀" -ForegroundColor Green
            Write-Host ""
            $running = $false
        }
        
        default {
            Write-Host ""
            Write-Host "❌ Invalid choice. Please try again." -ForegroundColor Red
        }
    }
    
    if ($running) {
        Write-Host ""
        $pause = Read-Host "Press Enter to continue..."
        Clear-Host
    }
}
