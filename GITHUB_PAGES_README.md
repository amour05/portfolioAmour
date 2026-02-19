# 📖 README - Déploiement GitHub Pages

> Portfolio Laravel généré automatiquement et déployé en statique sur GitHub Pages

## 🎯 Objectif

Ce projet utilise **GitHub Actions** pour convertir votre portfolio Laravel en **site statique** et le déployer sur **GitHub Pages** lors de chaque push.

## ✨ Caractéristiques

- ✅ **Automatisation complète** - Déploiement en un seul push
- ✅ **Vite + Tailwind** - Assets CSS/JS compilés
- ✅ **Pages dynamiques** - Blog avec articles générés
- ✅ **Zéro configuration** - Prêt à l'emploi
- ✅ **Compatible GitHub Pages** - HTML/CSS/JS pur
- ✅ **Laravel 12 + PHP 8.2** - Technos dernière génération

## 🚀 Démarrage rapide

### Sur Windows (Rapide) 

```bash
cd scripts
quick-start.bat
```

Sélectionnez le menu pour:
1. Setup initial
2. Build local
3. Générer le site complet

### Commandes manuelles

```bash
# 1️⃣ Installation (une seule fois)
composer install
npm install

# 2️⃣ Build complèt local
npm run build:static

# OU seulement les assets
npm run build

# 3️⃣ Générer les pages (serveur doit tourner)
php artisan serve --host=localhost --port=8000
# En autre terminal:
php artisan static:generate --output=dist
```

---

## 📂 Structure des fichiers ajoutés

```
.github/
└── workflows/
    └── deploy.yml                          # ⭐ Workflow GitHub Actions

app/Console/Commands/
└── GenerateStaticSite.php                  # ⭐ Commande Artisan

config/
└── static.js                               # Configuration statique

scripts/
├── static-generate.js                      # Script npm principal
├── static-build.bat                        # Script Windows batch
├── static-build.sh                         # Script Bash (macOS/Linux)
└── quick-start.bat                         # Menu rapide Windows

DEPLOY_GITHUB_PAGES.md                      # 📖 Guide complet (français)
GITHUB_PAGES_CONFIG.md                      # Configuration GitHub Pages
```

---

## 🔧 Configuration locale

### .env obligatoires

```env
APP_NAME="Portfolio"
APP_ENV=local
APP_KEY=base64:...          # Généré avec: php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite        # Important pour la génération

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Database

La génération statique utilise **SQLite** (pas MySQL/PostgreSQL):

```bash
# Créer la DB
touch database/portfolio.sqlite

# Migrations
php artisan migrate --database=sqlite

