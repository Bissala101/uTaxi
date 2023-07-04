<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('motorista', function (Blueprint $table) {
            $table->integer("estado")->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('motorista', function (Blueprint $table) {
            $table->dropColumn("estado");
        });
    }
};
