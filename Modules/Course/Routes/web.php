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
    Route::get('/allCbo', 'CursoController@allCbo')->name('allCbo');
    Route::get('/all', 'CursoController@all')->name('all');
    Route::post('/store', 'CursoController@store')->name('store');
    Route::post('/get', 'CursoController@get')->name('get');
    Route::get('/edit/{curso}', 'CursoController@edit')->name('edit');
    Route::post('/update/{curso}', 'CursoController@update')->name('update');
    Route::get('/active/{curso}/{active}', 'CursoController@active')->name('active');
    Route::get('/get-by-id/{curso}', 'CursoController@getById')->name('get-by-id');

    Route::prefix('grade')->name('grade.')->group(function () {
        Route::post('/store', 'GradeController@store')->name('store');
        Route::get('/{curso}/grades/all', 'GradeController@all')->name('all');
        Route::post('/get', 'GradeController@get')->name('get');
        Route::get('/edit/{grade}', 'GradeController@edit')->name('edit');
        Route::post('/update/{grade}', 'GradeController@update')->name('update');
        Route::get('/active/{grade}/{active}', 'GradeController@active')->name('active');
        Route::get('/get-by-id/{grade}', 'GradeController@getById')->name('get-by-id');

        Route::prefix('disciplina')->name('disciplina.')->group(function () {
            Route::get('/all-by-turma/{turma}', 'DisciplinaController@allByTurma')->name('all-by-turma');
            Route::get('/get-by-turma/{turma}', 'DisciplinaController@getByTurma')->name('get-by-turma');
            Route::post('/store', 'DisciplinaController@store')->name('store');
            Route::post('/get', 'DisciplinaController@get')->name('get');
            Route::get('/edit/{disciplina}', 'DisciplinaController@edit')->name('edit');
            Route::post('/update/{disciplina}', 'DisciplinaController@update')->name('update');
        });
    });
});

Route::prefix('admin/turma')->name('admin.turma.')->middleware(['auth'])->group(function() {
    Route::get('/', 'TurmaController@index')->name('index');
    Route::get('/all-by-grade/{grade}', 'TurmaController@allByGrade')->name('all-by-grade');
    Route::post('/store', 'TurmaController@store')->name('store');
    Route::post('/get', 'TurmaController@get')->name('get');
    Route::get('/edit/{turma}', 'TurmaController@edit')->name('edit');
    Route::post('/update/{turma}', 'TurmaController@update')->name('update');
    Route::get('/get-by-id/{turma}', 'TurmaController@getById')->name('get-by-id');
    Route::get('/active/{turma}/{active}', 'TurmaController@active')->name('active');

    Route::prefix('disciplina')->name('disciplina.')->group(function () {
        Route::post('/get', 'TurmaDisciplinaController@get')->name('get');
        Route::post('/update-professor/{turmaDisciplina}', 'TurmaDisciplinaController@updateProfessor')->name('update-professor');
        Route::get('/get-by-id/{turmaDisciplina}', 'TurmaDisciplinaController@getById')->name('get-by-id');
    });
});

Route::prefix('relatorio')->name('relatorio.')->middleware(['auth'])->group(function() {
    Route::get('diario-classe/{turma_disciplina}', 'ReportController@diarioClasse')->name('diario-classe.turma-disciplina');
    Route::get('historico-parcial/{matricula}', 'ReportController@historicoParcial')->name('historico-parcial');
    Route::get('historico-final/{matricula}', 'ReportController@historicoFinal')->name('historico-final');

    Route::prefix('pdf')->name('pdf.')->group(function (){
        Route::get('diario-classe/{turma_disciplina}', 'ReportPdfController@diarioClasse')->name('diario-classe.turma-disciplina');
        Route::get('historico-parcial/{matricula}', 'ReportPdfController@historicoParcial')->name('historico-parcial');
        Route::get('historico-final/{matricula}', 'ReportPdfController@historicoFinal')->name('historico-final');
    });
});
