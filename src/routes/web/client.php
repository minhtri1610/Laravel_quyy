<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'client.', 'namespace' => ('\App\Http\Controllers\Client')], function () {
    Route::get('/', 'HomeController@index')->name('index');

    Route::get('quyy/dang-ky', 'TemporaryController@create')->name('quyy.create');
    Route::post('quyy/dang-ky', 'TemporaryController@store')->name('quyy.store');
    Route::get('quyy/dang-ky-thanh-cong', 'TemporaryController@success')->name('quyy.success');

    Route::get('quyy/tim-kiem', 'QuyYController@search')->name('quyy.search');
    Route::post('quyy/kiem-tra', 'QuyYController@checkDetail')->name('quyy.check-detail');
    Route::get('quyy/not-found', 'QuyYController@notFound')->name('quyy.not-found');
    Route::get('quyy/detail/{uid}', 'QuyYController@detail')->name('quyy.detail');
    Route::get('q/{uid}', 'QuyYController@shortDetail')->name('quyy.short_detail');


    Route::get('/dang-nhap', 'AuthController@login')->name('login');
    Route::post('/dang-nhap', 'AuthController@postLogin')->name('login.post');

    Route::middleware(['roles:client'])->group(function () {
        Route::get('/logout', 'AuthController@logout')->name('logout');
    });
});
