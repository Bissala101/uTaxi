<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('viagens', function (Blueprint $table) {
            $table->decimal("preco", 32,2)->nullable();
            $table->bigInteger("cliente_id");
        });
    }

    public function down()
    {
        Schema::table('viagens', function (Blueprint $table) {
            $table->dropColumn("preco");
            $table->dropColumn("cliente_id");
        });
    }
};
