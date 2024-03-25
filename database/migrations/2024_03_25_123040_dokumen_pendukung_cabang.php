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
        Schema::table('dokumen_pendukungs', function (Blueprint $table) {
            $table->uuid('barantin_cabang_id')->nullanle()->after('baratin_id');
            $table->foreign('barantin_cabang_id')->references('id')->on('barantin_cabangs')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_pendukungs', function (Blueprint $table) {
            $table->dropForeign('dokumen_pendukungs_barantin_cabang_id_foreign');
            $table->dropColumn('barantin_cabang_id');
        });
    }
};
