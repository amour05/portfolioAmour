# 📦 Deployment Complet: Laravel Portfolio → GitHub Pages

## ✅ Statut Actuel

Your project already has a **complete static site generation setup**:

- ✅ `app/Console/Commands/GenerateStaticSite.php` - Commande Artisan pour générer les pages statiques
- ✅ `scripts/static-generate.js` - Orchestrateur npm
- ✅ `npm run build:static` - Script npm pour lancer la génération
- ✅ `.github/workflows/deploy.yml` - Workflow GitHub Actions optimisé avec:
  - Health check du serveur Laravel
  - Vérification de `dist/index.html`
  - Déploiement avec `peaceiris/actions-gh-pages@v3` (évite la dépréciée `upload-artifact@v3`)
  - Logs améliorés pour le diagnostic

---

## 🚀 Comment ça marche

### Flux de déploiement

```
1. Push sur main/master
   ↓
2. GitHub Actions lance le workflow deploy.yml
   ├─ Checkout code
   ├─ Setup PHP 8.2 & Node 20
   ├─ installer Composer & npm
   ├─ Créer .env & générer la clé Laravel
   ├─ Migrer la base de donnée SQLite
   ├─ Build des assets Vite
   ├─ Lancer le serveur Laravel en background
   ├─ Health check (curl) - attendre que le serveur soit prêt
   ├─ npm run build:static (génère dist/)
   │  ├─ php artisan static:generate --output=dist
   │  └─ Crée dist/index.html & autres pages
   ├─ Vérifier dist/index.html existe ✅
   ├─ Créer .gitignore dans dist/
   └─ Déployer avec peaceiris/actions-gh-pages@v3
     └─ Pousse dist/ sur la branche gh-pages
   ↓
3. GitHub Pages publiera depuis gh-pages
   ↓
4. Site visible à: https://amour05.github.io/portfolioAmour/
```

---

## 📋 Checklist de Configuration Locale

Avant de pousser, vérifiez localement:

### 1. Créer le fichier `.env`
```bash
cp .env.example .env
php artisan key:generate
```

### 2. Créer et migrer la base de données SQLite
```bash
touch database/portfolio.sqlite
php artisan migrate
php artisan db:seed --force
```

### 3. Builder les assets
```bash
npm install
npm run build
```

### 4. Démarrer le serveur Laravel (terminal 1)
```bash
php artisan serve --host=localhost --port=8000
```

### 5. Générer les pages statiques (terminal 2)
```bash
npm run build:static
```

### 6. Vérifier que dist/index.html existe
```bash
ls -la dist/
cat dist/index.html | head -50
```

Si la génération fonctionne localement, elle fonctionnera aussi dans GitHub Actions!

---

## 🔍 Fichiers Clés

### `app/Console/Commands/GenerateStaticSite.php`
- Génère un `index.html` pour chaque route publique
- Routes par défaut: `/`, `/projects`, `/about`, `/skills`, `/contact`, `/blog`
- Copie les assets compilés (build/, images/, robots.txt)
- Crée `.nojekyll` et `_config.yml` pour GitHub Pages

**Routes publiques à générer** (ligne ~31):
```php
$publicRoutes = [
    '/',
    '/projects',
    '/about',
    '/skills',
    '/contact',
    '/blog',
];
```

> **À personnaliser** selon votre application!

### `scripts/static-generate.js`
- Étape 1: Nettoyer `dist/` ancienne
- Étape 2: Builder avec Vite (`npm run build`)
- Étape 3: Lancer `php artisan static:generate --output=dist`
- Étape 4: Créer configuration GitHub Pages

### `config/static.js`
- Configuration centralisée des routes à générer
- Configuration GitHub Pages (base URL, etc.)

### `.github/workflows/deploy.yml`
- **Améliorations du workflow**:
  - ✅ Attente du serveur Laravel avec health check (curl)
  - ✅ Vérification que `dist/index.html` existe
  - ✅ Logs détaillés pour le diagnostic
  - ✅ Utilise `peaceiris/actions-gh-pages@v3` (stable)
  - ✅ Crée automatiquement la branche `gh-pages`

