<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ProjectController;
use App\Models\Project;

// Login as admin (id 1)
Auth::loginUsingId(1);

// Create request data (no image upload)
$data = [
    'title' => 'HTTP simulated project',
    'description' => 'Créé via script simulant la requête admin',
    'type' => 'app',
    'source_link' => 'https://example.com'
];

$request = Request::create('/admin/projects', 'POST', $data);

$controller = new ProjectController();
$response = $controller->store($request);

// Show last project
$last = Project::orderBy('id', 'desc')->first();
if ($last) {
    echo 'created id: ' . $last->id . ', title: ' . $last->title . PHP_EOL;
} else {
    echo 'no project created' . PHP_EOL;
}

echo 'total projects: ' . Project::count() . PHP_EOL;
