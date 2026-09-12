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
            $table->string('background_image')->nullable()->default(null);
            $table->boolean('hide_testimonials_on_home_page')->default(false);
            $table->boolean('hide_galleries_on_home_page')->default(false);
            $table->boolean('hide_serials_on_home_page')->default(false);
            $table->boolean('hide_filiars_on_home_page')->default(false);
            $table->boolean('hide_personnels_on_home_page')->default(false);
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
