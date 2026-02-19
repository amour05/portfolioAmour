# 📖 Guide de Déploiement sur GitHub Pages (Main Branch)

## 📋 Vue d'ensemble

Ce guide explique comment déployer votre portfolio Laravel sur GitHub Pages **sans créer de branche `gh-pages`**. Tous les fichiers statiques sont générés et poussés directement sur la branche `main`.

## 🎯 Objectif

- ✅ Repository GitHub Pages configuré sur la branche `main`
- ✅ Site statique généré à partir du code Laravel
- ✅ Fichiers HTML, CSS, JS à la racine du repository
- ✅ Déploiement automatique via GitHub Actions
- ✅ Pas de branche `gh-pages` séparée

## 🏗️ Architecture

```
┌─────────────────────┐
│  Développement      │
│  (code source)      │
├─────────────────────┤
│ • Laravel app       │
│ • Vite assets       │
│ • Database          │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────────────────┐
│  GitHub Actions Workflow        │
│  (.github/workflows/deploy.yml) │
├─────────────────────────────────┤
│ 1. Install dependencies         │
│ 2. Build assets                 │
│ 3. Generate static HTML         │
│ 4. Clean Laravel files          │
│ 5. Push to main                 │
└──────┬──────────────────────────┘
       │
       ▼
┌─────────────────────────────────┐
│  GitHub Pages (main branch)     │
│  https://username.github.io     │
├─────────────────────────────────┤
│ • index.html (root)             │
│ • CSS/JS assets                 │
│ • Images                        │
│ • .nojekyll                     │
│ • _config.yml                   │
└─────────────────────────────────┘
```

## 🚀 Configuration

### 1. Configuration GitHub Pages

1. Allez dans **Settings > Pages**
2. Sélectionnez :
   - **Source** : Deploy from a branch
   - **Branch** : `main`
   - **Folder** : `/ (root)`
3. Sauvegardez

> ⚠️ **Important** : Assurez-vous que `main` est sélectionnée comme branche source, pas `gh-pages`

### 2. Vérifier le workflow

Le fichier [`.github/workflows/deploy.yml`](.github/workflows/deploy.yml) configure automatiquement :

- Installation de PHP 8.2
- Installation des dépendances Composer
- Build des assets Vite
- Génération des fichiers statiques
- Push sur `main`

## 📝 Utilisation locale

### Générer le site statique en local

**Sur Windows :**
```bash
scripts\deploy-static.bat
```

**Sur macOS/Linux :**
```bash
bash scripts/deploy-static.sh
```

**Ou manuellement :**
```bash
# 1. Builder les assets
npm run build

# 2. Générer le site statique
php artisan static:generate --output=dist
```

### Tester en local

```bash
# Avec Node.js http-server
npx http-server dist

# Naviguez vers: http://localhost:8080
```

## 📤 Déploiement

### Option 1 : Déploiement automatique (Recommandé)

1. Les changements sur `main` déclenchent automatiquement le workflow
2. Le workflow génère les fichiers statiques
3. Les fichiers sont automatiquement pushés sur `main`
4. GitHub Pages se re-déploie automatiquement

**Aucune action manuelle requise !**

### Option 2 : Déploiement manuel

```bash
# 1. Générer localement
npm run build
php artisan static:generate --output=dist

# 2. Copier les fichiers
cp -r dist/* .

# 3. Nettoyer les fichiers Laravel (optionnel)
rm -rf app config database resources routes vendor node_modules artisan composer.json

# 4. Commiter et pousser
git add .
git commit -m "Deploy: Update static site"
git push origin main
```

### Option 3 : Déclencher manuellement le workflow

1. Allez dans **Actions** sur GitHub
2. Sélectionnez le workflow **Deploy Static Site**
3. Cliquez sur **Run workflow**
4. Attendez la complétion

## 📋 Pages générées

Le workflow génère automatiquement les pages suivantes :

| Route | Fichier | Description |
|-------|---------|-------------|
| `/` | `index.html` | Page d'accueil |
| `/about` | `about/index.html` | À propos |
| `/skills` | `skills/index.html` | Compétences |
| `/projects` | `projects/index.html` | Projets |
| `/contact` | `contact/index.html` | Contact |
| `/blog` | `blog/index.html` | Blog (index) |
| `/blog/{slug}` | `blog/{slug}/index.html` | Articles individuels |

## 🔧 Configuration avancée

### Personnaliser les pages générées

