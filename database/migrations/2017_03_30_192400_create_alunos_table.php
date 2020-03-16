<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlunosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('cell')->unique();
            $table->string('cep');
            $table->string('curso_info');
            $table->string('curso_ingl');
            $table->string('escolaridade');
            $table->string('in_teste');
            $table->string('rua');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('uf');
            $table->string('ibge');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alunos');

    }
}
