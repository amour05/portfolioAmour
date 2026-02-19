<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Models\Post;
use App\Models\Project;
use Exception;

class GenerateStaticSite extends Command
{
    protected $signature = 'static:generate {--output=dist}';
    protected $description = 'Generate static HTML files from public routes for GitHub Pages (no HTTP requests)';

    public function handle()
    {
        $outputDir = $this->option('output');
        
        // Clean and create output directory
        if (File::exists($outputDir)) {
            File::deleteDirectory($outputDir);
        }
        File::makeDirectory($outputDir, 0755, true);

        $this->info('🚀 Generating static site without HTTP requests...');

        // 1. Generate static pages
        $this->generateStaticPages($outputDir);

        // 2. Generate blog posts if posts table exists
        $this->generateBlogPosts($outputDir);

        // 3. Copy public assets
        $this->copyPublicAssets($outputDir);

        // 4. Create .nojekyll
        File::put("{$outputDir}/.nojekyll", '');
        $this->info("✅ Created: .nojekyll (disables Jekyll)");

        // 5. Create robots.txt
        File::put("{$outputDir}/robots.txt", "User-agent: *\nAllow: /\n");
        $this->info("✅ Created: robots.txt");

        $this->info('✅ Static site generated successfully!');
        $this->info("📁 Output directory: {$outputDir}/");
    }

    private function generateStaticPages($outputDir)
    {
        $pages = [
            '/' => 'home',
            '/about' => 'about',
            '/skills' => 'skills',
            '/projects' => 'projects',
            '/contact' => 'contact',
            '/blog' => 'blog.index',
        ];

        foreach ($pages as $route => $view) {
            try {
                if ($route === '/projects') {
                    $data = $this->getProjectsData();
                    $html = View::make($view, $data)->render();
                } elseif ($route === '/blog') {
                    $data = $this->getBlogData();
                    $html = View::make($view, $data)->render();
                } else {
                    $html = View::make($view)->render();
                }

                // Clean HTML URLs
                $html = $this->cleanHtmlUrls($html);

                // Save file
                $this->saveHtmlFile($route, $html, $outputDir);
                $this->info("✅ Generated: {$route}");
            } catch (Exception $e) {
                $this->error("❌ Failed to generate {$route}: " . $e->getMessage());
            }
        }
    }

    private function generateBlogPosts($outputDir)
    {
        try {
            // Check if posts table exists
            if (!method_exists(Post::class, 'getTable') || !File::exists('database/migrations')) {
                $this->warn("⚠️  Skipping blog posts (table might not exist)");
                return;
            }

            $posts = Post::where('is_published', true)->orWhere('published', true)->get();

            if ($posts->isEmpty()) {
                $this->info("ℹ️  No published blog posts found");
                return;
            }

            foreach ($posts as $post) {
                try {
                    $slug = $post->slug ?? str_slug($post->title);
                    $html = View::make('blog.show', ['post' => $post])->render();
                    $html = $this->cleanHtmlUrls($html);
                    
                    $this->saveHtmlFile("/blog/{$slug}", $html, $outputDir);
                    $this->info("✅ Generated: /blog/{$slug}");
                } catch (Exception $e) {
                    $this->warn("⚠️  Could not generate blog post: " . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            $this->warn("⚠️  Could not generate blog posts: " . $e->getMessage());
        }
    }

    private function copyPublicAssets($outputDir)
    {
        // Copy build assets
        if (File::exists('public/build')) {
            File::copyDirectory('public/build', "{$outputDir}/build");
            $this->info("✅ Copied: /build");
        }

        // Copy images
        if (File::exists('public/images')) {
            File::copyDirectory('public/images', "{$outputDir}/images");
            $this->info("✅ Copied: /images");
        }

        // Copy other public files
        foreach (['robots.txt', 'sitemap.xml', 'favicon.ico'] as $file) {
            if (File::exists("public/{$file}")) {
                File::copy("public/{$file}", "{$outputDir}/{$file}");
                $this->info("✅ Copied: {$file}");
            }
        }
    }

    private function cleanHtmlUrls(&$html)
    {
        // Replace dynamic URLs with static ones
        $html = preg_replace('|href="[^"]*route\(["\']([^"\']+)["\'][^"]*"|', 'href="/$1"', $html);
        $html = preg_replace('|href="[^"]*url\(["\']([^"\']+)["\'][^"]*"|', 'href="/$1"', $html);
        
        // Fix relative paths in navigation
        $html = str_replace('href="{{ url(\'/\')', 'href="/', $html);
        $html = str_replace('href="{{ route(\'', 'href="/', $html);
        
        return $html;
    }

    private function saveHtmlFile($route, $html, $outputDir)
    {
        if ($route === '/') {
            $filePath = "{$outputDir}/index.html";
        } else {
            $dir = "{$outputDir}{$route}";
            File::ensureDirectoryExists($dir);
            $filePath = "{$dir}/index.html";
        }

        File::put($filePath, $html);
    }

    private function getProjectsData()
    {
        try {
            $projects = Project::where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->paginate(9);
            return compact('projects');
        } catch (Exception $e) {
            return ['projects' => []];
        }
    }

    private function getBlogData()
    {
        try {
            $posts = Post::where('is_published', true)
                ->orWhere('published', true)
                ->latest()
                ->paginate(6);
            return compact('posts');
        } catch (Exception $e) {
            return ['posts' => []];
        }
    }
}
