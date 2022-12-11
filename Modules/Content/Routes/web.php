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

Route::prefix('content')->name('content.')->middleware(['auth'])->group(function() {
    Route::prefix('aula')->name('aula.')->group(function() {
        Route::post('/store', 'AulaController@store')->name('store');
        Route::post('/get', 'AulaController@get')->name('get');
        Route::get('/edit/{aula}', 'AulaController@edit')->name('edit');
        Route::post('/update/{aula}', 'AulaController@update')->name('update');
        Route::delete('/delete', 'AulaController@delete')->name('delete');
    });
    Route::prefix('atividade')->name('atividade.')->group(function() {
        Route::post('/store', 'AtividadeController@store')->name('store');
        Route::post('/get', 'AtividadeController@get')->name('get');
        Route::get('/edit/{atividade}', 'AtividadeController@edit')->name('edit');
        Route::post('/update/{atividade}', 'AtividadeController@update')->name('update');
        Route::delete('/delete', 'AtividadeController@delete')->name('delete');
    });
});
