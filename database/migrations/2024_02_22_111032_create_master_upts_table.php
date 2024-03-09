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
        Schema::create('master_upts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_satpel', 5)->nullable();
            $table->string('kode_upt', 5);
            $table->string('nama');
            $table->string('nama_en')->nullable();
            $table->string('wilayah_kerja', 50);
            $table->string('nama_satpel', 50)->nullable();
            $table->string('kota', 50)->nullable();
            $table->string('kode_pelabuhan', 45)->nullable();
            $table->string('tembusan')->nullable();
            $table->string('otoritas_pelabuhan')->nullable();
            $table->string('syah_bandar_pelabuhan')->nullable();
            $table->string('kepala_kantor_bea_cukai')->nullable();
            $table->string('nama_pengelola')->nullable();
            $table->enum('stat_ppkol', ['Y', 'N'])->default('Y')->nullable();
            $table->enum('stat_insw', ['Y', 'N'])->default('N')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_upts');
    }
};
