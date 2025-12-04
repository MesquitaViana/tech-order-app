<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('customer_id')) {
            return redirect()
                ->route('customer.login')
                ->with('error', 'Faça login para acessar seus pedidos.');
        }

        return $next($request);
    }
}
