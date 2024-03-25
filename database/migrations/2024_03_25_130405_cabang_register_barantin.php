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
        Schema::table('registers', function (Blueprint $table) {
            $table->foreign('barantin_cabang_id')->references('id')->on('barantin_cabangs')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registers', function (Blueprint $table) {
            $table->dropForeign('registers_barantin_cabang_id_foreign');
        });
    }
};
