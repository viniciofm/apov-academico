<?php

use Illuminate\Support\Facades\Route;

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

\Illuminate\Support\Facades\Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['auth']);

Route::prefix('api')->name('api.')->namespace('App\Http\Controllers')->middleware(['auth'])->group(function() {
    Route::get('/cidades/get', 'ApiController@getCidades')->name('cidades.get');
    Route::get('/generos/get', 'ApiController@getGeneros')->name('generos.get');
});

require __DIR__.'/auth.php';
