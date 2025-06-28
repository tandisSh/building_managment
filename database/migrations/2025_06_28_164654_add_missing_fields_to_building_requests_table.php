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
        Schema::table('building_requests', function (Blueprint $table) {
            // اضافه کردن فیلدهای missing
            if (!Schema::hasColumn('building_requests', 'building_type')) {
                $table->enum('building_type', ['residential', 'commercial', 'mixed'])->nullable()->after('building_name');
            }
            if (!Schema::hasColumn('building_requests', 'building_address')) {
                $table->text('building_address')->nullable()->after('building_type');
            }
            if (!Schema::hasColumn('building_requests', 'total_units')) {
                $table->integer('total_units')->nullable()->after('building_address');
            }
            if (!Schema::hasColumn('building_requests', 'description')) {
                $table->text('description')->nullable()->after('total_units');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('building_requests', function (Blueprint $table) {
            $table->dropColumn(['building_type', 'building_address', 'total_units', 'description']);
        });
    }
};
