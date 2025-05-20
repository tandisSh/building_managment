<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // اضافه کردن تعداد ساکنین به واحدها
        Schema::table('units', function (Blueprint $table) {
            $table->unsignedInteger('residents_count')->default(1)->after('storerooms');
        });

        // اضافه کردن فیلدهای تقسیم هزینه به صورتحساب کلی
        Schema::table('bulk_invoices', function (Blueprint $table) {
            $table->enum('distribution_type', ['equal', 'per_person'])
                  ->default('equal')
                  ->after('type');

            $table->unsignedInteger('fixed_percent')
                  ->nullable()
                  ->after('distribution_type');
        });
    }

    public function down(): void {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('residents_count');
        });

        Schema::table('bulk_invoices', function (Blueprint $table) {
            $table->dropColumn(['distribution_type', 'fixed_percent']);
        });
    }
};
