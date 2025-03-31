<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->route('userId');

        if (!User::where('id', $userId)->exists()) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
    
        return $next($request);
    }
}
