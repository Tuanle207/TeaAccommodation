<?php

namespace App\Http\Middleware;

use App\Apartment;
use Closure;

class CheckApartmentExistenceMiddleware
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
        $apartment = Apartment::find($req->route('id'));
        
        if (!$apartment) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Phòng trọ này không tồn tại'
            ], 404);
        }

        // attach apartment to req
        $req->request->add(['apartment' => $apartment]);
        
        return $next($req);
    }
}
