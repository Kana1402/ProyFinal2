<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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

Route::get('/admin', function () {
    return view('admin');
});

Route::post('/language-switch', function (Request $request) {

    Session::put('locale', $request->locale);

    return redirect()->back();

})->name('language.switch');
