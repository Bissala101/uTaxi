<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('carros', function (Blueprint $table) {
            $table->id();
            $table->string("tipo");
            $table->integer("velocidade_media");
            $table->integer("posicao_y")->default(1);
            $table->integer("posicao_x")->default(1);
            $table->bigInteger("motorista_id");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carros');
    }
};
