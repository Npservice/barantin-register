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
        Schema::create('registers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pj_barantin_id')->nullable();
            $table->bigInteger('master_upt_id')->unsigned()->nullable();
            $table->enum('status', ['MENUNGGU', 'DISETUJUI', 'DITOLAK'])->nullable();
            $table->longText('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('pj_barantin_id')->references('id')->on('pj_baratins')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('master_upt_id')->references('id')->on('master_upts')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registers');
    }
};
