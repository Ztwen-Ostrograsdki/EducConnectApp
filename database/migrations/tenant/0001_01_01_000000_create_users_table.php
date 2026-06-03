<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->string('name');
            $table->string('prenames');
            $table->string('job_name');
            $table->string('tenant_id')->nullable()->default(null);
            $table->string('school_name')->nullable()->default(null);
            $table->string('contacts')->nullable()->default(null);
            $table->string('adresse')->nullable()->default(null);
            $table->string('country')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->string('department')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('profil_photo')->nullable()->default(null);

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('logged_count')->default(0);
            $table->boolean('is_super_admin')->default(false);
            $table->boolean('blocked')->default(false);
            $table->boolean('cannot_edit_classes')->default(false);
            $table->string('gender')->nullable()->default('Masculin');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};
