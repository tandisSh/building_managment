<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('total_amount', 'amount');
            $table->string('title')->after('unit_id');
            $table->dropColumn('status'); 
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('amount', 'total_amount');
            $table->dropColumn('title');
            $table->enum('status', ['paid', 'unpaid', 'partial'])->default('unpaid');
        });
    }
};
