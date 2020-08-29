<?php

namespace App\Http\Middleware;

use App\Apartment;
use Closure;

class AuthorizationMiddleware
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
        // check if this apartment exists?
        $apartment = $req->input('apartment');

        // check if this apartment belongs to this user?
        $user = $req->input('user');
        if ($apartment->postedBy != $user->id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Chỉ người đăng mới có thể thực hiện thao tác này'
            ], 403);
        }

        return $next($req);
    }
}
