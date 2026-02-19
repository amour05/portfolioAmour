# 📋 Checklist de déploiement GitHub Pages

## Phase 1️⃣ : Préparation locale

### Vérifications de l'environnement

- [ ] **PHP 8.2+** - `php --version`
  ```bash
  php --version
  # Attendu: PHP 8.2.x ou plus
  ```

- [ ] **Node.js 20+** - `node --version`
  ```bash
  node --version
  # Attendu: v20.x.x ou plus
  ```

- [ ] **Composer** - `composer --version`
  ```bash
  composer --version
  # Attendu: Composer 2.x.x
  ```

- [ ] **Git** - `git --version`
  ```bash
  git --version
  # Attendu: git 2.x.x
  ```

### Fichiers de configuration

- [ ] `.github/workflows/deploy.yml` existe
  ```bash
  ls -la .github/workflows/deploy.yml
  ```

- [ ] `app/Console/Commands/GenerateStaticSite.php` existe
  ```bash
  ls -la app/Console/Commands/GenerateStaticSite.php
  ```

- [ ] `.nojekyll` existe
  ```bash
  ls -la .nojekyll
  ```

- [ ] `_config.yml` existe
  ```bash
  ls -la _config.yml
  ```

- [ ] `package.json` contient les scripts de déploiement
  ```bash
  grep "deploy:local" package.json
  ```

---

## Phase 2️⃣ : Génération locale

### Installation des dépendances

- [ ] **Installer Composer**
  ```bash
  composer install
  ```
  ✅ Devrait terminer sans erreur

- [ ] **Installer npm**
  ```bash
  npm install
  ```
  ✅ Devrait créer `node_modules/`

### Construction des assets

- [ ] **Build Vite**
  ```bash
  npm run build
  ```
  ✅ Devrait créer `public/build/`

- [ ] **Générer site statique**
  ```bash
  php artisan static:generate --output=dist
  ```
  ✅ Devrait créer `dist/` avec les fichiers HTML

### Vérification des fichiers générés

- [ ] **index.html existe**
  ```bash
  ls -l dist/index.html
  ```

- [ ] **index.html est valide**
  ```bash
  head -20 dist/index.html
  # Devrait commencer par <!DOCTYPE html>
  ```

- [ ] **Pages statiques générées** :
  - [ ] `dist/index.html` (page d'accueil)
  - [ ] `dist/about/index.html`
  - [ ] `dist/skills/index.html`
  - [ ] `dist/projects/index.html`
  - [ ] `dist/blog/index.html`

  ```bash
  find dist -name "index.html" -type f
  ```

- [ ] **Assets copiés** :
  - [ ] `dist/build/` (CSS/JS)
  - [ ] `dist/images/` (images)

  ```bash
  ls -la dist/build/
  ls -la dist/images/
  ```

- [ ] **Fichiers de config** :
  - [ ] `dist/.nojekyll` existe
  - [ ] `dist/_config.yml` existe

  ```bash
  ls -la dist/.nojekyll dist/_config.yml
  ```

---

## Phase 3️⃣ : Test local

### Serveur local

- [ ] **Démarrer le serveur**
  ```bash
  npm run serve:dist
  ```
  ✅ Serveur deve s'écouter sur `http://localhost:8080`

- [ ] **Pages accessible** :
  - [ ] `http://localhost:8080/` (accueil)
  - [ ] `http://localhost:8080/about/` (à propos)
  - [ ] `http://localhost:8080/skills/` (compétences))
  - [ ] `http://localhost:8080/projects/` (projets)
  - [ ] `http://localhost:8080/blog/` (blog)

### Rendu visuel

- [ ] **HTML charge correctement**
  - [ ] Pas d'erreurs dans la console
  - [ ] Pas d'erreurs 404

- [ ] **CSS charge**
  - [ ] Les couleurs s'affichent
  - [ ] La mise en page est correcte
  - [ ] Les fonts s'affichent

- [ ] **JavaScript fonctionne** (si applicable)
  - [ ] Les boutons sont cliquables
  - [ ] Les animations fonctionnent
  - [ ] Les interactions répondent

- [ ] **Images s'affichent**
  - [ ] Les images du portfolio visible
  - [ ] Les icônes s'affichent
  - [ ] Les logos s'affichent

---

## Phase 4️⃣ : Configuration GitHub

### Paramètres du repository

- [ ] **Accéder à Settings**
  ```
  https://github.com/YourusERNAME/your-repo/settings
  ```

- [ ] **Aller à Pages**
  ```
  https://github.com/YourusERNAME/your-repo/settings/pages
  ```

- [ ] **Configurer Source**
  - [ ] `Deploy from a branch` sélectionné
  - [ ] Branch: `main`
  - [ ] Folder: `/ (root)`
  - [ ] Cliquer sur `Save`

- [ ] **Vérifier la confirmation**
  ```
  "Your site is live at https://username.github.io/repo-name"  
  ```

### Vérifications d'autorisations

- [ ] **Branch protection rules** (optionnel)
  - [ ] Le workflow peut pousser sur `main`
  - [ ] Vérifier `Allow force pushes` si nécessaire

