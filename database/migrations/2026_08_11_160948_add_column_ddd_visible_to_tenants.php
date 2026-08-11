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
        Schema::table('tenants', function (Blueprint $table) {
            $table->boolean('ae_can_edit_coef')->default(false);
            $table->boolean('ca_can_edit_coef')->default(false);
            $table->boolean('tutors_can_download_bulletin')->default(false);
            $table->boolean('tutors_can_see_bulletin')->default(false);
            $table->boolean('pp_can_edit_coef')->default(false);
            $table->boolean('force_2fa')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            //
        });
    }
};
