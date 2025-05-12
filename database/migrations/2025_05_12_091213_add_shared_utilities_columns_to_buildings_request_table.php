<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('building_requests', function (Blueprint $table) {
            $table->boolean('shared_water')->default(false)->after('shared_utilities');
            $table->boolean('shared_electricity')->default(false)->after('shared_water');
            $table->boolean('shared_gas')->default(false)->after('shared_electricity');
        });
    }

  public function down(): void
{
    Schema::table('building_requests', function (Blueprint $table) {
        $table->dropColumn(['shared_water', 'shared_electricity', 'shared_gas']);
    });
}

};
