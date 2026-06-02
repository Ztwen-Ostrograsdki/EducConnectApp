<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('password_token_for_resets', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();

            // Token pour lien email (hashé)
            $table->string('token');

            // Code OTP (6 chiffres hashé)
            $table->string('otp_code')->nullable();

            // nombre de tentatives OTP
            $table->unsignedTinyInteger('attempts')->default(0);

            // expiration globale
            $table->timestamp('expires_at');

            // usage tracking
            $table->timestamp('used_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_token_for_resets');
    }
};
