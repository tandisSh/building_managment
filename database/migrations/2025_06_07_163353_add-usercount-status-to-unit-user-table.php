<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // حذف فیلد resident_count از جدول units
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('resident_count');
        });

        // افزودن فیلد resident_count و status به جدول unit_user
        Schema::table('unit_user', function (Blueprint $table) {
            $table->unsignedInteger('resident_count')->default(1)->after('role');
            $table->enum('status', ['active', 'inactive', 'pending', 'rejected'])->default('active')->after('resident_count');
        });
    }

    public function down()
    {
        // برگرداندن فیلد resident_count به جدول units
        Schema::table('units', function (Blueprint $table) {
            $table->unsignedInteger('resident_count')->default(1)->after('storerooms');
        });

        // حذف فیلدهای اضافه‌شده از جدول unit_user
        Schema::table('unit_user', function (Blueprint $table) {
            $table->dropColumn('resident_count');
            $table->dropColumn('status');
        });
    }
};
