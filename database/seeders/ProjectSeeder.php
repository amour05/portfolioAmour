<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $source = public_path('images/projets/vitrine.png');
        $target = 'projects/vitrine.png';

        if (file_exists($source)) {
            Storage::disk('public')->put($target, fopen($source, 'r'));
        }

        Project::create([
            'title' => 'Vitrine Test',
            'description' => 'Projet vitrine avec image locale.',
            'type' => 'site',
            'image' => $target,
            'source_link' => 'https://github.com/amour/vitrine',
        ]);
    }
}
