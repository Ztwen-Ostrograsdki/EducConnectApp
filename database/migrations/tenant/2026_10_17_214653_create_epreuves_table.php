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

        Schema::create('epreuves', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            // Fichier (même logique que GeneratedDocument)
            $table->string('filename');  // nom de l'épreuve
            $table->string('path'); // chemin du fichier (via TenantStorage)

            // Contexte pédagogique
            $table->string('classe_name')->nullable()->default(null);    // nom de la classe (texte libre, conservé tel quel)
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->nullOnDelete();
            $table->foreignId('filiar_id')->nullable()->constrained('filiars')->nullOnDelete();
            $table->foreignId('classe_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('serial_id')->nullable()->constrained('serials')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('school_year_id')->constrained('school_years');
            $table->string('teacher_name')->nullable()->default('Non renseigné'); //Nom complet de l'enseignant connecté envoyant les épreuves

            // Auteur de l'envoi
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete(); //ID du user connecté envoyant les épreuves

            $table->integer('period');
            $table->string('period_as_label')->nullable()->default(null); //semestre-1
            $table->string('type'); // Devoir-1 devoir-2...
            $table->boolean("downloaded")->default(false); 
            $table->boolean("locked")->default(false); 
            $table->boolean("updatable")->default(true); 
            $table->integer("downloaded_count")->default(0);
            $table->unsignedBigInteger("lot")->default(null);
            $table->boolean('downloadable_by_others')->default(false);
            $table->string('school_year_slug')->nullable();

            // Visibilité : true = accessible à tous les utilisateurs, false = privé
            $table->boolean('published')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_year_id', 'published']);
            $table->index(['school_year_id', 'subject_id']);
            $table->index(['school_year_id', 'promotion_id', 'filiar_id']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('epreuves');
        Schema::enableForeignKeyConstraints();
    }
};