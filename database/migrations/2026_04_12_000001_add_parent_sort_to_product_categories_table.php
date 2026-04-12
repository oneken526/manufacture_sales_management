<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $table): void {
            $table->foreignId('parent_id')
                  ->nullable()
                  ->after('name')
                  ->constrained('product_categories')
                  ->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')
                  ->default(0)
                  ->after('parent_id')
                  ->comment('表示順（昇順）');
        });
    }

    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table): void {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'sort_order']);
        });
    }
};
