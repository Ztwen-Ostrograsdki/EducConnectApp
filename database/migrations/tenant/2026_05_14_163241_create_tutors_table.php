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
        Schema::disableForeignKeyConstraints();
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->text('qr_code')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email')->nullable()->unique();
            $table->string('whatsapp_number')->nullable();
            $table->string('job_name')->nullable()->default(null);
            $table->enum('status', ['active', 'unactive'])->default('active');
            $table->boolean('blocked')->default(false);
            $table->string('blocked_reasons')->default('Non précisée');
            $table->timestamp('affiliated_at')->nullable(); 
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tutors');
        Schema::enableForeignKeyConstraints();
    }
};
