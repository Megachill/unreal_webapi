<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return response()->json(['Hello Unreal.'], 200);
});

/**
 * AUTH related routes
 */
$app->group(['namespace' => 'Auth'], function($app) {
    $app->post('/auth/login', 'AuthController@login');
    $app->post('/auth/register', 'AuthController@register');
    $app->post('/auth/logout', 'AuthController@logout');
});

/**
 * Example of routes where the user MUST be authenticated, if he isn't we will not grant access
 */
$app->group(['middleware' => 'auth:api'], function($app) {

    $app->get('/members','Members\MemberController@index');

});