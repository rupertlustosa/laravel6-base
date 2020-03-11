<?php

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

Route::post('login', 'Api\PassportController@login');
Route::get('unauthenticated', 'Api\PassportController@unAuthenticated')->name('unAuthenticated');
Route::post('register', 'Api\PassportController@register');

Route::get('cities', 'Api\ApiPostalCodeController@findCity');
Route::get('cities/find', 'Api\ApiCityController@find')->name('cities.find');

Route::middleware('auth:api')->namespace('Api')->group(function ($api) {

    $api->get('home', 'ApiHomeController@index');
    $api->get('me', 'ApiUserController@me');
    $api->get('settings', 'ApiSyncController@settings');
    $api->get('sales-to-pointing', 'ApiSyncController@getSalesToPointing');
    $api->put('save/sale/{sale}', 'ApiSyncController@save');
});
