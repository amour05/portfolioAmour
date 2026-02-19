# 💡 Exemples Concrets - Déploiement GitHub Pages

## Scénario 1: Première utilisation (Setup complet)

```bash
# Étape 1: Configuration initiale
composer install
npm install
php artisan key:generate

# Étape 2: Setup base de données SQLite
mkdir -p database
touch database/portfolio.sqlite
php artisan migrate --database=sqlite --force
php artisan db:seed --force

# Étape 3: Build local complet
npm run build:static

# Étape 4: Vérifier le résultat
ls -la dist/
```

**Résultat attendu:**
```
dist/
├── index.html              (✓ Page d'accueil)
├── projects/index.html     (✓ Page projets)
├── about/index.html        (✓ Page about)
├── skills/index.html       (✓ Page compétences)
├── contact/index.html      (✓ Page contact)
├── blog/index.html         (✓ Liste des articles)
├── build/                  (✓ Assets CSS/JS compilés)
└── images/                 (✓ Images copiées)
```

---

## Scénario 2: Ajouter une nouvelle page publique

### Étape 1: Créer la vue Blade

```bash
# Fichier: resources/views/mentions.blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mentions légales</h1>
    <p>Contenu de vos mentions légales...</p>
</div>
@endsection
```

### Étape 2: Ajouter la route

```php
// Fichier: routes/web.php
Route::get('/mentions', function () {
    return view('mentions');
})->name('mentions');
```

### Étape 3: Ajouter à la liste de génération

```php
// Fichier: app/Console/Commands/GenerateStaticSite.php
$publicRoutes = [
    '/',
    '/projects',
    '/about',
    '/skills',
    '/contact',
    '/blog',
    '/mentions',  // ← Ajouter cette ligne
];
```

### Étape 4: Tester et déployer

```bash
# Test local
npm run build:static

# Vérifier
ls -la dist/mentions/

# Push vers GitHub
git add .
git commit -m "Add mentions légales page"
git push origin main
```

**Résultat:** `/mentions/index.html` généré et déployé!

---

## Scénario 3: Mettre à jour un article de blog existant

### Article est automatiquement regénéré si:
- ✅ `is_published = true` dans la base de données
- ✅ La route `/blog/{slug}` existe

Exemple de post dans la DB:
```
id: 1
title: "Mon premier article"
slug: "mon-premier-article"
content: "<h2>Contenu HTML...</h2>"
is_published: true
created_at: 2026-02-19
```

**Alors automatiquement généré:**
```
dist/blog/mon-premier-article/index.html
```

Pour publier un nouvel article:
```bash
# Option 1: Via Laravel Tinker
php artisan tinker
>>> Post::create(['title' => 'Nouvel article', 'slug' => 'nouvel-article', 'content' => '...', 'is_published' => true])

# Option 2: Via un formulaire d'admin
# Aller sur /admin/posts et créer

# Option 3: Via migration/seeder
```

Puis regénérer et déployer:
```bash
npm run build:static
git add dist/
git commit -m "Update blog posts"
git push
```

---

## Scénario 4: Utiliser un domaine personnalisé

### DNS Configuration (GoDaddy, Namecheap, etc):

```
Nom: @
Type: CNAME
Valeur: username.github.io
TTL: 3600
```

Ou pour un sous-domaine:
```
Nom: portfolio
Type: CNAME
Valeur: username.github.io
TTL: 3600
```

### GitHub Repository Settings:

1. Settings → Pages
2. Custom domain: `portfolio.com`
3. ✅ Enforce HTTPS (automatique)

### Fichier CNAME automatique:

Le workflow crée automatiquement:
```bash
dist/CNAME
# Contenu:
# portfolio.com
```

---

## Scénario 5: Debugger un problème de déploiement

### Vérifier le workflow

```bash
# 1. Aller sur GitHub
# Repository → Actions → Dernier workflow

# 2. Ouvrir pour voir les logs complets
# Chercher les erreurs (généralement en rouge)
```

### Erreur commune #1: "Cannot reach localhost:8000"

**Cause:** Serveur Laravel n'a pas assez de temps pour démarrer

