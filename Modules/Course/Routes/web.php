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

Route::prefix('admin/curso')->name('admin.curso.')->middleware(['auth'])->group(function() {
    Route::get('/', 'CursoController@index')->name('index');
    Route::post('/store', 'CursoController@store')->name('store');
    Route::post('/get', 'CursoController@get')->name('get');
    Route::get('/edit/{curso}', 'CursoController@edit')->name('edit');
    Route::post('/update/{curso}', 'CursoController@update')->name('update');
    Route::get('/active/{curso}/{active}', 'CursoController@active')->name('active');
    Route::get('/get-by-id/{curso}', 'CursoController@getById')->name('get-by-id');

    Route::prefix('grade')->name('grade.')->group(function () {
        Route::post('/store', 'GradeController@store')->name('store');
        Route::post('/get', 'GradeController@get')->name('get');
        Route::get('/edit/{grade}', 'GradeController@edit')->name('edit');
        Route::post('/update/{grade}', 'GradeController@update')->name('update');
        Route::get('/active/{grade}/{active}', 'GradeController@active')->name('active');
        Route::get('/get-by-id/{grade}', 'GradeController@getById')->name('get-by-id');

        Route::prefix('disciplina')->name('disciplina.')->group(function () {
            Route::post('/store', 'DisciplinaController@store')->name('store');
            Route::post('/get', 'DisciplinaController@get')->name('get');
            Route::get('/edit/{disciplina}', 'DisciplinaController@edit')->name('edit');
            Route::post('/update/{disciplina}', 'DisciplinaController@update')->name('update');
        });
    });
});
