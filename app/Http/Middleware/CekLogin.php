<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekLogin
{
public function handle(Request $request, Closure $next): Response
{
    dd(session()->all());
    if (!session()->has('login')) {
        return redirect('/');
    }

    return $next($request);
}
}
