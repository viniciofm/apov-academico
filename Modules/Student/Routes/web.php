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
    Route::get('/all', 'AlunoController@all')->name('all');
    Route::post('/store', 'AlunoController@store')->name('store');
    Route::post('/get', 'AlunoController@get')->name('get');
    Route::get('/edit/{aluno}', 'AlunoController@edit')->name('edit');
    Route::post('/update/{aluno}', 'AlunoController@update')->name('update');
    Route::get('/active/{aluno}/{active}', 'AlunoController@active')->name('active');
});

Route::prefix('admin/matricula')->name('admin.matricula.')->middleware(['auth'])->group(function() {
    Route::get('/', 'MatriculaController@index')->name('index');
    Route::post('/store', 'MatriculaController@store')->name('store');
    Route::post('/get', 'MatriculaController@get')->name('get');
    Route::post('/update/{matricula}', 'MatriculaController@update')->name('update');
    Route::get('/get-by-id/{matricula}', 'MatriculaController@getById')->name('get-by-id');
    Route::post('/get-disciplinas', 'MatriculaController@getDisciplinas')->name('get-disciplinas');
    Route::post('/get-alunos', 'MatriculaController@getAlunos')->name('get-alunos');
    Route::post('/disciplinas/store', 'MatriculaController@storeDisciplinas')->name('store-disciplinas');
    Route::delete('/disciplinas/delete', 'MatriculaController@deleteDisciplina')->name('delete-disciplina');
});

Route::prefix('aluno')->name('aluno.')->middleware(['auth'])->group(function() {
    Route::get('/', 'AlunoController@alunoIndex')->name('index');
    Route::post('/get/matriculas', 'AlunoController@getMatriculas')->name('get.matriculas');
});
