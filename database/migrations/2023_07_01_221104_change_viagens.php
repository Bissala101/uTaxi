<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('viagens', function (Blueprint $table) {
            $table->decimal("km")->nullable();
            $table->decimal("tempo_estimado")->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('viagens', function (Blueprint $table) {
            $table->dropColumn("km");
            $table->dropColumn("tempo_estimado");
        });
    }
};
