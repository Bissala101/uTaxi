<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('viagens', function (Blueprint $table) {
            $table->id();
            $table->string("posicao_x");
            $table->string("posicao_y");
            $table->string("motorista_id");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('viagens');
    }
};
