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
        Schema::create('request_to_create_new_tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));

            // your custom columns may go here

            $table->string('school_name');
            $table->string('simple_name');
            $table->string('school_slug')->nullable();
            $table->string('school_devise')->nullable();
            $table->string('domain_name')->unique();

            // USER
            $table->string('role')->nullable();
            $table->string('name');
            $table->string('prenames');
            $table->string('job_name');
            $table->string('contacts')->nullable();
            $table->string('adresse')->nullable()->default(null);
            $table->string('gender')->nullable()->default('M');
            $table->string('department')->nullable()->default(null);
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('email')->unique();
            $table->string('profil_photo')->nullable()->default(null);
            

            $table->string('enseignement_type')->default('general');
            $table->string('school_type')->default('public');
            $table->string('devoirs_type')->default('devoir1-devoir2');
            $table->string('periode_type')->default('semestre');
            $table->boolean('validated')->default(false);
            $table->string('status')->default('pending');
            
            $table->string('logo')->nullable();

            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('request_to_create_new_tenants');
        Schema::enableForeignKeyConstraints();
    }
};
