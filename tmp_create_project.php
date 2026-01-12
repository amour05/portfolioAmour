<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Project;

echo 'env DB_CONNECTION: ' . env('DB_CONNECTION') . PHP_EOL;
echo 'config database.default: ' . config('database.default') . PHP_EOL;
echo 'sqlite path: ' . config('database.connections.sqlite.database') . PHP_EOL;

$p = Project::create([
    'title' => 'Tmp script test',
    'description' => 'créé via tmp script',
    'type' => 'site'
]);

echo 'created id: ' . $p->id . PHP_EOL;
echo 'count: ' . Project::count() . PHP_EOL;
