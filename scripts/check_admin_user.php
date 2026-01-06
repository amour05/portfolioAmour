<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = env('ADMIN_EMAIL');

if (! $email) {
    echo "NO_ADMIN_EMAIL\n";
    exit(0);
}

$user = App\Models\User::where('email', $email)->first();

if ($user) {
    echo "FOUND\n";
    echo "email: {$user->email}\n";
    echo "is_admin: " . (($user->is_admin) ? '1' : '0') . "\n";
} else {
    echo "NOT_FOUND\n";
}
