<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    load_php_files(__DIR__ . '/web');
});
