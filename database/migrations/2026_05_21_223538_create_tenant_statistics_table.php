<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('tenant_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreign('tenant_id')
                  ->references('id')
                  ->on('tenants')
                  ->cascadeOnDelete();

            // ─── Effectifs ────────────────────────────────────────────
            $table->unsignedInteger('students_count')->default(0);
            $table->unsignedInteger('teachers_count')->default(0);
            $table->unsignedInteger('classes_count')->default(0);
            $table->unsignedInteger('parents_count')->default(0);

            // ─── Activité ─────────────────────────────────────────────
            $table->unsignedInteger('payments_count')->default(0);
            $table->unsignedInteger('payments_pending')->default(0);

            // ─── Taux ─────────────────────────────────────────────────
            $table->decimal('attendance_rate', 5, 2)->default(0);
            $table->decimal('payment_rate', 5, 2)->default(0);

            // ─── Année scolaire active ────────────────────────────────
            $table->string('current_school_year')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();

            $table->unique('tenant_id');
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tenant_statistics');
        Schema::enableForeignKeyConstraints();
    }
};