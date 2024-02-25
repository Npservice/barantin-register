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
        Schema::create('mail_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pre_register_id')->nullable();
            $table->unsignedBigInteger('pj_baratan_kpp_id')->nullable();
            $table->string('token')->unique();
            $table->timestamp('expire_at_token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_tokens');
    }
};
