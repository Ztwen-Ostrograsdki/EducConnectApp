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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->string('qr_code')->nullable();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('school_year_id')->constrained('school_years');
            $table->foreignId('tutor_id')->nullable()->constrained('tutors')->nullOnDelete();
            $table->string('reference')->unique();               // numéro de reçu
            $table->enum('type', [
                'inscription',
                'scolarite',
                'transport',
                'cantine',
                'autre',
            ])->default('scolarite');
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_payed', 10, 2)->default(0);
            $table->decimal('remained_amount', 10, 2)->default(0);
            $table->enum('status', ['en_attente', 'partiel', 'complet'])->default('en_attente');
            $table->string('payment_mode')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('observations')->nullable();
            $table->foreignId('registred_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'school_year_id']);
            $table->index(['status', 'type']);
            $table->index('payment_date');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('payments');
        Schema::enableForeignKeyConstraints();
    }
};
