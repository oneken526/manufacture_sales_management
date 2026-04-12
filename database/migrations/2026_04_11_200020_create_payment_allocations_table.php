<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_allocations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('invoices')->restrictOnDelete();
            $table->decimal('allocated_amount', 15, 2);
            $table->timestamp('allocated_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE payment_allocations ADD CONSTRAINT chk_payment_allocations_amount CHECK (allocated_amount > 0)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};
