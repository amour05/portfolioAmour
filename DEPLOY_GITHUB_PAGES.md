# 🚀 Guide Complet: Déployer Portfolio Laravel sur GitHub Pages

## 📋 Vue d'ensemble

Ce guide vous montre comment déployer automatiquement votre portfolio Laravel en tant que **site statique** sur GitHub Pages.

### Pourquoi statique ?
- ✅ GitHub Pages supporte uniquement le contenu statique (HTML/CSS/JS)
- ✅ Génération automatisée avec Laravel et Vite
- ✅ Déploiement continu avec GitHub Actions
- ✅ Pas d'infrastructure serveur requise

---

## 🛠️ Prérequis

- PHP 8.2+
- Node.js 20+
- Composer
- Git
- Compte GitHub et repository

---

## 📦 Étape 1: Installation des dépendances

### 1. D'abord, mettez à jour les dépendances PHP:

```bash
composer install
```

Cela va installer `guzzlehttp/guzzle` et `symfony/dom-crawler` nécessaires pour la génération statique.

### 2. Ensuite, les dépendances Node:

```bash
npm install
```

---

## ⚙️ Étape 2: Configuration locale

### Créer un fichier `.env.local` (optionnel):

```bash
cp .env.example .env
php artisan key:generate
```

### Créer une base de données SQLite:

Pour la génération statique locale, on utilise SQLite:

```bash
touch database/portfolio.sqlite
php artisan migrate --database=sqlite
php artisan db:seed --database=sqlite
```

---

## 🏗️ Étape 3: Générer le site statique en local

### Option A: Utiliser le script npm (recommandé)

```bash
npm run build:static
```

Cela va:
1. ✅ Construire les assets Vite
2. ✅ Lancer le serveur Laravel
3. ✅ Générer les pages HTML statiques
4. ✅ Créer le dossier `dist/` prêt à déployer

### Option B: Utiliser le script Bash

```bash
chmod +x scripts/static-build.sh
./scripts/static-build.sh prod
```

### Option C: Commandes manuelles

```bash
# 1. Builder les assets
npm run build

# 2. Démarrer le serveur Laravel
php artisan serve --host=localhost --port=8000

# 3. En autre terminal, générer les pages
php artisan static:generate --output=dist
```

---

## 📂 Comprendre la structure générée

Après la génération, votre dossier `dist/` contiendra:

```
dist/
├── index.html              # Page d'accueil (/)
├── projects/
│   └── index.html          # /projects
├── about/
│   └── index.html          # /about
├── skills/
│   └── index.html          # /skills
├── contact/
│   └── index.html          # /contact
├── blog/
│   ├── index.html          # /blog
│   ├── mon-premier-article/
│   │   └── index.html      # Chaque article blog
│   └── autre-article/
│       └── index.html
├── build/
│   ├── manifest.json
│   └── assets/             # CSS/JS compilés
├── images/                 # Images copiées
├── robots.txt
├── .nojekyll               # Désactiver Jekyll
└── _config.yml             # Config GitHub Pages
```

---

## 🔧 Configuration GitHub Pages

### 1. Configuration du repository

1. Allez dans **Repository Settings** → **Pages**
2. Sous "Build and deployment":
   - **Source**: Deploy from a branch
   - **Branch**: `gh-pages`
   - **Folder**: `/` (root)
3. Cliquez **Save**

### 2. (Optionnel) Avec un domaine personnalisé

Si vous avez un domaine (ex: `portfolio.com`):

1. Ajouter un enregistrement DNS `CNAME` pointant vers `username.github.io`
2. Dans GitHub Pages Settings, entrer le domaine
3. Dans le dossier `dist/`, créer un fichier `CNAME`:

```
portfolio.com
```

Le workflow GitHub Actions crée automatiquement ce fichier si nécessaire.

---

## 🚀 Déploiement automatique

### Comment ça marche

Le fichier `.github/workflows/deploy.yml` exécute automatiquement:

1. À chaque `push` sur `main` ou `master`
2. Installation des dépendances
3. Build des assets Vite
4. Génération des pages statiques
5. Déploiement vers la branche `gh-pages`

### Vérifier le déploiement

1. Allez dans votre repository
2. Cliquez sur l'onglet **Actions**
3. Cherchez le workflow "Deploy to GitHub Pages"
4. Cliquez dessus pour voir les détails

### Vérifier que tout fonctionne

Après ~2-3 minutes, visitez:
```
https://<username>.github.io/<repository-name>
```

---

## 📝 Routes publiques vs privées

### Routes qui seront générées en statique:
```
GET /              → index.html
GET /projects      → projects/index.html
GET /about         → about/index.html
GET /skills        → skills/index.html
GET /contact       → contact/index.html
GET /blog          → blog/index.html
GET /blog/{slug}   → blog/<slug>/index.html
```

