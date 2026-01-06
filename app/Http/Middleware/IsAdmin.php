<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $adminEmail = env('ADMIN_EMAIL');

        if ($adminEmail) {
            if ($user->email !== $adminEmail) {
                abort(403, 'Accès interdit');
            }
        } else {
            if (! ($user->is_admin ?? false)) {
                abort(403, 'Accès interdit');
            }
        }

        return $next($request);
    }
}
