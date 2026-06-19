<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('body_type', 100)->nullable()->after('mileage');
            $table->string('drive_type', 100)->nullable()->after('body_type');
            $table->integer('engine_cc')->nullable()->after('drive_type');
            $table->string('bike_type', 100)->nullable()->after('engine_cc');
            $table->string('start_type', 100)->nullable()->after('bike_type');
            $table->string('road_type', 100)->nullable()->after('start_type');
            $table->string('machinery_type', 100)->nullable()->after('road_type');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['body_type', 'drive_type', 'engine_cc', 'bike_type', 'start_type', 'road_type', 'machinery_type']);
        });
    }
};