---

## Phase 5️⃣ : Déploiement

### Préparation du push

- [ ] **Git status**
  ```bash
  git status
  ```
  ✅ Devrait montrer les nouveaux fichiers/modifications

- [ ] **Ajouter les fichiers**
  ```bash
  git add .
  ```

- [ ] **Créer un commit**
  ```bash
  git commit -m "🚀 Initialize GitHub Pages deployment with main branch"
  ```

- [ ] **Pousser vers GitHub**
  ```bash
  git push origin main
  ```
  ✅ Devrait pousser sans erreur

### Suivi du workflow

- [ ] **Accéder à Actions**
  ```
  https://github.com/YourusERNAME/your-repo/actions
  ```

- [ ] **Vérifier le workflow**
  - [ ] Workflow `Deploy Static Site to GitHub Pages` listé
  - [ ] Status: ⏳ (en cours) → ✅ (succès)
  - [ ] Tous les steps sont ✅ verts

- [ ] **Vérifier les logs** (si erreur)
  - [ ] Cliquer sur le workflow pour voir les détails
  - [ ] Chercher l'étape échouée
  - [ ] Lire le message d'erreur

---

## Phase 6️⃣ : Vérification en ligne

### Accès au site

- [ ] **Accéder au site**
  ```
  https://yourusername.github.io/your-repo-name
  ```

- [ ] **Vérifier les pages** :
  - [ ] Accueil charge (`/`)
  - [ ] À propos charge (`/about/`)
  - [ ] Compétences charges (`/skills/`)
  - [ ] Projets charge (`/projects/`)
  - [ ] Blog charge (`/blog/`)

### Qualité du rendu

- [ ] **Navigation**
  - [ ] Menu fonctionne
  - [ ] Les liens marchent
  - [ ] Pas de liens cassés

- [ ] **Contenu**
  - [ ] Texte affiche correctement
  - [ ] Images visibles
  - [ ] Couleurs correctes

- [ ] **Responsivité**
  - [ ] Site responsive sur mobile
  - [ ] Site responsive sur tablette
  - [ ] Site responsive sur desktop

### Performance

- [ ] **Temps de chargement** acceptables
  ```bash
  # Vérifier avec DevTools > Network
  curl -w "@curl-format.txt" -o /dev/null -s https://yourusername.github.io/your-repo-name
  ```

- [ ] **Pas d'erreurs console**
  - [ ] F12 > Console
  - [ ] Pas d'erreurs 404
  - [ ] Pas d'erreurs JavaScript

---

## Phase 7️⃣ : Optimisation (Optionnel)

### SEO

- [ ] **robots.txt** accessible
  ```bash
  curl https://yourusername.github.io/your-repo-name/robots.txt
  ```

- [ ] **Meta tags**
  - [ ] Title présent
  - [ ] Description présent
  - [ ] Open Graph (optionnel)

### Analytics (Optionnel)

- [ ] **Google Analytics** configuré (optionnel)
- [ ] **Google Search Console** soumis (optionnel)

### Domaine personnalisé (Optionnel)

- [ ] **CNAME** configuré (optionnel)
- [ ] **SSL/HTTPS** activé (optionnel)

---

## 🔄 Maintenance continue

### Après chaque modification

- [ ] **Tester localement**
  ```bash
  npm run serve:dist
  ```

- [ ] **Vérifier le build**
  ```bash
  npm run deploy:local
  ```

- [ ] **Pousser vers GitHub**
  ```bash
  git add .
  git commit -m "Feature: description"
  git push origin main
  ```

- [ ] **Vérifier le workflow**
  - [ ] Actions > workflow se déclenche
  - [ ] Workflow complète avec succès
  - [ ] Site se redéploie

### Monitoring

- [ ] **Vérifier les logs** :
  ```
  https://github.com/YourusERNAME/your-repo/actions
  ```

- [ ] **Tester le site** :
  ```bash
  curl -I https://yourusername.github.io/your-repo-name
  # Devrait retourner 200 OK
  ```

---

## ✅ Succès ! 🎉

Cochez cette case quand tout fonctionne :

- [ ] ✅ **DÉPLOIEMENT RÉUSSI !**

Votre portfolio est maintenant en ligne sur GitHub Pages ! 🚀

---

## 📞 Aide rapide

### Workflow ne s'exécute pas

```bash
# Vérifier la syntaxe YAML
cat .github/workflows/deploy.yml | head -20

# Vérifier que le fichier est bien committé
git ls-files .github/workflows/deploy.yml
```

### Site affiche erreur 404

```bash
# Vérifier que index.html existe sur main
git ls-files index.html

# Vérifier la configuration GitHub Pages
# Settings > Pages : main + /root
```

### CSS/JS ne charge pas

```bash
# Vérifier que build/ existe
git ls-files build/ | head -5

# Tester directement
curl https://yourusername.github.io/your-repo-name/build/app.css | head -20
```

---

**Version** : 1.0  
**Dernière mise à jour** : 19 février 2026  
**Status** : ✅ Prêt pour déploiement
