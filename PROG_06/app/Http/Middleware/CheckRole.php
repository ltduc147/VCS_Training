<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PDO;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user_role = session('user')['role'];

        if (!$user_role || $user_role !== $role){
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
