<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_plan_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_plan_id')->constrained('time_plans')->cascadeOnDelete();
            $table->foreignId('classe_subject_of_school_year_id')
                ->constrained('classe_subject_of_school_years', 'id')->restrictOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 1=lundi ... 7=dimanche
            $table->time('starts_at');
            $table->time('ends_at');
            $table->string('label')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['time_plan_id', 'day_of_week', 'starts_at'], 'time_plan_slots_schedule_idx');
            $table->index(['classe_subject_of_school_year_id'], 'time_plan_slots_assignment_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_plan_slots');
    }
};
