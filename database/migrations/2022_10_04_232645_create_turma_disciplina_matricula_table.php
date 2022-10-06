<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurmaDisciplinaMatriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turma_disciplina_matricula', function (Blueprint $table) {
            $table->uuid('id');

            $table->uuid('turma_disciplina_id');
            $table->foreign('turma_disciplina_id')->references('id')->on('turma_disciplinas');
            $table->uuid('matricula_id');
            $table->foreign('matricula_id')->references('id')->on('matriculas');
            $table->string('status', 15);

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
        Schema::dropIfExists('turma_disciplina_matricula');
    }
}
