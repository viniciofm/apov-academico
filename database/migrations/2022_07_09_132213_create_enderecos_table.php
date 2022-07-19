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
            $table->string('rua',150);
            $table->integer('numero')->nullable();
            $table->string('bairro',150);
            $table->string('complemento',200)->nullable();
            $table->integer('cep');

            $table->uuid('cidade_id');
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
