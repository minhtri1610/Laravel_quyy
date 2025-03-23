<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'client.', 'namespace' => ('\App\Http\Controllers\Client')], function () {
    Route::get('/', 'HomeController@index')->name('index');

    Route::get('quyy/dang-ky', 'TemporaryController@create')->name('quyy.create');
    Route::post('quyy/dang-ky', 'TemporaryController@store')->name('quyy.store');
    Route::get('quyy/dang-ky-thanh-cong', 'TemporaryController@success')->name('quyy.success');
    
    Route::get('quyy/tim-kiem', 'QuyYController@search')->name('quyy.search');

    Route::get('/dang-nhap', 'AuthController@login')->name('login');
    Route::post('/dang-nhap', 'AuthController@postLogin')->name('login.post');

    Route::middleware(['roles:client'])->group(function () {
        Route::get('/logout', 'AuthController@logout')->name('logout');
    });
});
