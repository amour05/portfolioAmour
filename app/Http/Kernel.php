<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's middleware aliases.
     *
     * @var array<string, class-string>
     */
    protected $middlewareAliases = [
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
        'auto_login_admin' => \App\Http\Middleware\AutoLoginAdmin::class,
    ];
}
