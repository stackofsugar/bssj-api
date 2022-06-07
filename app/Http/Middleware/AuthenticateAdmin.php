<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateAdmin {
    public function handle(Request $request, Closure $next) {
        if ($request->user()->is_admin == false) {
            return response()->json([
                "status" => [
                    "code" => 401,
                    "message" => "Access denied",
                ],
            ], 401);
        } else {
            return $next($request);
        }
    }
}
