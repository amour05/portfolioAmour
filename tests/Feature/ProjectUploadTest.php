<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_project_with_image_and_image_is_stored()
    {
        Storage::fake('public');

        $user = User::factory()->create(['is_admin' => true]);

        $file = UploadedFile::fake()->create('project.jpg', 100, 'image/jpeg');

        $this->withoutMiddleware();

        $response = $this->actingAs($user)
            ->post(route('admin.projects.store'), [
                'title' => 'Mon projet test',
                'description' => 'Description',
                'type' => 'site',
                'image' => $file,
            ]);

        $response->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('projects', [
            'title' => 'Mon projet test',
        ]);

        $files = Storage::disk('public')->allFiles('projects');
        $this->assertNotEmpty($files, 'Aucun fichier trouvé dans storage/projects');
    }
}