# Seed (optionnel)
php artisan db:seed --database=sqlite
```

---

## 🌐 GitHub Pages Configuration

### 1. Settings du Repository

**Settings** → **Pages**:
- ✅ Source: `Deploy from a branch`
- ✅ Branch: `gh-pages`
- ✅ Folder: `/` (root)

Le workflow crée automatiquement la branche `gh-pages`!

### 2. URL de votre site

```
https://<username>.github.io/<repository-name>/
```

Exemple:
```
https://john-doe.github.io/portfolio/
```

### 3. (Optionnel) Domaine personnalisé

1. Achetez un domaine (Namecheap, GoDaddy, etc)
2. Configurez un enregistrement DNS `CNAME`:
   ```
   CNAME: john-doe.github.io
   ```
3. Dans GitHub Pages settings, entrez votre domaine
4. Le workflow crée automatiquement le fichier `CNAME` dans `dist/`

---

## 📊 Workflow GitHub Actions

### Déclenche automatiquement quand:

- ✅ Push sur `main` ou `master`
- ✅ Pull Request
- ✅ Workflow dispatch (manuel)

### Étapes du workflow:

```
1. Checkout code
2. Setup PHP 8.2
3. Cache Composer dependencies
4. Install PHP dependencies
5. Setup Node.js 20
6. Install NPM dependencies
7. Create .env
8. Build Vite assets
9. Setup SQLite database
10. Generate static site
11. Create GitHub Pages config
12. Deploy to GitHub Pages
```

### Voir les logs

1. GitHub repo → **Actions** tab
2. Cliquez sur le workflow "Deploy to GitHub Pages"
3. Voir les détails et les erreurs éventuelles

---

## 📝 Ajouter des pages

### Nouvelle page publique

1. Créer une vue Blade: `resources/views/ma-page.blade.php`
2. Ajouter la route: `routes/web.php`
   ```php
   Route::get('/ma-page', fn() => view('ma-page'))->name('ma-page');
   ```
3. Éditer [app/Console/Commands/GenerateStaticSite.php](app/Console/Commands/GenerateStaticSite.php):
   ```php
   $publicRoutes = [
       '/',
       '/projects',
       '/ma-page',  // ← Ajouter
   ];
   ```
4. Commit & push pour déclencher le déploiement!

### Articles de blog

Les articles blog sont générés automatiquement! 

Pour chaque article avec `is_published = true`:
- `/blog/{slug}` → `dist/blog/{slug}/index.html`

---

## ⚙️ Scripts npm

```bash
npm run dev              # Dev local Vite
npm run build            # Build Vite assets uniquement
npm run build:static     # Build complet (assets + statique)
npm run build:gh-pages   # Alias pour build:static
```

---

## 🐛 Troubleshooting

| Problème | Solution |
|----------|----------|
| **Workflow échoue** | Vérifier Actions tab pour les logs d'erreur |
| **Site ne s'affiche pas** | Attendre 2-3 minutes, vérifier GitHub Pages settings |
| **Assets CSS/JS manquants** | Vérifier que `public/build/` existe après le build |
| **Images manquantes** | Le script copie `public/images/` automatiquement |
| **Base URL incorrecte** | Modifier `config/static.js` pour domaine personnalisé |

---

## 📚 Documentation complète

📖 **[DEPLOY_GITHUB_PAGES.md](DEPLOY_GITHUB_PAGES.md)** - Guide détaillé complet (français) ← **LISEZ CECI!**

---

## 🔐 Variables d'environnement GitHub

Pour utiliser des APIs externes (Cloudinary, etc):

1. **Settings** → **Secrets and variables** → **Actions**
2. **New repository secret**:
   ```
   Name: CLOUDINARY_CLOUD_NAME
   Value: your-value
   ```
3. Dans le workflow `.github/workflows/deploy.yml`, utiliser:
   ```yaml
   env:
     CLOUDINARY_CLOUD_NAME: ${{ secrets.CLOUDINARY_CLOUD_NAME }}
   ```

---

## 💾 Déploiement manuel vers dist/

Si vous voulez générer sans le workflow:

```bash
# 1. Build assets
npm run build

# 2. Démarrer serveur
php artisan serve &

# 3. Générer pages
php artisan static:generate --output=dist

# 4. Renommez dist/ en gh-pages (optionnel)
# ou poussez directement:
git add dist/
git commit -m "Generate static site"
git push
```

---

## ✅ Checklist avant le premier déploiement

- [ ] `.github/workflows/deploy.yml` présent
- [ ] `composer install` exécuté
- [ ] `npm install` exécuté  
- [ ] `.env` configuré
- [ ] Repository pushé sur GitHub
- [ ] GitHub Pages enabled dans Settings
- [ ] Branche par défaut est `main` ou `master`
- [ ] Aucun error dans `composer.json` ou `package.json`

---

## 🎉 Prêt!

Après la configuration:

```bash
git add .
git commit -m "Setup GitHub Pages deployment"
git push origin main
```

Le workflow démarre automatiquement! 🚀

Visitez votre portfolio quelques minutes après:
```
https://<username>.github.io/<repository>/
```

---

## 📞 Support

- **Erreurs du workflow**: Consultez la section Actions
- **Questions Laravel**: https://laravel.com/docs/12
- **Questions GitHub Pages**: https://docs.github.com/en/pages
- **Faire du debug local**: Voir [DEPLOY_GITHUB_PAGES.md](DEPLOY_GITHUB_PAGES.md)

---

**Bonne chance! 🚀**
