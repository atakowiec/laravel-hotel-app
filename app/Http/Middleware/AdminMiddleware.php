<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if(auth()->check() && auth()->user()->admin) {
            return $next($request);
        }

        return redirect()->route('home')->with(['message' => 'Brak dostępu.']);
    }
}
