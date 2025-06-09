<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => ('App\Http\Controllers\Admin')], function () {
    Route::get('/login', 'AuthController@login')->name('login');
    Route::post('/login', 'AuthController@postLogin')->name('login.post');
    Route::get('/reset-password', 'AuthController@resetPassword')->name('pass.reset');
    Route::post('/reset-password', 'AuthController@postResetPassword')->name('pass.reset.post');

    Route::middleware(['roles:admin'])->group(function () {
        Route::get('/', 'DashboardController@index')->name('dashboard');
        Route::get('/logout', 'AuthController@logout')->name('logout');

        Route::group(['prefix' => 'ql-quy-y', 'as' => 'quyy.'], function () {
            Route::get('/', 'QuyYController@index')->name('index');
            Route::get('/danh-sach-cho-duyet', 'QuyYController@list')->name('list');
            Route::get('/them-phat-tu', 'QuyYController@create')->name('create');
            Route::post('/them-phat-tu', 'QuyYController@store')->name('store');
            Route::get('xoa/{uid?}', 'QuyYController@destroyUser')->name('destroy');

            Route::get('/chi-tiet-phat-tu/{uid}', 'QuyYController@detail')->name('detail');
            Route::post('/chi-tiet-phat-tu/{uid}', 'QuyYController@update')->name('update');

            Route::get('/nhap-hang-loat', 'QuyYController@import')->name('view');
            Route::post('/nhap-hang-loat', 'QuyYController@postImport')->name('import');

            Route::post('verify/{id?}', 'QuyYController@verify')->name('verify');
            Route::post('delete/{id?}', 'QuyYController@destroy')->name('delete');

            Route::post('gererate-names', 'QuyYController@gererateName')->name('gererate');
        });
    });
});


