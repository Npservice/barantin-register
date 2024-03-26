<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pre_registers', function (Blueprint $table) {
            $table->foreign('pj_baratin_id')->references('id')->on('pj_baratins')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registers', function (Blueprint $table) {
            $table->dropForeign('pre_registers_pj_baratin_id_foreign');
        });
    }
};
