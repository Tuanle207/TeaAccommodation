<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use function GuzzleHttp\json_decode;

class AuthController extends Controller {

    public function signup(SignupRequest $req) {

        // Get required user's infos from request body
        $user = [
            'email' => $req->input('email'),
            'password' => $req->input('password'),
            'passwordConfirm' => $req->input('passwordConfirm'),
            'name' => $req->input('name'),
            'phoneNumber' => $req->input('phoneNumber')       
        ];
        if ($req->input('address')) {
            $user['address'] = $req->input('address');
        }
        //Create new user using that infos
        $newUser = User::create($user);   

        $newUser->makeVisible(['address', 'photo', 'role']);
        // Response cookie
        return $this->responseCookie($newUser, 201);
    }

    public function login(LoginRequest $req) {
        // Get email, password for login
        ['email' => $email, 'password' => $password] = $req->input();

        // Get user information includes password.
        $user = User::fields()->where('email', $email)->addSelect('password')->first();
        
        // Check user still exists?
        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'message' => 'This user has not been available'
            ], 404);
        }

        // Select additionally password as well
        $user->makeVisible(['password']);

        // Check password
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Invalid email or password'
            ], 401);
        }

        // Hide user password
        $user->makeHidden(['password']);

        // Response cookie
        return $this->responseCookie($user, 200);
    }

    public function logout(Request $req) {
        return $this->responseCookie(null, 200);
    }

    public function isLoggedIn(Request $req) {
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
        $user = User::fields()->find($id);

        // Check if user still exists
        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'message' => 'This user is not available'
            ], 404);
        }

        // response json includes user's data
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
        
    }
    public function forgotPassword(Request $req) {
        return response()->json([
            'status' => 'fail',
            'message' => 'This api route is comming soon'
        ], 404);
    }

    public function resetPassword(Request $req) {
        return response()->json([
            'status' => 'fail',
            'message' => 'This api route is comming soon'
        ], 404);
    }

    private function signToken($id) {
        // Create factory includes required fields in payload
        $factory = JWTFactory::customClaims([
            'sub' => $id ? $id : 'anhtuandeptrai_logoutsecretkey',
            'iss' => env('JWT_SECRET'),
            'exp' => $id ? Carbon::now()->timestamp + env('JWT_TTL') * 86400 : Carbon::now()->timestamp + 2
            ]);
        // Create payload
        $payload = $factory->make();
        // Create and return token includes payload
        $token = (string) JWTAuth::encode($payload);
        return $token;
    }

    private function responseCookie($user, $statusCode) {
        // Create jwt token
        $token = $user ?  $this->signToken($user->id) : $this->signToken(null);

        // Create cookie
        $cookie = Cookie::make(
            'jwt', 
            $token, 
            !$user ? 2 : env('JWT_COOKIES_EXPIRES_IN') * 864000,
            '/',
            null, 
            null, 
            env('APP_ENV') == 'production' ? true : false
        );

        // Return json response includes cookies
        return Response::json([
            'status' => 'success',
            'token' => $token,
            'data' => $user
        ], $statusCode)->withCookie($cookie);
    }
}