### Routes NON générées (nécessitent une BD):
```
GET /dashboard     (nécessite auth)
GET /profile       (nécessite auth)
GET /admin/*       (nécessite admin)
GET /api/*         (API)
GET /_debug/*      (debug)
GET /cv            (PDF)
```

---

## 🎨 Customisation

### 1. Ajouter de nouvelles routes publiques

Éditez [app/Console/Commands/GenerateStaticSite.php](app/Console/Commands/GenerateStaticSite.php):

```php
$publicRoutes = [
    '/',
    '/projects',
    '/about',
    '/skills',
    '/contact',
    '/blog',
    '/new-route',  // Ajouter ici
];
```

### 2. Modifier la base URL

Pour un domaine personnalisé, éditez [config/static.js](config/static.js):

```javascript
github: {
  baseUrl: 'https://portfolio.com',
}
```

### 3. Copier des assets additionnels

Éditez la méthode `copyPublicAssets()` dans la commande Artisan.

---

## 🐛 Troubleshooting

### Le workflow GitHub Actions échoue?

1. **Vérifier les logs**: Actions tab → cliquer sur le workflow échoué
2. **Erreur "Cannot reach localhost:8000"**: Augmentez le timeout dans le workflow
3. **Base de données vide**: Assurez-vous que les seeders fonctionnent

### Le site n'affiche pas les bons styles?

1. **Vérifier les assets**: Visitez `https://your-site/build/assets/`
2. **Vérifier la base URL**: Les URLs relatifs doivent commencer par `/`
3. **Vérifier `.nojekyll`**: Ce fichier doit être présent dans `dist/`

### Les URLs sont incorrectes en production?

**Solution 1**: Utiliser des URLs relatives dans les vues Blade:
```blade
<a href="/projects">Projects</a>   <!-- ✅ Correct -->
<a href="projects">Projects</a>    <!-- ❌ Incorrect -->
```

**Solution 2**: Si vous avez un chemin de base (ex: `/my-portfolio`), mettez à jour la base URL dans le workflow.

---

## 📊 Commandes disponibles

```bash
# Build local complet
npm run build:static

# Générer uniquement le statique (serveur doit tourner)
php artisan static:generate --output=dist

# Build Vite uniquement
npm run build

# Dev local
npm run dev

# Dev avec Artisan
composer run dev
```

---

## 🔐 Variables d'environnement

Pour le workflow GitHub Actions, vous pouvez définir des secrets:

1. Settings → Secrets and variables → Actions
2. Ajouter les variables nécessaires:

```
CLOUDINARY_CLOUD_NAME=xxx
CLOUDINARY_API_KEY=xxx
CLOUDINARY_API_SECRET=xxx
```

Pour les utiliser dans le workflow, ajoutez:
```yaml
env:
  CLOUDINARY_CLOUD_NAME: ${{ secrets.CLOUDINARY_CLOUD_NAME }}
```

---

## ✅ Checklist avant déploiement

- [ ] Repository pushé sur GitHub
- [ ] `.github/workflows/deploy.yml` présent
- [ ] Dépendances mises à jour: `composer install` + `npm install`
- [ ] Branche `gh-pages` activée dans Settings → Pages
- [ ] Routes publiques définies dans `GenerateStaticSite.php`
- [ ] Premier push exécute le workflow (vérifier Actions tab)

---

## 🎯 Points clés à retenir

1. **GitHub Pages = statique uniquement** → Pas d'API côté serveur
2. **La commande Artisan** scrape toutes les routes publiques via HTTP
3. **Les assets Vite** sont copiés automatiquement dans `dist/`
4. **Deployment automatique** à chaque push sur `main`/`master`
5. **Branche spéciale** `gh-pages` est créée automatiquement

---

## 📚 Ressources

- [GitHub Pages Documentation](https://docs.github.com/en/pages)
- [Laravel 12 Documentation](https://laravel.com/docs/12)
- [Vite Documentation](https://vitejs.dev/)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)

---

## 💡 Conseils avancés

### 1. Mettre en cache les pages générées

Ajouter un `.gitkeep` dans le dossier `dist/` pour le versionner:

```bash
echo "*" > dist/.gitignore
echo "!.gitkeep" >> dist/.gitignore
touch dist/.gitkeep
git add dist/.gitignore dist/.gitkeep
```

### 2. Générer un sitemap

Ajouter une route `/sitemap.xml` qui liste toutes les pages.

### 3. Activer HTTPS custom

GitHub Pages force automatiquement HTTPS pour les domaines personnalisés.

---

Besoin d'aide ? Consultez les logs du workflow ou cette documentation !
