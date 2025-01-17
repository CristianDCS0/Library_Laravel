<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message'=>'welcome to server backend with Laravel. by Cristian Cobaxin']);
});

Route::get('storage-link', function () {
    Artisan::call('storage:link');
});
