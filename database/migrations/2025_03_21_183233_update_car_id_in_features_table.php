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
        Schema::table('car_features', function (Blueprint $table) {
            // $table->dropForeign(['car_id']);
            $table->foreign('car_id')->references('id')->on('cars')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_features', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
