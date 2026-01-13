<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLoginAdmin
{
    /**
     * Si `AUTO_LOGIN_ADMIN` est vrai dans l'env, connecte automatiquement l'admin par email.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! env('AUTO_LOGIN_ADMIN', false)) {
            return $next($request);
        }

        if (Auth::check()) {
            return $next($request);
        }

        $email = env('AUTO_LOGIN_EMAIL', 'amoursedjro47@gmail.com');
        $user = \App\Models\User::where('email', $email)->first();

        if ($user) {
            // login with remember = true so cookie is set
            Auth::login($user, true);
        }

        return $next($request);
    }
}
