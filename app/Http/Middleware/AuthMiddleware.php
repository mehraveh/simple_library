<?php

namespace App\Http\Middleware;

use JWTAuth;
use Closure;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $token = JWTAuth::parseToken()) {
            return abort(403, 'Unauthorized user');
        }
        else
        {
            return $request($next);
        }


    }
}
