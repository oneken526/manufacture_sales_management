<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->decimal('quantity', 15, 3);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->decimal('shipped_quantity', 15, 3)->default(0);
            $table->smallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE order_details ADD CONSTRAINT chk_order_details_quantity CHECK (quantity >= 1)');
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
