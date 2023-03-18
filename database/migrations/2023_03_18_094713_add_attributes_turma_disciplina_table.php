<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributesTurmaDisciplinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turma_disciplina_matricula', function (Blueprint $table) {
            $table->integer('faltas')->nullable();
            $table->integer('frequencia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turma_disciplina_matricula', function (Blueprint $table) {
            $table->removeColumn('faltas');
            $table->removeColumn('frequencia');
        });
    }
}
