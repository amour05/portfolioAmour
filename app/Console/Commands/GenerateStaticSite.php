<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;
use Exception;

class GenerateStaticSite extends Command
{
    protected $signature = 'static:generate {--output=dist}';
    protected $description = 'Generate static HTML files from public routes for GitHub Pages';

    public function handle()
    {
        $outputDir = $this->option('output');
        
        // Clean and create output directory
        if (File::exists($outputDir)) {
            File::deleteDirectory($outputDir);
        }
        File::makeDirectory($outputDir, 0755, true);

        // Routes publiques à générer
        $publicRoutes = [
            '/',
            '/projects',
            '/about',
            '/skills',
            '/contact',
            '/blog',
        ];

        $this->info('🚀 Generating static site...');
        
        $client = new Client([
            'base_uri' => 'http://localhost:8000/',
            'timeout'  => 30,
        ]);

        foreach ($publicRoutes as $route) {
            try {
                $this->generateRoute($client, $route, $outputDir);
                $this->info("✅ Generated: {$route}");
            } catch (Exception $e) {
                $this->error("❌ Failed to generate {$route}: " . $e->getMessage());
            }
        }

        // Générer les pages blog dynamiques
        $this->generateBlogPosts($client, $outputDir);

        // Copier les assets publics
        $this->copyPublicAssets($outputDir);

        // Créer .nojekyll pour désactiver Jekyll sur GitHub Pages
        File::put("{$outputDir}/.nojekyll", '');
        $this->info("✅ Created: .nojekyll (disables Jekyll)");

        // Créer un fichier robots.txt
        $robotsTxt = "User-agent: *\nAllow: /\n";
        File::put("{$outputDir}/robots.txt", $robotsTxt);
        $this->info("✅ Created: robots.txt");

        $this->info('✅ Static site generated successfully!');
        $this->info("📁 Output directory: {$outputDir}/");
        $this->info("\n📝 Tip: Make sure .nojekyll is pushed to gh-pages branch!");
    }

    private function generateRoute($client, $route, $outputDir)
    {
        $response = $client->get(ltrim($route, '/'));
        $html = (string) $response->getBody();

        // Déterminer le chemin du fichier
        if ($route === '/') {
            $filePath = "{$outputDir}/index.html";
        } else {
            $dir = "{$outputDir}" . dirname($route);
            File::ensureDirectoryExists($dir);
            $filePath = "{$outputDir}{$route}/index.html";
        }

        File::put($filePath, $html);
    }

    private function generateBlogPosts($client, $outputDir)
    {
        // Récupérer tous les posts publiés depuis la base de données
        try {
            $posts = \App\Models\Post::where('is_published', true)->get();

            foreach ($posts as $post) {
                try {
                    $this->generateRoute($client, "/blog/{$post->slug}", $outputDir);
                    $this->info("✅ Generated: /blog/{$post->slug}");
                } catch (Exception $e) {
                    $this->error("❌ Failed to generate blog post {$post->slug}");
                }
            }
        } catch (Exception $e) {
            $this->warn("⚠️  Could not generate blog posts: " . $e->getMessage());
        }
    }

    private function copyPublicAssets($outputDir)
    {
        // Copier les assets compilés build/
        if (File::exists('public/build')) {
            File::copyDirectory('public/build', "{$outputDir}/build");
            $this->info("✅ Copied: /build assets");
        }

        // Copier les images
        if (File::exists('public/images')) {
            File::copyDirectory('public/images', "{$outputDir}/images");
            $this->info("✅ Copied: /images assets");
        }

        // Copier d'autres fichiers statiques si nécessaire
        if (File::exists('public/robots.txt')) {
            File::copy('public/robots.txt', "{$outputDir}/robots.txt");
            $this->info("✅ Copied: robots.txt");
        }
    }
}
