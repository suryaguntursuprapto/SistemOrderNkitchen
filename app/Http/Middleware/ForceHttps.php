<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Force HTTPS in production environment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only enforce HTTPS in production
        if (app()->environment('production')) {
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri(), 301);
            }
        }

        return $next($request);
    }
}
