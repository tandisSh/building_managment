<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bulk_invoices', function (Blueprint $table) {
            $table->string('status')->default('pending'); // یا enum اگر خواستی
        });
    }

    public function down()
    {
        Schema::table('bulk_invoices', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
