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
        Schema::create('bulk_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->decimal('base_amount', 12, 2);
            $table->decimal('water_cost', 12, 2)->nullable();
            $table->decimal('electricity_cost', 12, 2)->nullable();
            $table->decimal('gas_cost', 12, 2)->nullable();
            $table->enum('type', ['current', 'fixed']);
            $table->date('due_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_invoices');
    }
};
