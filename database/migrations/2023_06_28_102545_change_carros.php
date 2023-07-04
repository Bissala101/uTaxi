<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('carros', function (Blueprint $table) {
            $table->string("nome", 1000)->after("id");
            $table->decimal("preco", 32, 2);
            $table->string("modelo", 1000)->after("nome");
        });
    }

    public function down()
    {
        Schema::table('carros', function (Blueprint $table) {
            $table->dropColumn("nome");
            $table->dropColumn("modelo");
            $table->dropColumn("preco");
        });
    }
};
