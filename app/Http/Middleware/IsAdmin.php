<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Vérifie si l'utilisateur est admin
        if (!Auth::user()->is_admin) {
            abort(403, 'Accès interdit');
        }

        return $next($request);
    }
}
