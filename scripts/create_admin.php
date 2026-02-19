<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'amoursedjro47@gmail.com';
$password = '12507';

use Illuminate\Support\Facades\Hash;

$user = \App\Models\User::updateOrCreate(
    ['email' => $email],
    [
        'name' => 'Amour',
        'password' => Hash::make($password),
        'is_admin' => true,
    ]
);

if ($user && $user->id) {
    echo "OK: user id {$user->id}\n";
    echo "Email: {$email}\n";
    echo "Password: {$password}\n";
    echo "Change the password after first login.\n";
    exit(0);
}

echo "FAILED\n";
exit(1);
