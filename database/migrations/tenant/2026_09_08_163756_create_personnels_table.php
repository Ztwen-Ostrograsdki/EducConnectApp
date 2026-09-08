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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));

            $table->string('name');
            $table->string('prenames');
            $table->date('birth_date')->nullable();
            $table->string('contacts')->nullable();
            $table->string('profil_photo')->nullable();

            $table->string('title')->nullable();       // ex: "Secrétaire administrative"
            $table->text('description')->nullable();

            $table->foreignId('school_year_id')
                ->nullable()
                ->constrained('school_years')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->boolean('hidden')->default(false);

            $table->string('gender')->nullable();
            $table->string('grade')->nullable();

            $table->date('since')->nullable();
            $table->date('ended_at')->nullable();

            $table->timestamps();

            $table->index(['is_active', 'hidden']);
            $table->index('grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};



