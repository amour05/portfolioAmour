#!/usr/bin/env node

/**
 * Script pour générer le site statique pour GitHub Pages
 * Usage: npm run build:static
 */

import fs from 'fs';
import path from 'path';
import { spawnSync } from 'child_process';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const OUTPUT_DIR = process.argv[2] || 'dist';

console.log('🏗️  Building static site for GitHub Pages...\n');

// Étape 1: Nettoyer les anciens builds
console.log('📦 Step 1: Cleaning old builds...');
if (fs.existsSync(OUTPUT_DIR)) {
    fs.rmSync(OUTPUT_DIR, { recursive: true, force: true });
}
console.log('✅ Cleaned.\n');

// Étape 2: Builder les assets avec Vite
console.log('📦 Step 2: Building assets with Vite...');
const viteBuild = spawnSync('npm', ['run', 'build'], { 
    stdio: 'inherit',
    shell: true
});

if (viteBuild.status !== 0) {
    console.error('❌ Vite build failed!');
    process.exit(1);
}
console.log('✅ Assets built.\n');

// Étape 3: Générer les pages statiques
console.log('📦 Step 3: Generating static pages...');
const generateStatic = spawnSync('php', ['artisan', 'static:generate', `--output=${OUTPUT_DIR}`], {
    stdio: 'inherit',
    shell: true,
    env: Object.assign({}, process.env, {
        APP_ENV: 'production',
    })
});

if (generateStatic.status !== 0) {
    console.error('❌ Static generation failed!');
    process.exit(1);
}
console.log('✅ Static pages generated.\n');

// Étape 4: Créer un fichier de configuration pour GitHub Pages
console.log('📦 Step 4: Creating GitHub Pages configuration...');
const configPath = path.join(OUTPUT_DIR, '_config.yml');
const gitignorePath = path.join(OUTPUT_DIR, '.gitignore');

fs.writeFileSync(configPath, `# GitHub Pages configuration for Portfolio
# Deploy: gh-pages
# Branch: gh-pages
domain: github.io
title: Portfolio
lang: en
url: 
baseurl: 

# Jekyll settings
markdown: kramdown
highlighter: rouge
future: true
`);

fs.writeFileSync(gitignorePath, `.DS_Store
.env
node_modules/
storage/
`);

console.log('✅ Configuration created.\n');

console.log('🎉 Static site build complete!');
console.log(`📁 Output: ${OUTPUT_DIR}/`);
console.log(`🌐 Ready to deploy to GitHub Pages!\n`);

process.exit(0);
