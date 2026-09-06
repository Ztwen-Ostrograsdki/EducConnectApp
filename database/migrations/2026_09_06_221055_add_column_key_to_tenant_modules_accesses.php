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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('subscription_requests');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('tenant_module_accesses');

        Schema::create('subscription_requests', function (Blueprint $table) {
            $table->id();
            $table->string('key', 10)->unique();
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->restrictOnDelete();

            $table->string('transaction_id')->nullable();

            $table->enum('status', ['pending', 'payment_claimed', 'approved', 'rejected'])
                ->default('pending');

            $table->text('reject_reason')->nullable();
            $table->timestamp('payment_reminder_sent_at')->nullable();

            $table->foreignId('treated_by')->nullable()->constrained('central_users')->nullOnDelete();
            $table->timestamp('treated_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->string('key', 10)->unique();
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->restrictOnDelete();
            $table->foreignId('subscription_request_id')->nullable()
                ->constrained('subscription_requests')->nullOnDelete();

            $table->timestamp('started_at');
            $table->timestamp('expire_at');
            $table->boolean('is_free')->default(false);
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('tenant_module_accesses', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();

            // Une ligne d'accès modules par subscription
            $table->foreignId('subscription_id')
                ->nullable()
                ->constrained('subscriptions')
                ->nullOnDelete();

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

            // ─── Notes & Classements ──────────────────────────────────
            $table->boolean('marks_management')->default(true);
            $table->boolean('rankings')->default(false);
            $table->boolean('marks_reports')->default(false);

            // ─── Impressions & Documents ──────────────────────────────
            $table->boolean('custom_prints')->default(false);
            $table->boolean('printable_docs')->default(false);

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

            // Un accès modules par subscription (plusieurs historiques par tenant possibles)
            $table->unique('subscription_id');
            $table->index('tenant_id');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_modules_accesses', function (Blueprint $table) {
            //
        });
    }
};
