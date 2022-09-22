<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('rua', 150)->nullable();
            $table->integer('numero')->nullable();
            $table->string('bairro', 150)->nullable();
            $table->string('complemento', 200)->nullable();
            $table->string('cep', 9)->nullable();

            $table->uuid('cidade_id')->nullable();
            $table->foreign('cidade_id')->references('id')->on('cidades');

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
        Schema::dropIfExists('enderecos');
    }
}
