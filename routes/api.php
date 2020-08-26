<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Users router
Route::prefix('/users') -> group(function() {

    // Signup route
    Route::post('/signup', 'AuthController@signup'); // done
    // Login route
    Route::post('/login', 'AuthController@login'); // done
    // Logout route
    Route::get('/logout', 'AuthController@logout'); // done
    // Check logged in route
    Route::get('/isLoggedIn', 'AuthController@isLoggedIn'); // done
    // Forgot password route
    Route::post('/forgotPassword', 'AuthController@forgotPassword');
    // Reset password route
    Route::patch('/resetPassword/{token}', 'AuthController@resetPassword');
});



Route::resource('Apartment', 'ApartmentController');
