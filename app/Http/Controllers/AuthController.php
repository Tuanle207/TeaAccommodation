<?php

namespace App\Http\Controllers;

use App\Address;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Support\Facades\Response;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Utils\UserModificationHanlder;


class AuthController extends Controller {

    public function signup(SignupRequest $req) {

        // create a new record of User
        $user = null;

        // handle request body to store user's infomations
        $body = $req->all();
        $filter = ['email', 'password', 'passwordConfirm', 'name', 'phoneNumber', 'role'];

        $newUser = UserModificationHanlder::saveUser($user, $body, $filter);
        
        // Response cookie
        return $this->responseCookie($newUser, 201);
    }

    public function login(LoginRequest $req) {
        // Get email, password for login
        ['email' => $email, 'password' => $password] = $req->input();

        // Get user information includes password.
        $user = User::fields()->where('email', $email)->addSelect('password')->first();

        // Check user still exists and password is correct?
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Email hoặc mật khẩu không hợp lệ'
            ], 401);
        }

        // Hide user password
        $user->makeHidden(['password']);
        
        // Attach address
        if ($user->address) {
            $address = Address::find($user->address);
            unset($address->id);
            $user->address = $address;
        }

        // Response cookie
        return $this->responseCookie($user, 200);
    }

    public function logout(Request $req) {
        return $this->responseCookie(null, 200);
    }

    public function isLoggedIn(Request $req) {
        $user = $req->user;

        // Attach address
        if ($user->address) {
            $address = Address::find($user->address);
            unset($address->id);
            $user->address = $address;
        }

        // response json includes user's data
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
        
    }

    public function updatePassword(UpdatePasswordRequest $req) {
        // Get required fields for updating password from req body
        [
            "currentPassword" => $currentPassword, 
            "password" => $password, 
            "passwordConfirm" => $passwordConfirm
        ] = $req->input(); 

        // Check password
        $user = $req->user;
        
        if (!Hash::check($currentPassword, $user->password)) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Mật khẩu không chính xác'
            ], 401);
        }

        // OK -> continue updating password
        $_user = User::find($user->id);
        $_user->password = $password;
        $_user->save();
        
        // Response success message
        return $this->responseCookie($_user, 200);
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
            env('APP_ENV') === 'production' ? true : false
        );

        // Return json response includes cookies
        return Response::json([
            'status' => 'success',
            'token' => $token,
            'data' => $user
        ], $statusCode)->withCookie($cookie);
    }
}
