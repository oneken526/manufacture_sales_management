<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->string('unit_name', 20)
                  ->nullable()
                  ->after('standard_price')
                  ->comment('単位（個・本・kg 等）');
            $table->text('notes')
                  ->nullable()
                  ->after('unit_name')
                  ->comment('備考');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['unit_name', 'notes']);
        });
    }
};
