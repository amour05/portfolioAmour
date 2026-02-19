<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample projects for static site generation
        Project::create([
            'title' => 'Vitrine Personnel',
            'description' => 'Mon portfolio personnel mettant en avant mes compétences et réalisations.',
            'type' => 'Web Application',
            'langages' => 'PHP, JavaScript, HTML, CSS',
            'framework' => 'Laravel 12',
            'outils' => 'Git, Composer, npm, Vite',
            'environnement' => 'Cloud',
            'database' => 'PostgreSQL, SQLite',
            'source_link' => 'https://github.com/amour05/portfolioAmour',
            'is_published' => true,
        ]);

        Project::create([
            'title' => 'Application E-commerce',
            'description' => 'Platform e-commerce avec gestion de produits, panier et paiement intégré.',
            'type' => 'E-commerce',
            'langages' => 'PHP, JavaScript',
            'framework' => 'Laravel',
            'outils' => 'Stripe API, Docker',
            'environnement' => 'Production',
            'database' => 'PostgreSQL',
            'source_link' => null,
            'is_published' => true,
        ]);

        Project::create([
            'title' => 'API REST Microservices',
            'description' => 'API REST scalable pour intégration avec plusieurs clients et services externes.',
            'type' => 'API',
            'langages' => 'PHP, JSON, REST',
            'framework' => 'Laravel',
            'outils' => 'JWT, API Documentation',
            'environnement' => 'Kubernetes',
            'database' => 'PostgreSQL',
            'source_link' => null,
            'is_published' => true,
        ]);
    }
}
