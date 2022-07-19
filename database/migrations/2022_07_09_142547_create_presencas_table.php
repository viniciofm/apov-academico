<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresencasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presencas', function (Blueprint $table) {
            $table->uuid('id');
            $table->boolean('presente')->default(true);

            $table->uuid('matricula_id');
            $table->foreign('matricula_id')->references('id')->on('matriculas');
            $table->uuid('aula_id');
            $table->foreign('aula_id')->references('id')->on('aulas');

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
        Schema::dropIfExists('presencas');
    }
}
