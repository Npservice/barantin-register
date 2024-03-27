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
        Schema::create('mitra_perusahaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pj_baratin_id');
            $table->uuid('barantin_cabang_id')->nullable();

            $table->string('nama_mitra')->nullable();
            $table->string('jenis_identitas_mitra')->nullable();
            $table->string('nomor_identitas_mitra')->nullable();
            $table->string('alamat_mitra')->nullable();
            $table->string('telepon_mitra')->nullable();

            $table->bigInteger('master_negara_id')->unsigned()->nullable();
            $table->bigInteger('master_provinsi_id')->unsigned()->nullable();
            $table->bigInteger('master_kota_kab_id')->unsigned()->nullable();



            $table->foreign('barantin_cabang_id')->references('id')->on('barantin_cabangs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('pj_baratin_id')->references('id')->on('pj_baratins')->cascadeOnDeleter()->cascadeOnUpdate();

            $table->foreign('master_negara_id')->references('id')->on('master_negaras')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('master_provinsi_id')->references('id')->on('master_provinsis')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('master_kota_kab_id')->references('id')->on('master_kota_kabs')->cascadeOnDelete()->cascadeOnUpdate();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_perusahaans');
    }
};
