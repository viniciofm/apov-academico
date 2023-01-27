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

Route::prefix('admin/empresa')->name('admin.empresa.')->middleware(['auth'])->group(function() {
    Route::get('/', 'EmpresaController@index')->name('index');
    Route::get('/all', 'EmpresaController@all')->name('all');
    Route::post('/store', 'EmpresaController@store')->name('store');
    Route::post('/get', 'EmpresaController@get')->name('get');
    Route::get('/edit/{empresa}', 'EmpresaController@edit')->name('edit');
    Route::post('/update/{empresa}', 'EmpresaController@update')->name('update');
    Route::get('/active/{empresa}/{active}', 'EmpresaController@active')->name('active');
});

Route::prefix('empresa')->name('empresa.')->middleware(['auth'])->group(function() {
    Route::get('/', 'EmpresaController@indexEmpresa')->name('index');
    Route::get('/edit', 'EmpresaController@editEmpresa')->name('edit');
});
