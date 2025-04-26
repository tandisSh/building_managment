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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->string('unit_number');    // شماره واحد
            $table->integer('floor')->nullable();   // طبقه
            $table->decimal('area', 8, 2)->nullable(); // متراژ
            $table->integer('parking_slots')->default(0); // تعداد جای پارک
            $table->integer('storerooms')->default(0);   // تعداد انباری
         
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
