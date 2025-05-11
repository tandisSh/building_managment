<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop composite PK before adding 'id'
        Schema::table('unit_user', function (Blueprint $table) {
            $table->dropPrimary(); // حذف primary key ترکیبی فعلی
        });

        Schema::table('unit_user', function (Blueprint $table) {
            $table->id()->first(); // افزودن فیلد id جدید به عنوان primary key
        });

        Schema::table('building_user', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('building_user', function (Blueprint $table) {
            $table->id()->first();
        });
    }

    public function down(): void
    {
        Schema::table('unit_user', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->primary(['user_id', 'unit_id']); // برگرداندن کلید اصلی ترکیبی
        });

        Schema::table('building_user', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->primary(['user_id', 'building_id']);
        });
    }
};

