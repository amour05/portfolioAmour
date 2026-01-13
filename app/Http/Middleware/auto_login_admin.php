<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Petit shim compatible PSR-4 pour l'alias "auto_login_admin".
 * Délègue le travail au middleware `AutoLoginAdmin` existant.
 */
class auto_login_admin
{
    public function handle(Request $request, Closure $next)
    {
        $middleware = new AutoLoginAdmin();

        return $middleware->handle($request, $next);
    }
}
