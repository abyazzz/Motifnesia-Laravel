<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login, redirect berdasarkan role
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/product-management');
            }
            return redirect()->route('customer.home');
        }

        return $next($request);
    }
}
