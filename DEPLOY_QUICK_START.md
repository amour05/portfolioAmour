# 🚀 Déploiement GitHub Pages - Guide Rapide

## ✅ Pré-requis

- ✅ Repository GitHub configuré
- ✅ PHP 8.2 installé localement
- ✅ Node.js 20+ installé
- ✅ Composer installé
- ✅ Git configuré

## 🚀 5 étapes pour déployer

### 1️⃣ Générer le site statique

```bash
# Windows
scripts\deploy-static.bat

# macOS/Linux
bash scripts/deploy-static.sh

# Ou manuellement
npm run build
php artisan static:generate --output=dist
```

### 2️⃣ Vérifier le résultat

```bash
# Vérifier que index.html existe
ls dist/index.html

# Tester localement
npx http-server dist
# Naviguez à http://localhost:8080
```

### 3️⃣ Configurer GitHub Pages

1. Allez dans **Settings > Pages**
2. Source : `main` branch, `/root` folder
3. Attendez la confirmation

### 4️⃣ Pousser vers GitHub

```bash
# Ajouter tous les fichiers
git add .

# Commiter
git commit -m "🚀 Deploy static site to GitHub Pages"

# Pousser
git push origin main
```

### 5️⃣ Vérifier le déploiement

- ✅ Allez dans **Actions** pour vérifier le workflow
- ✅ Le site sera disponible à : `https://username.github.io/repo-name`
- ✅ Vérifiez que `index.html` charge correctement

## 🔄 Déploiement automatique

Le workflow est maintenant **automatique** :

```
Vous modifiez le code → Push sur main 
  → Workflow génère le statique 
    → Pousse sur main 
      → GitHub Pages se redéploie ✅
```

**Aucune action manuelle !**

## 📂 Structure after deployment

```
main branch (après déploiement)
├── index.html          ← Page d'accueil
├── about/index.html    ← À propos
├── skills/index.html   ← Compétences
├── projects/index.html ← Projets
├── blog/index.html     ← Blog (index)
├── blog/article-1/index.html ← Article de blog
├── build/              ← Assets CSS/JS
├── images/             ← Images
├── .nojekyll           ← Désactive Jekyll
├── _config.yml         ← Config GitHub Pages
├── robots.txt          ← SEO
└── .github/workflows/  ← Workflow (conservé)
```

## 🆘 Problèmes courants

### 🔴 Erreur 404 sur GitHub Pages

```bash
# Vérifier que index.html existe à la racine
git ls-files index.html

# Vérifier .nojekyll
git ls-files .nojekyll

# Vérifier la configuration GitHub Pages
# Settings > Pages : main + /root
```

### 🔴 Workflow ne se déclenche pas

1. Vérifiez que `.github/workflows/deploy.yml` existe
2. Allez dans **Actions > Enable workflows**
3. Attendez le prochain push de changements

### 🔴 CSS/JS ne charge pas

```bash
# Vérifier les chemins des assets
curl https://username.github.io/build/app.css

# Vérifier le fichier HTML généré
curl https://username.github.io/index.html | grep build
```

## 📚 Documentation complète

Pour plus de détails, consultez : [DEPLOY_GITHUB_PAGES_MAIN.md](DEPLOY_GITHUB_PAGES_MAIN.md)

## 🎯 Checklist finale

- [ ] Fichiers dans `dist/` générés avec succès
- [ ] `index.html` existe à la racine
- [ ] `.nojekyll` existe
- [ ] GitHub Pages configuré sur `main` + `/root`
- [ ] Workflow `.github/workflows/deploy.yml` existe
- [ ] Site accessible à `https://username.github.io`
- [ ] Toutes les pages (home, about, projects, etc.) chargent
- [ ] CSS et images s'affichent correctement

## 🎉 Succès !

Votre portfolio est maintenant publié sur GitHub Pages ! 🚀

---

**Questions ?** Consultez la [documentation complète](DEPLOY_GITHUB_PAGES_MAIN.md)
