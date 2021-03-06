<?php

Route::namespace('Panel')
    ->middleware(['auth.panel', 'auth'])
    ->prefix('panel')
    ->group(function ($panel) {

        $panel->get('/', 'DashboardController@dashboard')->name("dashboard");
        $panel->get('/iframe', 'DashboardController@iframe')->name("iframe");

        /* panel/profile */
        $panel->put('profile', 'UserController@profileUpdate')->name('users.profileUpdate');
        $panel->get('profile', 'UserController@profile')->name('users.profile');

        /* panel/users */
        $panel->get('users/find', 'UserController@find')->name('users.find');
        $panel->put('users/image', 'UserController@updateImageCrop')->name('users.updateImageCrop');
        $panel->get('users/crop/{id}', 'UserController@imageCrop')->name('users.imageCrop');
        $panel->resource('users', UserController::class);

        /* panel/roles */
        $panel->resource('roles', RoleController::class);

        # rotas para panel
    });
