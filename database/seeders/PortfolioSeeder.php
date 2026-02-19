<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample projects for static site generation
        Project::create([
            'title' => 'Projet 1 - Portfolio',
            'description' => 'Mon premier projet de portfolio personnel présentant mes compétences et projets.',
            'type' => 'Web Application',
            'langages' => 'PHP, JavaScript, HTML, CSS',
            'framework' => 'Laravel 12, Vite',
            'outils' => 'Git, Composer, npm',
            'environnement' => 'Linux, Windows',
            'database' => 'PostgreSQL, SQLite',
            'source_link' => 'https://github.com/amour05/portfolioAmour',
            'is_published' => true,
        ]);

        Project::create([
            'title' => 'Projet 2 - Application E-commerce',
            'description' => 'Une application e-commerce complète avec panier, paiement et gestion d\'inventaire.',
            'type' => 'E-commerce',
            'langages' => 'PHP, JavaScript, HTML, CSS',
            'framework' => 'Laravel',
            'outils' => 'Stripe API, Git',
            'environnement' => 'Cloud',
            'database' => 'PostgreSQL',
            'source_link' => null,
            'is_published' => true,
        ]);

        Project::create([
            'title' => 'Projet 3 - API REST',
            'description' => 'Une API REST robuste pour gérer les ressources utilisateur et les données.',
            'type' => 'API',
            'langages' => 'PHP, JSON',
            'framework' => 'Laravel',
            'outils' => 'Postman, Git',
            'environnement' => 'Production',
            'database' => 'PostgreSQL',
            'source_link' => null,
            'is_published' => true,
        ]);

        // Create sample blog posts
        Post::create([
            'title' => 'Introduction à Laravel',
            'slug' => 'introduction-a-laravel',
            'content' => 'Laravel est un framework PHP moderne et élégant. Découvrez ses fonctionnalités principales et comment démarrer avec Laravel.',
            'published' => true,
        ]);

        Post::create([
            'title' => 'Les bonnes pratiques de développement',
            'slug' => 'bonnes-pratiques-developpement',
            'content' => 'Apprenez les meilleures pratiques de développement web pour écrire du code maintenable et performant.',
            'published' => true,
        ]);

        Post::create([
            'title' => 'Déploiement sur GitHub Pages',
            'slug' => 'deploiement-github-pages',
            'content' => 'Comment déployer un site statique sur GitHub Pages et comment automatiser le processus avec GitHub Actions.',
            'published' => true,
        ]);
    }
}
