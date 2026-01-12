<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$u = User::where('is_admin', true)->first();
if ($u) {
    echo 'id: ' . $u->id . ', email: ' . $u->email . PHP_EOL;
} else {
    echo 'no admin' . PHP_EOL;
}
