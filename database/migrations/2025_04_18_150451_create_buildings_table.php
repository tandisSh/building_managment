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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('address');
            $table->boolean('shared_utilities')->default(false); // آب و برق مشترک؟
            $table->integer('number_of_floors')->nullable();     // تعداد طبقات
            $table->integer('number_of_units')->nullable();      // تعداد کل واحدها
            $table->timestamps();
        });


            // $table->boolean('has_elevator')->default(false);    // آسانسور داره؟
            // $table->boolean('has_guard')->default(false);       // نگهبان داره؟
            // $table->boolean('has_cctv')->default(false);         // دوربین مدار بسته؟
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
