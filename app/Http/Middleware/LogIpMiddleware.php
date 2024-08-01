<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {        
        $ipAddress = $request->ip();

        // Catat alamat IP ke dalam log
        Log::info('Accessed IP:', ['ip' => $ipAddress]);

        return $next($request);
                $ipAddress = $request->ip();

        // Catat alamat IP ke dalam log
        Log::info('Accessed IP:', ['ip' => $ipAddress]);

        return $next($request);
    }
}
