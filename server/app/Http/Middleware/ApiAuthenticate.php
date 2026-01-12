<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if the user is authenticated
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => 'Token expired or invalid'
                ], 401);
            }

            return $next($request);
        } catch (AuthenticationException $e) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'Token expired or invalid'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Authentication error',
                'message' => 'Unable to authenticate user'
            ], 500);
        }
    }
}