---

## ⚙️ Configuration GitHub Pages

### Dans votre dépôt GitHub

1. Allez à **Settings → Pages**
2. **Source**: Sélectionnez `Deploy from a branch`
3. **Branch**: Choisissez `gh-pages` et `/ (root)`
4. **Cliquez Save**

Le site sera publié à: `https://amour05.github.io/portfolioAmour/`

> La branche `gh-pages` sera créée **automatiquement** par le workflow lors du premier déploiement réussi!

---

## 🐛 Dépannage

### Problème: `Static generation failed!`

**Causes possibles:**
1. Le serveur Laravel n'a pas démarré à temps
2. Une route n'existe pas dans votre application  
3. La base de données n'a pas été migrée

**Solutions:**
- Vérifiez les logs du workflow dans l'onglet **Actions**
- Testez localement: lancez le serveur et exécutez `npm run build:static`
- Vérifiez que les vues Blade existent pour les routes listées

### Problème: `curl: (7) Failed to connect`

Le serveur Laravel n'a pas démarré à temps.

**Solution:**
```php
// Dans .github/workflows/deploy.yml, augmentez le timeout:
// Actuellement: 30 secondes
# Wait for server to start with health check (max 60 seconds)
for i in {1..60}; do
    ...
```

### Problème: `dist/index.html not found`

La commande `php artisan static:generate` n'a pas généré les pages.

**Solution:**
1. Vérifiez que les routes publiques existent dans votre application
2. Modifiez `app/Console/Commands/GenerateStaticSite.php` pour ajouter vos routes
3. Testez localement: `php artisan static:generate --output=dist-test`

### Problème: Erreur 404 sur GitHub Pages

**Cause**: `dist/index.html` n'existe pas à la racine.

**Vérification:**
```bash
# Sur GitHub Pages (via Actions logs)
ls -la dist/ | grep index.html
```

**Solution:**
- Assurez-vous que votre route `/` existe dans `routes/web.php`
- Vérifiez que la vue correspondante (`resources/views/...`) existe

---

## 📝 Prochaines Étapes

### 1. Vérifier que sites statiques (localement)
```bash
php artisan serve &
npm run build:static
ls dist/index.html  # ← doit exister!
```

### 2. Pousser les changements
```bash
git add .github/workflows/deploy.yml
git commit -m "Improve: GitHub Pages workflow with health checks"
git push origin main
```

### 3. Monitorer le premier déploiement
- Actions → build-and-deploy
- Vérifiez les logs étape par étape
- Consultez Settings → Pages une fois réussi

### 4. Vérifier la branche gh-pages
```bash
git branch -r  # Doit afficher "origin/gh-pages" après un déploiement réussi
```

---

## 🎯 Résumé Rapide

| Étape | Comando |
|-------|---------|
| Setup local | `php artisan serve` + `npm run build:static` |
| Vérifier | `ls dist/index.html` |
| Pousser | `git push origin main` |
| Déployer | Actions déclenche auto |
| Vérifier site | `https://amour05.github.io/portfolioAmour/` |
| Logs | Actions → build-and-deploy → logs |
| Branche | GitHub créé auto `gh-pages` |

---

## 📚 Ressources

- [Laravel Static Site Generation](https://laravel.com/docs)
- [peaceiris/actions-gh-pages](https://github.com/peaceiris/actions-gh-pages)
- [GitHub Pages Documentation](https://docs.github.com/en/pages)
- Appelle les routes `localhost:8000/` pour tester

---

## ✨ Bonus: Routes Personnalisées

Pour ajouter une route personnalisée à la génération statique:

**`app/Console/Commands/GenerateStaticSite.php`** (ligne ~31):
```php
$publicRoutes = [
    '/',
    '/projects',
    '/about',
    '/skills',
    '/contact',
    '/blog',
    '/portfolio',  // ← Ajoutez ici
    '/services',   // ← Et ici
];
```

Une fois modifié, le workflow générera automatiquement `dist/portfolio/index.html` et `dist/services/index.html`!

---

Generated: 2026-02-19
Status: ✅ Production Ready
