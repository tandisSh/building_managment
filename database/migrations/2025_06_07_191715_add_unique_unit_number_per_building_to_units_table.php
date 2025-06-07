<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->unique(['building_id', 'unit_number'], 'unique_unit_per_building');
        });
    }

    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropUnique('unique_unit_per_building');
        });
    }
};
