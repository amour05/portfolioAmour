# Configuration pour GitHub Pages

## Paramètres d'URL

Votre portfolio sera accessible à:
```
https://<username>.github.io/<repository-name>
```

## Configuration requise

1. **Repository Settings** → **Pages**
   - Source: Deploy from a branch
   - Branch: gh-pages
   - Folder: / (root)

2. **Si vous avez un domaine personnel:**
   - Ajouter le domaine dans Settings → Pages → Custom domain
   - Créer un fichier `CNAME` à la racine du dossier `dist/` avec votre domaine

## Déploiement automatique

À chaque push sur `main` ou `master`, le workflow GitHub Actions:
1. Install les dépendances PHP et Node
2. Builder les assets Vite
3. Génère les pages HTML statiques
4. Déploie vers la branche `gh-pages`

## Variables d'environnement pour production

Vous pouvez définir des secrets GitHub:
- Aller à Settings → Secrets and variables → Actions
- Ajouter les variables nécessaires si vous utilisez des APIs externes

## Troubleshooting

**Les pages ne s'affichent pas?**
- Vérifiez que la branche `gh-pages` existe
- Vérifier les logs du workflow dans Repository → Actions

**Site avec base URL incorrecte?**
- Modifier `config/static.js` si vous avez un domaine personnel
- Ajouter un fichier `.htaccess` ou mettre à jour les URLs dans les vues Blade
