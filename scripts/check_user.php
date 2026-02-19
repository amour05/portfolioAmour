<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;

$email = $argv[1] ?? 'amoursedjro47@gmail.com';
$password = $argv[2] ?? null;

$user = \App\Models\User::where('email', $email)->first();

if (! $user) {
    echo "NOT FOUND\n";
    exit(2);
}

echo "FOUND: id={$user->id}, email={$user->email}, is_admin=" . ($user->is_admin ? '1' : '0') . "\n";

if ($password === null) {
    echo "No password provided to verify.\n";
    exit(0);
}

if (Hash::check($password, $user->password)) {
    echo "PASSWORD OK\n";
    exit(0);
} else {
    echo "PASSWORD MISMATCH\n";
    exit(3);
}
