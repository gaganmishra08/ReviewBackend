<?php

use Illuminate\Http\Request;
use Dingo\Api\Routing\Router;
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
$api = app(Router::class);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


$api->version('v1', function (Router $api) {

    $api->group(['prefix' => 'auth'], function(Router $api) {

        $api->post('signup', 'App\\Http\\Controllers\\Api\\SignUpController@signUp');
        $api->post('me', 'App\\Http\\Controllers\\Api\\ApiController@me');
        
    });
    
    $api->post('location', 'App\\Http\\Controllers\\Api\\AppApiController@getLocations');

    // User
    $api->post('searchUser', 'App\\Http\\Controllers\\Api\\UserApiController@searchUser');
    // messages
    $api->post('getMessageThreads', 'App\\Http\\Controllers\\Api\\MessageApiController@getMessageThreads');
    $api->post('sendMessage', 'App\\Http\\Controllers\\Api\\UserApiController@sendMessage');
    $api->post('saveNotificationToken', 'App\\Http\\Controllers\\Api\\UserApiController@saveNotificationToken');
    
    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
});
