# 🚀 QUICK START - 5 minutes to deploy!

## STEP 1: Setup (2 min)

On Windows, ouvrez PowerShell ou CMD dans le dossier du projet:

```bash
# Option A: Using PowerShell (Interactive)
.\scripts\helper.ps1
# Select "1) Setup Everything"

# Option B: Batch script (Interactive Menu)
cd scripts
quick-start.bat
# Select "1) SETUP INITIAL"

# Option C: Manual commands
composer install
npm install
php artisan key:generate

# Create database
mkdir database
type nul > database\portfolio.sqlite
php artisan migrate --database=sqlite --force
php artisan db:seed --force
```

## STEP 2: Build locally (2 min)

```bash
# Full build including static generation
npm run build:static

# This creates dist/ folder with:
# ✓ HTML pages
# ✓ CSS/JS assets  
# ✓ Images
```

Verify `dist/` folder was created with files! 

## STEP 3: Push to GitHub (1 min)

```bash
git add .
git commit -m "Setup GitHub Pages deployment"
git push origin main
```

## STEP 4: Enable GitHub Pages

1. Go to GitHub repo
2. Settings → Pages
3. Select branch: `gh-pages`
4. Select folder: `/`
5. Save

## STEP 5: Wait & Visit

⏳ Wait 2-3 minutes for workflow to complete

Then visit:
```
https://USERNAME.github.io/REPO-NAME/
```

🎉 DONE!

---

## 🆘 Something wrong?

```bash
# Check if everything is properly set up
.\scripts\diagnostic.bat

# Or use interactive helper
.\scripts\helper.ps1
```

## 📖 Need more info?

- **Complete Guide**: Read `DEPLOY_GITHUB_PAGES.md` (français)
- **Examples**: See `EXAMPLES.md` for 7 scenarios
- **Troubleshooting**: Check `DEPLOY_GITHUB_PAGES.md` section "Troubleshooting"

---

**Questions?** Check the documentation in French: `DEPLOY_GITHUB_PAGES.md`
