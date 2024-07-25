<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string
    // {
    //     // return response()->json([$request->user()]);
    //     return $request->expectsJson() ? null : route('user.login');
    // }

    // protected function authenticate($request, array $guards)
    // {
    //     foreach ($guards as $guard) {
    //         if (Auth::guard($guard)->guest()) {
    //             return response()->json([$request]);
    //         } else {
    //             return response()->json(['message' => 'user unathenticated', $request->user()], 401);
    //         }
    //     }
    // }

    // public function handle($request, Closure $next, ...$guards)
    // {
    //     return $this->authenticate($request, $guards);
    // }
}
