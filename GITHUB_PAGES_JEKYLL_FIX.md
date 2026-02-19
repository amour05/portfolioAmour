# 🔧 GitHub Pages Jekyll Error - Solution

## ❌ Problème

Vous avez reçu cette erreur:
```
GitHub Pages: github-pages v232 GitHub Pages: jekyll v3.10.0
Error: No such file or directory @ dir_chdir0 - /github/workspace/docs
```

**Cause**: GitHub Pages essaie d'utiliser **Jekyll** (moteur de compilation par défaut) pour traiter vos fichiers Markdown au lieu de servir les fichiers HTML statiques.

---

## ✅ Solution

### Qu'est-ce que `.nojekyll`?

Le fichier `.nojekyll` est un fichier vide qui indique à GitHub Pages:
> "Ne compile rien. Servez simplement les fichiers statiques tels quels."

### Changements Appliqués

1. **✅ Créé `.nojekyll` à la racine du dépôt**
   - Ce fichier indique que ce projet n'utilise pas Jekyll

2. **✅ Modifié le workflow pour créer `dist/.nojekyll`**
   - Avant le déploiement, le workflow crée explicitement `dist/.nojekyll`
   - Cela désactive Jekyll pour le contenu servi depuis `gh-pages`

3. **✅ Amélioré la commande Artisan**
   - `app/Console/Commands/GenerateStaticSite.php` crée maintenant `.nojekyll` pendant la génération

### Fichiers Modifiés

| Fichier | Changement |
|---------|-----------|
| `.nojekyll` | Créé à la racine (nouveau) |
| `.github/workflows/deploy.yml` | Ajoute `touch dist/.nojekyll` avec vérification |
| `app/Console/Commands/GenerateStaticSite.php` | Crée `.nojekyll` pendant la génération |

---

## 🚀 Redéploiement

Le workflow a été retriggeré automatiquement. Vérifiez:

**URL**: GitHub → Actions → `build-and-deploy` → Logs

**Recherchez** dans les logs:
```
✅ .nojekyll created successfully
```

---

## 🔍 Vérification

Une fois le déploiement réussi, vérifiez que `.nojekyll` est présent:

### Via Git (local)
```bash
# Vérifiez que .nojekyll existe dans gh-pages
git fetch origin
git show origin/gh-pages:.nojekyll
# Doit afficher un fichier vide (ou rien)
```

### Via GitHub Web
1. Visitez votre dépôt
2. Changez la branche vers `gh-pages`
3. Cherchez `.nojekyll` à la racine
4. Cliquez dessus - doit être vide

---

## 📋 Checklist Post-Fix

- [ ] Workflow déploiement réussi (Actions → ✅)
- [ ] `.nojekyll` visible dans la branche `gh-pages`
- [ ] Site accessible sans erreur Jekyll: `https://amour05.github.io/portfolioAmour/`
- [ ] Page d'accueil affiche `dist/index.html` correctement

---

## 💡 Pourquoi Jekyll?

GitHub Pages utilise Jekyll par défaut pour transformer du Markdown en HTML. Mais puisque **vous générez déjà du HTML statique** depuis votre application Laravel, vous n'avez pas besoin de Jekyll.

Le fichier `.nojekyll` dit à GitHub Pages: "Pas besoin de Jekyll, les fichiers CSS/JS/HTML sont déjà prêts."

---

## 🆘 Si ça ne marche toujours pas

1. **Vérifiez les logs du workflow**
   - Actions → build-and-deploy → Logs
   - Cherchez: "✅ .nojekyll created successfully"

2. **Vérifiez la branche gh-pages**
   - Allez à Settings → Pages
   - Source doit être: `Deploy from a branch`
   - Branch: `gh-pages` (ou `gh-pages / root` si le dropdown affiche aussi le dossier)

3. **Forcez un redéploiement**
   ```bash
   git commit --allow-empty -m "Force redeploy"
   git push origin main
   ```

4. **Vérifiez ls fichiers dans dist/**
   - Logs du workflow → step "Configure GitHub Pages"
   - Doit afficher `dist/.nojekyll` existant

---

## 📚 Ressources

- [GitHub Pages with Jekyll disabled](https://github.blog/changelog/2020-09-15-github-pages-from-branch-deployments/)
- [peaceiris/actions-gh-pages documentation](https://github.com/peaceiris/actions-gh-pages#disabling-jekyll)
- [Creating a GitHub Pages site](https://docs.github.com/en/pages/getting-started-with-github-pages/creating-a-github-pages-site)

---

Status: ✅ Fixed and redeployed
Date: 2026-02-19
