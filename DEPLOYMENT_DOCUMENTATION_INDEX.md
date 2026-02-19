# 📑 Index de documentation - Déploiement GitHub Pages

## 🎯 Point De Départ

**👉 COMMENCEZ ICI :**
- **[DEPLOY_START_HERE.md](DEPLOY_START_HERE.md)** ⭐ - Guide 5 minutes pour démarrer immédiatement

---

## 📚 Documentation par besoin

### Je veux déployer maintenant
1. [DEPLOY_START_HERE.md](DEPLOY_START_HERE.md) - 5 minutes
2. Exécuter les commandes
3. Consulter [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md) pendant le déploiement

### Je veux comprendre le processus complet
- [DEPLOY_GITHUB_PAGES_MAIN.md](DEPLOY_GITHUB_PAGES_MAIN.md) - Documentation complète et détaillée

### Je veux vérifier que tout est configuré
- [GITHUB_PAGES_CONFIGURATION_SUMMARY.md](GITHUB_PAGES_CONFIGURATION_SUMMARY.md) - Synthèse de la configuration
- [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md) - Checklist détaillée à coche

### Je veux tester avant de déployer
- [DEPLOY_QUICK_START.md](DEPLOY_QUICK_START.md) - Guide rapide de déploiement local

### Je veux valider la configuration
- Exécuter : `bash scripts/validate-deployment.sh` (macOS/Linux)
- Exécuter : `scripts\validate-deployment.bat` (Windows)

---

## 🛠️ Scripts disponibles

### Déploiement

| Script | OS | Usage |
|--------|----|----|
| `scripts/deploy-static.sh` | macOS/Linux | Générer le site statique local |
| `scripts/deploy-static.bat` | Windows | Générer le site statique local |

### Validation

| Script | OS | Usage |
|--------|----|----|
| `scripts/validate-deployment.sh` | macOS/Linux | Valider la configuration |
| `scripts/validate-deployment.bat` | Windows | Valider la configuration |

### npm (package.json)

```bash
npm run build              # Compiler les assets Vite
npm run dev                # Mode développement local
npm run deploy:local       # Générer le site statique
npm run deploy:test        # Générer + servir localement
npm run serve:dist         # Servir les fichiers statiques générés
```

---

## 📝 Fichiers de configuration

| Fichier | Description |
|---------|-------------|
| `.github/workflows/deploy.yml` | 🔑 Workflow GitHub Actions (automatique) |
| `app/Console/Commands/GenerateStaticSite.php` | Commande Artisan pour générer le statique |
| `.nojekyll` | Fichier GitHub Pages (désactive Jekyll) |
| `_config.yml` | Configuration GitHub Pages |
| `.gitignore` | Fichiers à ignorer (ex: Laravel code en prod) |
| `DEPLOY_START_HERE.md` | ⭐ Démarrage rapide |
| `DEPLOY_QUICK_START.md` | Guide rapide |
| `DEPLOY_GITHUB_PAGES_MAIN.md` | Documentation complète |
| `GITHUB_PAGES_CONFIGURATION_SUMMARY.md` | Synthèse technique |
| `DEPLOYMENT_CHECKLIST.md` | Checklist de déploiement |

---

## 🚀 Flux de travail typique

```
┌─────────────────────┐
│ 1. Lire              │
│ DEPLOY_START_HERE   │
└──────────┬──────────┘
           ↓
┌─────────────────────┐
│ 2. Exécuter les     │
│ commandes (composer │
│ install, npm build) │
└──────────┬──────────┘
           ↓
┌─────────────────────┐
│ 3. Générer local    │
│ (npm run            │
│ deploy:local)       │
└──────────┬──────────┘
           ↓
┌─────────────────────┐
│ 4. Tester local     │
│ (npm run serve:dist)│
└──────────┬──────────┘
           ↓
┌─────────────────────┐
│ 5. Pousser GitHub   │
│ (git push origin    │
│ main)               │
└──────────┬──────────┘
           ↓
┌─────────────────────┐
│ 6. Attendre         │
│ déploiement         │
│ (1-2 minutes)       │
└──────────┬──────────┘
           ↓
┌─────────────────────┐
│ 7. Vérifier site    │
│ https://username    │
│ .github.io/repo     │
└─────────────────────┘
```

---

## 📊 Configuration en place

