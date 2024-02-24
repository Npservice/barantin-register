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
        Schema::create('master_provinsis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode', 5)->nullable();
            $table->string('un_code', 5)->nullable();
            $table->string('nama', 32)->nullable();
            $table->string('nama_en', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_provinsis');
    }
};
