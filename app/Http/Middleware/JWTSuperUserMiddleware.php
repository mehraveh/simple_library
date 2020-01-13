<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class JWTSuperUserMiddleware
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
        $user = JWTAuth::parseToken()->authenticate();
        if($user){
            if(!$user->super_user){
                return response()->json(['message' => 'You are not allowed! Cuz your arent ADMIN!!']);  
            }
        }
        return $next($request);
    }
}