✅ **GitHub Actions Workflow**
- Déclenché automatiquement quand vous poussez sur `main`
- Installe PHP 8.2 + Composer + Node.js
- Build les assets Vite
- Génère les fichiers HTML statiques
- Pousse les changements sur `main`

✅ **Commande Artisan personnalisée**
- `php artisan static:generate --output=dist`
- Rend les vues Blade en HTML directement
- Sans dépendre d'un serveur HTTP local
- Gère les pages dynamiques (blog)

✅ **Configuration GitHub Pages**
- Branch source : `main`
- Folder : `/` (root)
- Index : `index.html`
- `.nojekyll` pour désactiver Jekyll

✅ **Scripts locaux**
- `deploy-static.sh` (Linux/macOS)
- `deploy-static.bat` (Windows)
- Pour générer et tester localement

---

## 🔐 Sécurité

⚠️ **Important** :
- Pas de données sensibles en production
- `.env` est **ignorée** et ne sera pas déployée
- Base de données n'est pas synchronisée
- Seuls les fichiers HTML/CSS/JS statiques sont servis

---

## ✅ Vérification finale

Pour vérifier que tout est configuré :

```bash
# macOS/Linux
bash scripts/validate-deployment.sh

# Windows
scripts\validate-deployment.bat
```

Tous les checks doivent être ✅

---

## 🆘 Dépannage rapide

| Problème | Solution |
|----------|----------|
| Workflow ne se déclenche | Vérifier `.github/workflows/deploy.yml` existe |
| Erreur 404 | Vérifier GitHub Pages config: `main` + `/` |
| CSS ne charge pas | Vérifier `build/` est copié |
| `index.html` vide | Vérifier les vues Blade se rendent |
| Impossibilité de déployer | Consulter [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md) |

---

## 📞 Ressources et liens

- 📖 [GitHub Pages Official](https://docs.github.com/en/pages)
- 📖 [Laravel Documentation](https://laravel.com/docs)
- 📖 [GitHub Actions Documentation](https://docs.github.com/en/actions)
- 📖 [Vite Documentation](https://vitejs.dev)

---

## 📋 Résumé des changements

### Fichiers créés/modifiés

**Workflow**
- ✅ `.github/workflows/deploy.yml` - Entièrement refondu

**Commandes**
- ✅ `app/Console/Commands/GenerateStaticSite.php` - Modernisée (pas de requête HTTP)

**Documentation** (CRÉÉE)
- ✅ `DEPLOY_START_HERE.md` - Guide 5 minutes
- ✅ `DEPLOY_QUICK_START.md` - Guide rapide
- ✅ `DEPLOY_GITHUB_PAGES_MAIN.md` - Doc complète
- ✅ `GITHUB_PAGES_CONFIGURATION_SUMMARY.md` - Synthèse
- ✅ `DEPLOYMENT_CHECKLIST.md` - Checklist complète
- ✅ `DEPLOYMENT_DOCUMENTATION_INDEX.md` - Ce fichier

**Scripts** (CRÉÉS/MODIFIÉS)
- ✅ `scripts/deploy-static.sh` - Script de déploiement (Linux/macOS)
- ✅ `scripts/deploy-static.bat` - Script de déploiement (Windows)
- ✅ `scripts/validate-deployment.sh` - Validation (Linux/macOS)
- ✅ `scripts/validate-deployment.bat` - Validation (Windows)

**Configuration**
- ✅ `.nojekyll` - Créé/vérifié
- ✅ `_config.yml` - Créé/vérifié
- ✅ `.gitignore` - Mis à jour
- ✅ `package.json` - Ajout de scripts npm

---

## 🎉 Prêt ?

1. **Commencez par** : [DEPLOY_START_HERE.md](DEPLOY_START_HERE.md)
2. **Exécutez les commandes**
3. **Poussez sur GitHub**
4. **Vérifiez le site en ligne** ✅

---

**Version** : 1.0  
**Date** : 19 février 2026  
**Configuration** : Portfolio Amour - GitHub Pages (Main Branch)  

**Status** : ✅ **Complet et prêt pour déploiement**

---

## 🚀 Commandes rapides pour démarrer

```bash
# 1. Installer
composer install
npm install

# 2. Builder
npm run build
php artisan static:generate --output=dist

# 3. Tester
npm run serve:dist

# 4. Pousser
git add .
git commit -m "🚀 Deploy to GitHub Pages"
git push origin main

# ✅ C'est terminé !
```

**Allez-y ! 🚀**
