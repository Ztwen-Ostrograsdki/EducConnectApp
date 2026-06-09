<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));

            // your custom columns may go here

            $table->string('school_name');
            $table->string('simple_name')->nullable()->default(null);
            $table->string('domain_name')->unique();
            $table->string('school_slug')->nullable();
            $table->string('school_devise')->nullable();

            // USER
            $table->string('role')->nullable();
            $table->string('name');
            $table->string('prenames');
            $table->string('job_name');
            $table->string('contacts')->nullable();
            $table->string('adresse')->nullable()->default(null);
            $table->string('department')->nullable()->default(null);
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('email')->unique();
            $table->string('profil_photo')->nullable()->default(null);
            $table->date('birth_date')->nullable()->default(null);
            $table->string('gender')->nullable()->default('Masculin');
            
            $table->string('enseignement_type')->default('general');
            $table->string('school_type')->default('public');
            $table->string('devoirs_type')->default('devoir1-devoir2');
            $table->string('periode_type')->default('semestre');
            $table->string('status')->default('pending');
            
            $table->boolean('domain_blocked')->default(false);
            $table->boolean('open_only_for_tenant')->default(false);
            $table->boolean('completed')->default(false);
            $table->integer('stage')->default(1);
            $table->string('logo')->nullable();
            $table->timestamp('date_expiration_abonnement')->nullable();

            $table->unsignedBigInteger('request_id')->nullable()->default(null);

            $table->softDeletes();

            $table->timestamps();
            $table->json('data')->nullable();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tenants');
        Schema::enableForeignKeyConstraints();
    }
}
