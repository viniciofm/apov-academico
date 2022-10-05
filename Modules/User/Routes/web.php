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

Route::prefix('admin/usuario')->name('admin.usuario.')->middleware(['auth'])->group(function() {
    Route::get('/', 'UserController@index')->name('index');
    Route::post('/store', 'UserController@store')->name('store');
    Route::post('/get', 'UserController@get')->name('get');
    Route::get('/edit/{user}', 'UserController@edit')->name('edit');
    Route::get('/edit-user', 'UserController@editUser')->name('edit-user');
    Route::post('/update/{user}', 'UserController@update')->name('update');
    Route::get('/block/{user}/{block}', 'UserController@block')->name('block');
});

Route::prefix('/')->name('usuario.')->middleware(['auth'])->group(function() {
    Route::get('/meus-dados', 'UserController@meusDados')->middleware(['auth'])->name('meus-dados');
    Route::get('/mudar-senha', 'UserController@editarSenha')->middleware(['auth'])->name('mudar-senha');
    Route::post('/update-password', 'UserController@updatePassword')->middleware(['auth'])->name('update-password');
});
