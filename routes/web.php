<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\SaleEvent;

Route::get('/', function () {
    return redirect()->to('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

#login social

#Route::get('login/github', 'Auth\LoginController@redirectToProvider');
#Route::get('login/github/callback', 'Auth\LoginController@handleProviderCallback');

#Route::get('login/facebook', 'Auth\LoginSocialController@redirectToProvider');
#Route::get('login/facebook/callback', 'Auth\LoginSocialController@handleProviderCallback');

#Route::get('login/social', 'Auth\LoginSocialController@redirectToProvider');#?driver=facebook
#Route::get('login/social/callback', 'Auth\LoginSocialController@handleProviderCallback');

#Route::get('login/{provider}', 'SocialController@redirect');
Route::get('login/{provider}', 'Auth\LoginSocialController@redirect');

#Route::get('login/{provider}/callback','SocialController@Callback');
Route::get('login/{provider}/callback', 'Auth\LoginSocialController@callback');

Route::get('/t', function () {

    //event(new \App\Events\SaleEvent());

    broadcast(new SaleEvent());
    dd('Event Run Successfully.');
});
