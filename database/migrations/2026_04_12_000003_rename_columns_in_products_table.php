<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->renameColumn('product_code', 'code');
            $table->renameColumn('product_category_id', 'category_id');
            $table->renameColumn('standard_price', 'unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->renameColumn('code', 'product_code');
            $table->renameColumn('category_id', 'product_category_id');
            $table->renameColumn('unit_price', 'standard_price');
        });
    }
};
