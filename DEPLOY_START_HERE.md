# 🚀 DÉMARRAGE IMMÉDIAT - GitHub Pages (Main Branch)

**Vous êtes ici pour déployer ? Parfait ! Suivez ces 5 minuts. ⏱️**

---

## 🎯 Objectif final

Votre portfolio Laravel sur GitHub Pages à : `https://username.github.io/repo-name` ✅

---

## ⚡ Commandes rapides (COPIER-COLLER)

### Sur macOS/Linux

```bash
# 1. Installer les dépendances
composer install
npm install

# 2. Builder et générer
npm run build
php artisan static:generate --output=dist

# 3. Tester localement
npm run serve:dist

# Naviguez à : http://localhost:8080

# 4. Quand prêt, pousser vers GitHub
git add .
git commit -m "🚀 Deploy to GitHub Pages"
git push origin main
```

### Sur Windows (PowerShell ou CMD)

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
```

---

## ☑️ Checklist ultra-rapide

- [ ] 1. Exécutez les commandes ci-dessus
- [ ] 2. Vérifiez que `dist/index.html` existe
- [ ] 3. Testez localement à `http://localhost:8080`
- [ ] 4. Allez dans **GitHub Settings > Pages**
- [ ] 5. Sélectionnez : Branch `main` + Folder `/`
- [ ] 6. Poussez vers GitHub
- [ ] 7. Attendez 1-2 minutes
- [ ] 8. Accédez à `https://username.github.io/repo-name` ✅

---

## 📲 Après chaque modification

```bash
# Testernormalement
npm run serve:dist

# Pousser
git add .
git commit -m "Feature: description"
git push origin main

# ✅ C'est tout ! Le site se redéploie tout seul
```

---

## 🆘 Si ça ne fonctionne pas

### Erreur 404 sur GitHub Pages

```
Vérifiez:
1. Settings > Pages : main + /root
2. dist/index.html existe
3. Le workflow a réussi (Actions tab)
```

### Fichiers CSS/JS ne chargent pas

```
git ls-files build/ | head
git ls-files images/ | head

# Si vide: npm run build && git add . && git push
```

### Workflow ne se déclenche pas

```
Vérifiez:
1. .github/workflows/deploy.yml existe
2. Le fichier est en YAML valide
3. Attendez 1-2 minutes après le push
```

---

## 📚 Documentation complète

Si vous avez besoin de plus de détails :

- 📖 **Guide complet** : [DEPLOY_GITHUB_PAGES_MAIN.md](DEPLOY_GITHUB_PAGES_MAIN.md)
- 📋 **Checklist détaillée** : [DEPLOYMENT_CHECKLIST.md](DEPLOYMENT_CHECKLIST.md)
- ⚙️ **Configuration résumée** : [GITHUB_PAGES_CONFIGURATION_SUMMARY.md](GITHUB_PAGES_CONFIGURATION_SUMMARY.md)

---

## 🎉 C'est prêt !

Votre configuration GitHub Pages est complète. 

**Exécutez les commandes et profitez !** 🚀

---

### Scripts disponibles

```bash
npm run build          # Compiler les assets
npm run dev            # Mode développement
npm run deploy:local   # Générer le statique
npm run deploy:test    # Générer + tester localement
npm run serve:dist     # Servir le site généré
```

---

**Questions ?** → Consultez le guide complet  
**Prêt ?** → Allez-y ! 🚀

---

*Configuration GitHub Pages pour Portfolio Amour*  
*Version 1.0 - Main Branch Deployment*  
*Février 2026*
