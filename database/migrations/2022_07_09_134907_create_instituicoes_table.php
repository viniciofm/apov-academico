<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituicoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('nome',100);
            $table->string('email',100);
            $table->string('responsavel',100);
            $table->string('telefone_contato',15);
            $table->string('cpf_cnpj', 14);
            $table->string('tipo_documento',4);
            $table->string('logomarca')->nullable();

            $table->uuid('endereco_id')->nullable();
            $table->foreign('endereco_id')->references('id')->on('enderecos');

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
        Schema::dropIfExists('instituicoes');
    }
}
