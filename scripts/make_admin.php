<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'amoursedjro47@gmail.com';

$user = App\Models\User::where('email', $email)->first();

if ($user) {
    $user->is_admin = true;
    $user->save();
    echo "UPDATED\n";
} else {
    echo "NOT FOUND\n";
}