Modifiez la commande `static:generate` dans [GenerateStaticSite.php](app/Console/Commands/GenerateStaticSite.php) :

```php
$pages = [
    '/' => 'home',
    '/about' => 'about',
    '/skills' => 'skills',
    // Ajouter d'autres routes publiques
];
```

### Ajouter des pages dynamiques

Pour générer des pages dynamiques (comme les articles de blog) :

```php
private function generateBlogPosts($outputDir)
{
    $posts = Post::where('published', true)->get();
    foreach ($posts as $post) {
        $html = View::make('blog.show', ['post' => $post])->render();
        $this->saveHtmlFile("/blog/{$post->slug}", $html, $outputDir);
    }
}
```

### Ignorer les fichiers Laravel en production

Le fichier [`.gitignore`](.gitignore) exclut automatiquement :

- `/vendor/` - Dépendances Composer
- `node_modules/` - Dépendances npm
- `.env` - Variables d'environnement
- `/storage/` - Fichiers temporaires
- `/app`, `/config`, `/database`, etc. - Code source

## 🐛 Dépannage

### Issue : `dist/index.html` non généré

**Causes possibles :**
1. La liaison `http://localhost:8000/` n'existe pas
2. Les vues ne peuvent pas être rendues

**Solutions :**
```bash
# Vérifier que la commande fonctionne
php artisan static:generate --output=dist -v

# Vérifier la vue 'home'
php artisan tinker
View::make('home')->render();
```

### Issue : Erreur 404 sur GitHub Pages

**Assurez-vous que :**
1. ✅ `index.html` existe à la racine
2. ✅ `.nojekyll` existe pour désactiver Jekyll
3. ✅ GitHub Pages est configuré sur le branch `main` `/root`

```bash
# Vérifier
ls -la index.html .nojekyll
```

### Issue : Styles CSS/JS ne chargent pas

**Causes :**
- Les URLs sont relative au dossier courant
- Les assets ne sont pas copiés correctement

**Solution :**
```bash
# Vérifier les fichiers build/ et images/
ls -la build/ images/
```

### Issue : Le workflow ne se déclenche pas

**Vérifications :**
1. ✅ Le workflow est sur la branche `main`
2. ✅ Le fichier est à `.github/workflows/deploy.yml`
3. ✅ La syntaxe YAML est correcte

```bash
# Tester la syntaxe
yamllint .github/workflows/deploy.yml
```

## 📊 Monitoring

### Vérifier l'état du workflow

1. Allez dans **Actions** sur GitHub
2. Cherchez le workflow **Deploy Static Site**
3. Consultez les logs pour chaque étape

### Vérifier le site en ligne

```bash
# Vérifier que le site est servi
curl -I https://<username>.github.io

# Vérifier la présence de index.html
curl https://<username>.github.io/index.html | head -20
```

## 🔐 Sécurité

### Points d'attention

- ⚠️ Les variables `.env` NE sont PAS incluses dans le déploiement
- ⚠️ La base de données n'est PAS déployée
- ⚠️ Seuls les fichiers statiques sont servis

### Secrets GitHub

Si vous avez besoin de secrets pour la génération (ex: API keys) :

1. Allez dans **Settings > Secrets and variables > Actions**
2. Cliquez sur **New repository secret**
3. Utiliser dans le workflow : `${{ secrets.MON_SECRET }}`

## 📚 Ressources supplémentaires

- [GitHub Pages Documentation](https://docs.github.com/en/pages)
- [Laravel Documentation](https://laravel.com/docs)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Vite Documentation](https://vitejs.dev)

## ❓ FAQ

**Q: Puis-je développer localement pendant que le site est en ligne ?**
A: Oui, le code source reste sur votre machine. Poussez simplement vos changements et le workflow se rédéploiera automatiquement.

**Q: Comment puis-je mettre à jour les posts de blog ?**
A: Modifiez la base données, puis relancez le workflow. Seules les posts "published" seront incluses.

**Q: Est-ce gratuit ?**
A: Oui, GitHub Pages est gratuit et le workflow GitHub Actions est gratuit jusqu'à 2000 minutes/mois.

**Q: Puis-je utiliser un domaine personnalisé ?**
A: Oui, créez un fichier `CNAME` à la racine avec votre domaine et configurez les DNS.

---

**Dernière mise à jour** : Février 2026
**Maintenant par** : Portfolio Amour
**Version** : 1.0 - GitHub Pages (Main Branch)
