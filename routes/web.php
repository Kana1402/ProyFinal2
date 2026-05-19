<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/publication', function () {
    return view('publication');
});

Route::get('/we', function () {
    return view('we');
});

Route::get('/services', function () {
    return view('services');
});
