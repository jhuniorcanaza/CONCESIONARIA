<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('client')->after('email');
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};
