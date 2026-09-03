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
        Schema::create('subscription_requests', function (Blueprint $table) {
            $table->id();
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
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('subscription_requests');
        Schema::enableForeignKeyConstraints();
    }
};
