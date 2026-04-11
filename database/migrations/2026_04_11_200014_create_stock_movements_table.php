<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('stock_lot_id')->constrained('stock_lots')->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();
            $table->foreignId('target_warehouse_id')->nullable()
                  ->constrained('warehouses')->nullOnDelete();
            $table->enum('movement_type', [
                'inbound',
                'outbound',
                'transfer',
                'adjustment',
                'return',
            ]);
            $table->decimal('quantity', 15, 3);
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('memo')->nullable();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();
            // updated_at なし（履歴テーブル）、softDeletes なし
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
