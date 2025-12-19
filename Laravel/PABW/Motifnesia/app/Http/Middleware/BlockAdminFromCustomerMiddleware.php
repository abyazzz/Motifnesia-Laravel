<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockAdminFromCustomerMiddleware
{
    /**
     * Handle an incoming request.
     * Block admin dari mengakses halaman customer public
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user adalah admin, redirect ke admin dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect('/admin/product-management')
                ->with('error', 'Admin tidak dapat mengakses halaman customer.');
        }

        return $next($request);
    }
}
