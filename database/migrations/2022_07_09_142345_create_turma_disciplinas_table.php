<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurmaDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turma_disciplinas', function (Blueprint $table) {
            $table->uuid('id');

            $table->uuid('turma_id');
            $table->foreign('turma_id')->references('id')->on('turmas');
            $table->uuid('professor_id');
            $table->foreign('professor_id')->references('id')->on('professores');
            $table->uuid('disciplina_id');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');

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
        Schema::dropIfExists('turma_disciplinas');
    }
}
