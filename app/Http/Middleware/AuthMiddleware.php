<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($req, Closure $next)
    {
        // There existing a cookie ?
        if (!$req->cookie('jwt')) {
            // User have not logged in yet
            return response()->json([
                'status' => 'fail',
                'message' => 'You haven\'t logged in yet! Please log in to continue'
            ]);
        }
        // Get token from cookie
        $token = $req->cookie('jwt');
            
        // Decode token
        try {
            $decoded = JWTAuth::setToken($token)->getPayload();
            $decoded = json_decode($decoded);
            // Check if token has expired, based on time expire from payload in decoded token
        } catch (TokenExpiredException $e) { 
            return response()->json([
                'status' => 'fail',
                'message' => 'The token is invalid or has expired!'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'The token is invalid or has expired!'
            ], 401);
        }

        // Get user based on id from payload in decoded token
        $id = $decoded->sub;
        $user = User::fields()->addSelect('password')->find($id);
        // Check if user still exists
        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'message' => 'This user is not available'
            ], 404);
        }

        // Attach user to req
        $req->request->add(['user' => $user]);
        return $next($req);
    }
}