**Solution:** Augmenter le timeout dans `.github/workflows/deploy.yml`:
```yaml
- name: Start Laravel server
  run: |
    php artisan serve --host=localhost --port=8000 &
    sleep 5  # ← Augmenter à 10 si besoin
```

### Erreur commune #2: "Post model not found"

**Cause:** Database empty

**Solution:** S'assurer que les seeders tournent:
```yaml
- name: Seed database
  run: php artisan db:seed --force --no-interaction
  continue-on-error: true  # Ne pas bloquer du reste
```

### Erreur commune #3: "npm run build:static not found"

**Cause:** Scripts pas à jour dans package.json

**Solution:** Vérifier que package.json a:
```json
"scripts": {
    "build": "vite build",
    "build:static": "node scripts/static-generate.js dist"
}
```

---

## Scénario 6: Ajouter des secrets (Cloudinary, etc)

### Ajouter un secret GitHub

```bash
# 1. Go to: Repository Settings → Secrets and variables → Actions
# 2. Click: New repository secret
# 3. Name: CLOUDINARY_API_KEY
# 4. Value: xxxxxx
# 5. Click: Add secret
```

### Utiliser dans le workflow

```yaml
# .github/workflows/deploy.yml
steps:
  - name: Deploy to GitHub Pages
    env:
      CLOUDINARY_API_KEY: ${{ secrets.CLOUDINARY_API_KEY }}
      CLOUDINARY_CLOUD_NAME: ${{ secrets.CLOUDINARY_CLOUD_NAME }}
    run: npm run build:static
```

### Utiliser dans Laravel

```php
// app/Models/Post.php
$cloudinaryKey = env('CLOUDINARY_API_KEY');
```

---

## Scénario 7: Générer un sitemap.xml

### Créer une route

```php
// routes/web.php
Route::get('/sitemap.xml', function () {
    $posts = App\Models\Post::where('is_published', true)->get();
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ([
        '/' => 'daily',
        '/projects' => 'weekly',
        '/about' => 'monthly',
        '/blog' => 'daily'
    ] as $route => $freq) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . config('app.url') . "$route</loc>\n";
        $xml .= "    <changefreq>$freq</changefreq>\n";
        $xml .= "  </url>\n";
    }
    
    foreach ($posts as $post) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . config('app.url') . "/blog/{$post->slug}</loc>\n";
        $xml .= "    <lastmod>" . $post->updated_at->toAtomString() . "</lastmod>\n";
        $xml .= "  </url>\n";
    }
    
    $xml .= '</urlset>';
    return response($xml, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');
```

### Ajouter à la génération

```php
// app/Console/Commands/GenerateStaticSite.php
$publicRoutes = [
    // ... autres
    '/sitemap.xml',  // ← Ajouter
];
```

### Résultat

```
dist/sitemap.xml  (généré automatiquement)
```

---

## Cheat Sheet - Commandes essentielles

| Commande | Utilité |
|----------|---------|
| `npm run build` | Build Vite uniquement |
| `npm run build:static` | Build complet (Assets + HTML) |
| `php artisan serve` | Démarrer le serveur |
| `php artisan static:generate` | Générer pages HTML |
| `php artisan migrate --database=sqlite` | Créer tables |
| `php artisan db:seed` | Remplir la base |
| `php artisan tinker` | Shell PHP interactive |
| `./scripts/diagnostic.bat` | Vérifier la configuration |
| `./scripts/quick-start.bat` | Menu rapide |

---

## Flux schématique du déploiement

```
[1. Local Development]
   ↓
   composer install
   npm install
   ↓
[2. Test Build]
   ↓
   npm run build:static
   ↓
[3. Verify dist/ folder]
   ↓
   git add .
   git commit
   git push
   ↓
[4. GitHub Actions Workflow Starts]
   ├─ Setup PHP 8.2
   ├─ Setup Node 20
   ├─ Install dependencies
   ├─ Build Vite assets
   ├─ Setup SQLite DB
   ├─ Generate static pages
   └─ Deploy to gh-pages
   ↓
[5. GitHub Pages Updates]
   ↓
[6. Live on https://username.github.io/repo/]
```

---

✅ Vous êtes prêt à déployer!
