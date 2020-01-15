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
        try {
                $user = JWTAuth::parseToken()->authenticate();
            } 
            catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return response()->json(['status' => 'Token is Invalid']);
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return response()->json(['status' => 'Token is Expired']);
                }else{
                    return response()->json(['status' => 'Authorization Token not found']);
                }
            }
        $user = JWTAuth::parseToken()->authenticate();
        if($user){
            if(!$user->super_user){
                return response()->json(['message' => 'You are not allowed! Cuz your arent ADMIN!!']);  
            }
        }
        return $next($request);
    }
}
