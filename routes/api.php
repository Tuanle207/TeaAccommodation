<?php

use App\Http\Middleware\AuthenticationMiddleware;
use App\Http\Middleware\AuthorizationMiddleware;
use App\Http\Middleware\CheckApartmentExistenceMiddleware;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
* API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*
!!! WARNING

        ! NOT ONCE ...... GIVE A TRY TO MODIFY THE ORDER OF ROUTES!!!
        !-> IT WILL DESTROY APPLICATION BEHAVIOR!!!!!!!!!!!
        * A strongly important CAUTION from Tuanle207
!!! WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING  
*/


/*
    * User router
*/
Route::prefix('/users') -> group(function() {

    // * Signup route
    Route::post('/signup', 'AuthController@signup'); // done
    
    // * Login route
    Route::post('/login', 'AuthController@login'); // done

    // * Logout route
    Route::get('/logout', 'AuthController@logout'); // done

    // * Pass requests through authenticate middleware
    Route::middleware([AuthenticationMiddleware::class])->group(function() {

        // * Check logged in route
        Route::get('/isLoggedIn', 'AuthController@isLoggedIn'); // done

        // * Update password
        Route::patch('/updatePassword', 'AuthController@updatePassword'); // done

        // * Forgot password route
        Route::post('/forgotPassword', 'AuthController@forgotPassword');

        // * Reset password route
        Route::patch('/resetPassword/{token}', 'AuthController@resetPassword');

        // * Get user profile
        Route::get('/profile', 'UserController@getUserProfile'); // done

        // * Update user profile
        Route::post('/profile/edit', 'UserController@updateUserProfile');
    });
    
});

/*
!!! WARNING

        ! NOT ONCE ...... GIVE A TRY TO MODIFY THE ORDER OF ROUTES!!!
        !-> IT WILL DESTROY APPLICATION BEHAVIOR!!!!!!!!!!!
        * A strongly important CAUTION from Tuanle207
!!! WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING  
*/

/*
    * Apartment router
*/
Route::prefix('/apartments') -> group(function() {
  
    // * Get list of apartments route
    Route::get('/', 'ApartmentController@getApartments');


    // * Pass requests through authenticate middleware
    Route::middleware([AuthenticationMiddleware::class])->group(function() {
        
        // * Create a new apartment route
        Route::post('/', 'ApartmentController@createApartment'); // done

        // * Get authticated user's posted apartments
        Route::get('/posted', 'ApartmentController@getPostedApartments'); // done

        
        // * Pass requests through check apartment existence? middleware
        Route::middleware([CheckApartmentExistenceMiddleware::class])->group(function() {

        
            // * Pass requests through authorization middleware
            Route::middleware([AuthorizationMiddleware::class])->group(function() {

                // * Update apartment's detail route
                Route::post('/{id}', 'ApartmentController@updateApartment');

                // * Activate availability mode route
                Route::patch('/{id}/activate', 'ApartmentController@activateAvailabilityMode'); // done

                // * Deactivate availability mode route
                Route::patch('/{id}/deactivate', 'ApartmentController@deactivateAvailabilityMode');// done

                // * Delete an apartment mode route
                Route::delete('/{id}', 'ApartmentController@deleteApartment'); // done
            });
           
            /*
                * Comment route
            */
        
            // * Get list of comments about an apartment route
            Route::get('/{id}/comments', 'CommentController@getComments'); // done
            
            Route::prefix('/{id}/comments')->group(function() {
                // * Create a new comment about an apartment route
                Route::post('/', 'CommentController@createComment'); // done

                // * Delete a comment about an apartment route
                Route::delete('/{comment_id}', 'CommentController@deleteComment'); // done

            });
            
            /*
                * Rating route
            */
            Route::prefix('/{id}/ratings')->group(function() {

                // * Get user's rating for apartment
                Route::get('/', 'RatingController@getRating');

                // * Ranking/ change ranking an apartment route
                Route::post('/', 'RatingController@createOrUpdateRating');

                // *Cancel ranking an apartment route
                Route::delete('/', 'RatingController@deleteRating');
            });

        });
    });

    // Get apartment's detail route
    Route::get('/{id}', 'ApartmentController@getApartment')
    ->middleware([CheckApartmentExistenceMiddleware::class]); // done

});


/*
!!! WARNING

        ! NOT ONCE ...... GIVE A TRY TO MODIFY THE ORDER OF ROUTES!!!
        !-> IT WILL DESTROY APPLICATION BEHAVIOR!!!!!!!!!!!
        * A strongly important CAUTION from Tuanle207
!!! WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING WARNING  
*/

Route::any('test', function(Request $req) {

    $obj = null;
    $obj = new User; /* 
    ! 'address' is a property of User model */

    $address = $obj->address === null ? 9999 : 0;   

    $obj->address = 9999;

    return response()->json([
        'test $obj->address === null?' => $obj->address === null,
        'obj' => $obj,
        'test' => addslashes(3432)
    ], 200);

});


// * There is no api route left
Route::any('/{any}', function($any = null) {
    return response()->json([
        'status' => 'fail',
        'message' => 'This api route is not available'
    ], 404);
})->where('any', '.*');

