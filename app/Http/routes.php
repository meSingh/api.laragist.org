<?php


/*
| Exception Types:
|--------------------------------------------------------------------------
| Dingo\Api\Exception\DeleteResourceFailedException
| Dingo\Api\Exception\ResourceException
| Dingo\Api\Exception\StoreResourceFailedException
| Dingo\Api\Exception\UpdateResourceFailedException
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Landing Page for API
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


 
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

ApiRoute::version('v1', [ 
        'prefix' => 'v1', 
        'namespace' => 'GistApi\Http\Controllers\v1' 
    ], function () {
    

        /*
        |--------------------------------------------------------------------------
        | Routes for everything related to authentication
        |--------------------------------------------------------------------------
        */
        ApiRoute::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

            // Register a new user
            ApiRoute::post('register', 'AuthController@register');

        });


        /*
        |--------------------------------------------------------------------------
        | Routes for everything related to users
        |--------------------------------------------------------------------------
        */
        ApiRoute::group(['prefix' => 'users', 'namespace' => 'Users'], function () {

            // Confirm user account email link
            ApiRoute::get('{id}/confirm/{code}', 'ProfileController@confirm');
            

        });


        // Confirm user account email link
        ApiRoute::get('/', 'PackageController@index');
        ApiRoute::post('submit', 'PackageController@store');
        ApiRoute::post('package/{user}/{name}', 'PackageController@show');


}); // Version-1 group





