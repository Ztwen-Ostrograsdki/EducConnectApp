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
        Schema::create('generated_documents', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('filename');
            $table->boolean("downloaded")->default(false);
            $table->integer("downloaded_count")->default(0);
            $table->boolean('downloadable_by_others')->default(false);
            $table->string('path');
            $table->string('url');
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->string('tenant_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_documents');
    }
};
