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
        Schema::create('tutors', function (Blueprint $table) {
           $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->string('qr_code')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('prenames');
            $table->string('email')->nullable()->unique();
            $table->string('contacts')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->enum('gender', ['M', 'F'])->default('M');
            $table->string('profession')->nullable();
            $table->text('adresse')->nullable();
            $table->enum('status', ['active', 'unactive'])->default('active');
            $table->boolean('blocked')->default(false);
            $table->text('blocked_reasons')->default("Non précisée");
            $table->timestamps();
            $table->softDeletes();
            $table->index(['name', 'prenames']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
