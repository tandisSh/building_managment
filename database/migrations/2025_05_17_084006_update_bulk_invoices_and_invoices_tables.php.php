<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('bulk_invoices', function (Blueprint $table) {
            $table->dropColumn([
                'water_cost',
                'electricity_cost',
                'gas_cost'
            ]);

            $table->string('title')->after('building_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('title')->after('unit_id');
        });
    }

    public function down(): void
    {
        Schema::table('bulk_invoices', function (Blueprint $table) {
            $table->integer('water_cost')->nullable();
            $table->integer('electricity_cost')->nullable();
            $table->integer('gas_cost')->nullable();
            $table->dropColumn('title');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
