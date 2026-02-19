<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = env('ADMIN_EMAIL');

if (! $email) {
    echo "NO_ADMIN_EMAIL\n";
    exit(1);
}

$user = App\Models\User::where('email', $email)->first();

if (! $user) {
    echo "USER_NOT_FOUND\n";
    exit(1);
}

$password = 'Secret123!';

// Use the Hash facade to hash the password
$user->password = \Illuminate\Support\Facades\Hash::make($password);
$user->is_admin = true;
$user->save();

echo "OK\n";
echo "Email: {$user->email}\n";
echo "New password: {$password}\n";
