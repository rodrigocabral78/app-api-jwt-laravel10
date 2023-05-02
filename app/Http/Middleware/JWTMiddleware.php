<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $bearer = $request->bearerToken();
            $user   = JWTAuth::parseToken()->authenticate();
            // dd($bearer, $user);
            if (!$user) {
                return response()->json(['message' => 'user not found'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                Log::warning('Token is Invalid', ['error' => $e]);

                return response()->json(['status' => 'Token is Invalid'], Response::HTTP_FORBIDDEN);
            } elseif ($e instanceof TokenExpiredException) {
                Log::warning('Token is Expired', ['error' => $e]);

                return response()->json(['status' => 'Token is Expired'], Response::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof TokenBlacklistedException) {
                Log::warning('Token is Blacklisted', ['error' => $e]);

                return response()->json(['status' => 'Token is Blacklisted'], Response::HTTP_BAD_REQUEST);
            }
            Log::warning('Authorization Token not found', ['error' => $e]);

            return response()->json(['status' => 'Authorization Token not found'], Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
