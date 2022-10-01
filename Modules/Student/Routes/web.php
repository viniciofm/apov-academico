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

Route::prefix('admin/aluno')->name('admin.aluno.')->middleware(['auth'])->group(function() {
    Route::get('/', 'AlunoController@index')->name('index');
    Route::post('/store', 'AlunoController@store')->name('store');
    Route::post('/get', 'AlunoController@get')->name('get');
    Route::get('/edit/{aluno}', 'AlunoController@edit')->name('edit');
    Route::post('/update/{aluno}', 'AlunoController@update')->name('update');
    Route::get('/active/{aluno}/{active}', 'AlunoController@active')->name('active');
});
