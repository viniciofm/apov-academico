<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->uuid('id');
            $table->double('nota')->default(0);

            $table->uuid('matricula_id');
            $table->foreign('matricula_id')->references('id')->on('matriculas');
            $table->uuid('atividade_id');
            $table->foreign('atividade_id')->references('id')->on('atividades');

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
        Schema::dropIfExists('notas');
    }
}
