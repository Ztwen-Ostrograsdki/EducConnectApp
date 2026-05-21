<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_module_accesses', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();

            // ─── Pack souscrit ────────────────────────────────────────
            $table->enum('pack', ['starter', 'pro', 'premium', 'custom'])
                ->default('starter');

            $table->timestamp('pack_started_at')->nullable();
            $table->timestamp('pack_expires_at')->nullable();

            // ─── Communications ───────────────────────────────────────
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('whatsapp_notifications')->default(false);

            // ─── Bulletins & Documents ────────────────────────────────
            $table->boolean('pdf_bulletins')->default(true);
            $table->boolean('bulletin_email_send')->default(false);
            $table->boolean('bulletin_whatsapp_send')->default(false);
            $table->boolean('bulletin_sms_send')->default(false);

            // ─── Statistiques & Rapports ──────────────────────────────
            $table->boolean('semester_statistics')->default(false);
            $table->boolean('annual_statistics')->default(false);
            $table->boolean('attendance_reports')->default(false);
            $table->boolean('payment_reports')->default(false);
            $table->boolean('performance_reports')->default(false);

            // ─── Paiements ────────────────────────────────────────────
            $table->boolean('online_payments')->default(false);
            $table->boolean('payment_reminders')->default(false);
            $table->boolean('payment_receipts')->default(true);

            // ─── Import / Export ──────────────────────────────────────
            $table->boolean('excel_import')->default(true);
            $table->boolean('excel_export')->default(false);
            $table->boolean('pdf_export')->default(false);

            // ─── Portails ─────────────────────────────────────────────
            $table->boolean('parent_portal')->default(true);
            $table->boolean('student_portal')->default(false);
            $table->boolean('teacher_portal')->default(true);

            // ─── Avancés ──────────────────────────────────────────────
            $table->boolean('multi_period')->default(false);
            $table->boolean('timetable')->default(false);
            $table->boolean('library_management')->default(false);
            $table->boolean('canteen_management')->default(false);
            $table->boolean('transport_management')->default(false);

            // ─── Méta ─────────────────────────────────────────────────
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_module_accesses');
    }
};
