<?php

use App\Http\Controllers\ApartmentController;
use App\Http\Middleware\AuthMiddleware;
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

    // Pass requests through authenticate middleware
    Route::middleware([AuthMiddleware::class])->group(function() {

        // Check logged in route
        Route::get('/isLoggedIn', 'AuthController@isLoggedIn'); // done

        // Update password
        Route::patch('/updatePassword', 'AuthController@updatePassword'); // done

        // Forgot password route
        Route::post('/forgotPassword', 'AuthController@forgotPassword');

        // Reset password route
        Route::patch('/resetPassword/{token}', 'AuthController@resetPassword');
    });
    
});


// Apartment router
Route::prefix('/apartments') -> group(function() {
  
    // Get list of apartments route


    // Pass requests through authenticate middleware
    Route::middleware([AuthMiddleware::class])->group(function() {
        
        // Create a new apartment route
        Route::post('/', 'ApartmentController@createApartment');

        // Get authticated user's posted apartments
        Route::get('/posted', 'ApartmentController@getPostedApartments');

        // Update apartment's detail route
        Route::patch('/{id}', 'ApartmentController@updateApartment');

        // Activate availability mode route

        // Deactivate availability mode route

        // Delete an apartment mode route

    });

        // Get apartment's detail route
    Route::get('/{id}', 'ApartmentController@getApartment');
    
});


// Comment router
Route::prefix('/comments') -> group(function() {
    
    // Pass requests through authenticate middleware
    Route::middleware([AuthMiddleware::class])->group(function() {
        
    });
    
});

Route::resource('Apartment', 'ApartmentController');
