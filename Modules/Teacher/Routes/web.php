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

Route::prefix('admin/professor')->name('admin.professor.')->middleware(['auth'])->group(function() {
    Route::get('/', 'ProfessorController@index')->name('index');
    Route::get('/all', 'ProfessorController@all')->name('all');
    Route::post('/store', 'ProfessorController@store')->name('store');
    Route::post('/get', 'ProfessorController@get')->name('get');
    Route::get('/edit/{professor}', 'ProfessorController@edit')->name('edit');
    Route::post('/update/{professor}', 'ProfessorController@update')->name('update');
    Route::get('/active/{professor}/{active}', 'ProfessorController@active')->name('active');
});

Route::prefix('professor')->name('professor.')->middleware(['auth'])->group(function() {
    Route::get('/', 'ProfessorController@myDisciplines')->name('my-disciplines');
    Route::post('/my-disciplines', 'ProfessorController@getMyDisciplines')->name('get-my-disciplines');
});
