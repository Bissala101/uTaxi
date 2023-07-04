<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('motorista', function (Blueprint $table) {
            $table->integer("classificacao")->default(0);
            $table->decimal("km_realizado")->default(0);
            $table->decimal("cumprimento")->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('motorista', function (Blueprint $table) {
            $table->dropColumn("classificacao");
            $table->dropColumn("km_realizado");
            $table->dropColumn("cumprimento");
        });
    }
};
