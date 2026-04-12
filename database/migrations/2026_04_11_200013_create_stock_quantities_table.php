<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_quantities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('stock_lot_id')->constrained('stock_lots')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();
            $table->decimal('quantity', 15, 3)->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['stock_lot_id', 'warehouse_id']);
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE stock_quantities ADD CONSTRAINT chk_stock_quantities_quantity CHECK (quantity >= 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_quantities');
    }
};
