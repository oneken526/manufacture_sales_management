<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouses', function (Blueprint $table): void {
            $table->string('code', 50)->unique()->after('id');
            $table->dropColumn('location');
        });
    }

    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table): void {
            $table->dropUnique('warehouses_code_unique');
            $table->dropColumn('code');
            $table->string('location', 255)->nullable()->after('name');
        });
    }
};
