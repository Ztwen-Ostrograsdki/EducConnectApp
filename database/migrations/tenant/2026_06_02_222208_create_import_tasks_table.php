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
        Schema::create('import_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name')->nullable()->default(null);
            $table->string('crud')->nullable()->default(null);
            $table->text('error')->nullable();
            $table->string('status')->nullable()->default('pending');
            $table->string('batch_id')->nullable()->default(null);
            $table->integer('attempts')->default(0);
            $table->json('payload');

            $table->timestamps();

            $table->index(['batch_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_tasks');
    }
};
