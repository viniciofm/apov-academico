<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->uuid('id');

            $table->uuid('curso_id');
            $table->foreign('curso_id')->references('id')->on('cursos');
            $table->uuid('turma_id');
            $table->foreign('turma_id')->references('id')->on('turmas');
            $table->uuid('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->uuid('aluno_id');
            $table->foreign('aluno_id')->references('id')->on('alunos');

            $table->double('nota_final', 10, 2)->nullable();
            $table->string('status', 15);
            $table->string('conceito', 15);

            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matriculas');
    }
}
