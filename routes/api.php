<?php

use App\Http\Controllers\ApartmentController;
use App\Http\Middleware\AuthenticationMiddleware;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\AuthorizationMiddleware;
use App\Http\Middleware\CheckApartmentExistenceMiddleware;
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
| !!! WARNING
|
|        NOT ONCE ...... GIVE A TRY TO MODIFY THE ORDER OF THE ROUTES!!!
|        -> IT WILL DESTROY APPLICATION BEHAVIOR!!!!!!!!!!!
|        *** A strongly important CAUTION from Tuanle207
| !!! WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING    
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
    Route::middleware([AuthenticationMiddleware::class])->group(function() {

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

/*
| !!! WARNING
|
|        NOT ONCE ...... GIVE A TRY TO MODIFY THE ORDER OF ROUTE!!!
|        -> IT WILL DESTROY APPLICATION BEHAVIOR!!!!!!!!!!!
|        * A strongly important CAUTION from Tuanle207
| !!! WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING  
*/

// Apartment router
Route::prefix('/apartments') -> group(function() {
  
    // Get list of apartments route


    // Pass requests through authenticate middleware
    Route::middleware([AuthenticationMiddleware::class])->group(function() {
        
        // Create a new apartment route
        Route::post('/', 'ApartmentController@createApartment'); // done

        // Get authticated user's posted apartments
        Route::get('/posted', 'ApartmentController@getPostedApartments'); // done

        // Pass requests through check apartment existence? middleware
        Route::middleware([CheckApartmentExistenceMiddleware::class])->group(function() {
            
            // Pass requests through authorization middleware
            Route::middleware([AuthorizationMiddleware::class])->group(function() {
                // Update apartment's detail route
                Route::patch('/{id}', 'ApartmentController@updateApartment'); // done

                // Activate availability mode route
                Route::patch('/{id}/activate', 'ApartmentController@activateAvailabilityMode'); // done

                // Deactivate availability mode route
                Route::patch('/{id}/deactivate', 'ApartmentController@deactivateAvailabilityMode');// done

                // Delete an apartment mode route
                Route::delete('/{id}', 'ApartmentController@deleteApartment');
            });
           
            // Create a new comment about an apartment route
            Route::post('/{id}/comments', 'CommentController@createComment');

            //Delete a comment about an apartment route
            Route::delete('/{id}/comments/{comment_id}', 'CommentController@deleteComment');

            // Get list of comments about an apartment route
            Route::get('/{id}/comments', 'CommentController@getComments');
        });
    });

    // Get apartment's detail route
    Route::get('/{id}', 'ApartmentController@getApartment')
    ->middleware([CheckApartmentExistenceMiddleware::class]); // done

});


/*
| !!! WARNING
|
|        NOT ONCE ...... GIVE A TRY TO MODIFY THE ORDER OF ROUTE!!!
|        -> IT WILL DESTROY APPLICATION BEHAVIOR!!!!!!!!!!!
|        * A strongly important CAUTION from Tuanle207
| !!! WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING  
*/

// Comment router
Route::prefix('/comments') -> group(function() {
    
    // Pass requests through authenticate middleware
    Route::middleware([AuthenticationMiddleware::class])->group(function() {

    });
    
});

Route::resource('Apartment', 'ApartmentController');

/*
| !!! WARNING
|
|        NOT ONCE ...... GIVE A TRY TO MODIFY THE ORDER OF ROUTE!!!
|        -> IT WILL DESTROY APPLICATION BEHAVIOR!!!!!!!!!!!
|        * A strongly important CAUTION from Tuanle207
| !!! WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING  
*/

Route::get('/test', function() {
    return 1;
});

