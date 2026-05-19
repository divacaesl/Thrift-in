<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // If 'super_admin' is required, strict check
        if (in_array('super_admin', $roles) && $user->role !== 'super_admin') {
            abort(403, 'Akses Ditolak. Anda bukan Super Admin.');
        }

        // If multiple roles allowed
        if (!empty($roles) && !in_array($user->role, $roles) && $user->role !== 'super_admin') {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
